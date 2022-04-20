<?php
class Admin extends MY_AdminPanel {   

        public $uid;       
        public $langs;
	public function __construct() 
        {
            parent::__construct();
            $this->uid = $this->session->userdata('uid');               
            $this->lang->load('vars','romanian');
            $this->load->model('admin_model');
            $this->langs = $this->mysql->get_All('langs',array('status'=>1),'','','ord','asc');
                        
        }        
        
	public function index()
	{
            $this->main();
            //$this->output->enable_profiler(TRUE);
            /*echo "<pre>";
            print_r($this->session->all_userdata());   
            echo "</pre>";*/
	}
                  
	public function main($reset=null)
	{           
           $data['breadcrumbs'] = $this->breadcrumbs->main_panel();
           $this->display->backend('main',$data);
	}
   
       
         public function active_cons($parent = 0,$id = null,$erorrs = null)
        {
          $id = $this->input->get('id');
          $consultant = $this->mysql->get_row('offers',array('id'=>$id));
          if ($consultant['status']==0) {
              $status = 1;} else {
                  $status = 0;
              }
          $this->mysql->update('offers', array('status'=>$status),array('id'=>$id));
        redirect('/admin/main');
        }

/***************** pages ******************/

        public function pages($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());

            $data['breadcrumbs'] = $this->breadcrumbs->pages($parent);
            $data['crumbs_pages'] = $this->display->crumbs_pages($this->mysql->get_row('pages',array('id_pages'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            $data['pages'] = $this->admin_model->get_sub_items('pages',$parent,'ord','asc');
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('pages',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/pages');
               else  redirect('admin/pages/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('pages',array('id_pages'=>$parent)),0,'pages');
            
            
            $this->display->backend('catalog/pages',$data);
            // $this->output->enable_profiler(TRUE);
	}

        public function pages_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->pages();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['gallery'] = $this->mysql->get_All('photo_pages',array('pid'=>$id));
                $data['title_page'] = lang('edit_pages');
                $data['action'] = '/admin/edit_pages';
                $data['page'] = $this->mysql->get_row('pages',array('id_pages'=>$id));
                
            }
            else
            {
                $data['title_page'] = lang('add_pages');
                $data['action'] = '/admin/add_pages';
            }
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('pages',array('id_pages'=>$parent)),0,'pages');
            $this->display->backend('catalog/pages_form',$data);
        }
        
        public function get_last_photo()
        {
              $table = $this->input->post('table');
              $data['photo'] = $this->mysql->get_row($table,array('phid'=>$this->mysql->get_max($table,'phid')));   
              $this->load->view('catalog/one_photo',$data);
            
        }
        public function del_photos()
        {
             //if($dif>14) unlink($path.$oldFile[$i]); 
             if($this->input->post('id')) 
             {   
                 $table = $this->input->post('table');
                 $photo =$this->mysql->get_row($table,array('phid'=>$this->input->post('id')));   
                 unlink($_SERVER['DOCUMENT_ROOT'].$photo['path']);
                 unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);
                 $this->mysql->delete($table,array('phid'=>$this->input->post('id')));   
             }
            
        }
        public function edit_pages()
        {

             $data['breadcrumbs'] = $this->breadcrumbs->pages();
             $pages = $this->input->post('page');
             $this->admin_model->edit_pages($pages,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_photo2'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_pages($this->input->post('id'),'photo_ro');
             $erorr_image .= $this->upload_photo_pages($this->input->post('id'),'photo_en');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

             if($this->input->post('send')) $this->send_news($this->input->post('id'));
             $this->output->enable_profiler(TRUE);
             redirect('admin/pages/'.$this->input->post('parent'));
        }


        public function add_pages()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->pages();
                $this->admin_model->add_pages($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('pages','id_pages');
               $erorr_image = $this->upload_photo_pages($last_id,'photo_ro');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

               // daca este bifat send news
               if($this->input->post('send')) $this->send_news($last_id);
                //$this->output->enable_profiler(TRUE);
                redirect('admin/pages/'.$this->input->post('parent'));
        }

        public function del_pages($parent = 0)
        {
               $this->admin_model->del_pages($this->input->post('selected'));
               redirect('admin/pages/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_pages($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/pages/';
             
             $dir2 = 'uploads/pages/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
           
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                if($fisier=='photo_ro') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);
/*
                if($fisier=='image')  $this->mysql->update('pages',array('photo' =>'/'.$dir2.$file),array('id_pages'=>$pid));
                else $this->mysql->update('pages',array('photo' =>'/'.$new_name),array('id_pages'=>$pid));*/

                if($fisier=='photo_ro')  $this->mysql->update('pages',array('photo_ro' =>'/'.$dir2.$file),array('id_pages'=>$pid));
                else $this->mysql->update('pages',array('photo_en' =>'/'.$dir2.$file),array('id_pages'=>$pid));
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }
        
/***************** end pages ***********/
         
/***************** auto ******************/

        public function auto($parent = 0, $id = null)
	{
          /*  
            $auto = $this->mysql->get_All('auto');  
            foreach($auto as $item)
            {
                $this->mysql->update('auto',array('ord'=>$item['id']),array('id'=>$item['id']));
            }    
            */
            
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->auto($parent);
            $data['crumbs_auto'] = $this->display->crumbs_auto($this->mysql->get_row('auto',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['auto'] = $this->admin_model->get_sub_items('auto',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('auto',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/auto');
               else  redirect('admin/auto/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('auto',array('id'=>$parent)),0,'auto');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/auto/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('auto',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/auto',$data);
	}

        public function auto_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->auto();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_auto');
                $data['action'] = '/admin/edit_auto';
                $data['page'] = $this->mysql->get_row('auto',array('id'=>$id));               
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('auto',array('id'=>$parent)),0,'auto');
            }
            else
            {
                $data['title_page'] = lang('add_auto');
                $data['action'] = '/admin/add_auto';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('auto',array('id'=>$parent)),0,'auto');
            }

            $this->display->backend('catalog/auto_form',$data);
        }

        public function edit_auto()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->auto();
             $auto = $this->input->post('page');
             $this->admin_model->edit_auto($auto,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //$this->output->enable_profiler(TRUE);
             redirect('admin/auto/'.$this->input->post('parent'));
        }

        public function add_auto()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->auto();
                $this->admin_model->add_auto($this->input->post('page'),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/auto/'.$this->input->post('parent'));
        }

        public function del_auto($parent = 0)
        {
               $this->admin_model->del_auto($this->input->post('selected'));
               redirect('admin/auto/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end auto ***********/ 
        
        /***************** catalog ******************/

        public function catalog($parent = 0, $id = null)
	{
          /*  
            $auto = $this->mysql->get_All('auto');  
            foreach($auto as $item)
            {
                $this->mysql->update('auto',array('ord'=>$item['id']),array('id'=>$item['id']));
            }    
            */
            
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->catalog($parent);
            $data['crumbs_catalog'] = $this->display->crumbs_catalog($this->mysql->get_row('catalog',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['catalog'] = $this->admin_model->get_sub_items('catalog',$parent,'id','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('catalog',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/catalog');
               else  redirect('admin/catalog/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog',array('id'=>$parent)),0,'catalog');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/catalog/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('catalog',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/catalog',$data);
	}

        public function catalog_form($parent = 0,$id = null,$erorrs = null)
        {
    
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->catalog();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            $data['carcase_dulapuri'] = $this->mysql->get_All('moto',array('parent'=>0));
            if($parent > 0) {
                $data['parentparent'] = $this->mysql->get_row('catalog',array('id'=>$parent));
            }
            
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_catalog');
                $data['action'] = '/admin/edit_catalog';
                $data['page'] = $this->mysql->get_row('catalog',array('id'=>$id));           
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                 $data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$id),'','','ord','asc');
                  $data['colors'] = $this->mysql->get_All('text');   
                  $data['aditionale'] = $this->mysql->get_All('banners');   
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog',array('id'=>$parent)),0,'catalog');
            }
            else
            {
                $data['title_page'] = lang('add_catalog');
                $data['action'] = '/admin/add_catalog';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog',array('id'=>$parent)),0,'catalog');
            }
             $data['time']       = time();
            $this->display->backend('catalog/catalog_form',$data);
        }
        
        public function active_multi_prices() {
            if (isset($_POST['multi_price']) && $_POST['multi_price'] == 1) {
             $valoare = $_POST['multi_price'];
            } else {
             $valoare = 0;
            }
            $this->mysql->update('catalog',array('multi_price'=>$valoare),array('id'=>$this->input->post('id')));   
           redirect('admin/catalog_form/'.$this->input->post('parent').'/'.$this->input->post('id')); 
        }
        
        public function edit_catalog()
        {
          
          
             $data['breadcrumbs'] = $this->breadcrumbs->catalog();
             $catalog = $this->input->post('page');
             $preturi = $this->input->post('pret');
             $preturi_red = $this->input->post('pret_red');
             $this->admin_model->edit_catalog($catalog,$this->input->post('id'),$this->input->post('del_photo'),$preturi,$preturi_red);
            //incarcam imagrinea
             $erorr_image = $this->upload_photo_catalog($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
            
             
//             $erorr_image_color = $this->upload_photo_color_catalog($this->input->post('id'),$this->input->post('imcol_name'));
//             if($erorr_image_color) { $this->session->set_flashdata('error',$erorr_image); echo 'id-'.$erorr_image; } else { echo 'else -'.$erorr_image;}
//             $erorr_image_color = $this->upload_photo_color_catalog($this->input->post('id'),'imcol4');
//             if($erorr_image_color) { $this->session->set_flashdata('error',$erorr_image); echo 'id-'.$erorr_image; } else { echo 'else -'.$erorr_image;}
             //$this->output->enable_profiler(TRUE);
            redirect('admin/catalog/'.$this->input->post('parent'));
        }
        public function update_color() {
             $colors = array();
            foreach($_POST as $key=>$value) {
              if (preg_match('/color/',$key)) {
                  $colors['culori'][$key] = $value;  
                } else if(preg_match('/adiacente/',$key)) {
                  $colors['culori_adiac'][$key] = $value;   
                }
            }
            $str = serialize($colors);
          /*  echo '<pre>';
             print_r($_POST); 
            print_r($colors);
            print_r($str);*/
            
            $this->mysql->update_color('catalog',array('colors'=>$str),$this->input->post('id'));
            redirect('admin/catalog_form/'.$this->input->post('parent').'/'.$this->input->post('id').'#photos');
        }
        
        public function serdec() {
            
           // $ser = 'a:2:{s:6:"culori";a:7:{s:7:"color18";s:9:"344 Cires";s:7:"color20";s:7:"381 Fag";s:7:"color21";s:8:"722 Alun";s:7:"color24";s:17:"2226 Wenghe Magia";s:7:"color25";s:20:"2380 Limba Ciocolata";s:7:"color26";s:19:"2381 Limba Deschisa";s:7:"color27";s:14:"8130 Angelique";}s:12:"culori_adiac";a:7:{s:11:"adiacente18";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";} s:11:"adiacente20";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente21";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente24";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente25";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente26";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente27";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}}';
          $ser = 'a:7:{s:11:"adiacente18";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";} s:11:"adiacente20";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente21";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente24";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente25";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente26";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}s:11:"adiacente27";a:2:{i:6;s:13:"Oglinda + Pal";i:7;s:16:"Oglinda +Oglinda";}';
            $uns = unserialize($ser);
            $decse = base64_encode($ser);
            echo $decse;
            echo '<pre>';
            print_r($uns);
            
        }
        public function edit_upload_photo() {
             $page = $this->mysql->get_row('catalog',array('id'=>$this->input->post('id')));  
			 
             $culori_existente  = unserialize($page['colors']);
            // print_r($culori_existente);die();
             foreach($culori_existente['culori'] as $key=>$culoare) {
                 $color_id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                $this->upload_photo_color_catalog($this->input->post('id'),'imcol'.$color_id); 
                if (isset($culori_existente['culori_adiac'])) {
                foreach($culori_existente['culori_adiac']['adiacente'.$color_id] as $key=>$culoare_ad) {
                  $adiacent_id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
                  $this->upload_photo_color_catalog($this->input->post('id'),'imcolad'.$adiacent_id,$color_id); 
                }}
             }
              
             redirect('admin/catalog_form/'.$this->input->post('parent').'/'.$this->input->post('id').'#photos');
        }
        
         public function delete_foto_color() {
            $parent = $this->mysql->get_row('catalog',array('id'=>$this->input->get('id')));  
            //echo $this->input->get('id').' - '.$this->input->get('id_color');
            if (isset($_GET['adi_id']) && $_GET['adi_id'] != '') {
            $this->mysql->delete('photo_anunt',array('anunt_id'=>$this->input->get('id'),'color_id'=>$this->input->get('id_color'),'aditional_id'=>$_GET['adi_id']));    
            } else {
            $this->mysql->delete('photo_anunt',array('anunt_id'=>$this->input->get('id'),'color_id'=>$this->input->get('id_color')));
            }
             redirect('admin/catalog_form/'.$parent['parent'].'/'.$this->input->get('id').'#photos');
        }
        public function add_catalog()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->catalog();
                $this->admin_model->add_catalog($this->input->post('page'),$this->input->post('parent'),  $this->input->post('pret'));

                //incarcam imagrinea
               $last_id = $this->mysql->get_max('catalog','id');
             $erorr_image = $this->upload_photo_catalog($last_id,'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
//$this->output->enable_profiler(TRUE);
                redirect('admin/catalog/'.$this->input->post('parent'));
        }

        public function del_catalog($parent = 0)
        {
               $this->admin_model->del_catalog($this->input->post('selected'));
               redirect('admin/catalog/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
          public function upload_photo_catalog($pid,$fisier)
        {
           $erorr_image = '';
				
           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
            
             
             $dir = 'uploads/catalog/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
           
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('catalog',array('photo' =>'/'.$dir.$file),array('id'=>$pid));
                else $this->mysql->update('catalog',array('photo' =>'/'.$new_name),array('id'=>$pid));

//                if($fisier=='photo_ro')  $this->mysql->update('pages',array('photo_ro' =>'/'.$dir2.$file),array('id_pages'=>$pid));
//                else $this->mysql->update('pages',array('photo_en' =>'/'.$dir2.$file),array('id_pages'=>$pid));
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_catalog,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }
           public function upload_photo_color_catalog($pid,$fisier,$ad = null)
        {
             // print_r($fisier);die();
           $erorr_image = '';

          // if($fisier=='image') $err_text = lang('photo_general').' :';
          // else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
            
             
             $dir = '../uploads/catalog_photo/';
             $dir_name = 'uploads/catalog_photo/';
              $rand = rand();
             $filename = $pid.'-'.$rand.'-';
            //echo $filename;
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
             $config['file_name'] = $filename;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
               
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                $new_name =  $dir_name.$file;
              
                
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);
                if (isset($ad) && $ad != '') {
                $color_id = filter_var($ad, FILTER_SANITIZE_NUMBER_INT);
                $aditional_id = filter_var($fisier, FILTER_SANITIZE_NUMBER_INT);
                $this->mysql->insert('photo_anunt',array('path' =>'/'.$new_name, 'color_id'=>$color_id, 'anunt_id'=>$pid, 'aditional_id'=>$aditional_id));
                } else {
                 $color_id = filter_var($fisier, FILTER_SANITIZE_NUMBER_INT);
                $this->mysql->insert('photo_anunt',array('path' =>'/'.$new_name, 'color_id'=>$color_id, 'anunt_id'=>$pid));   
                }
              }
             else
             {
                $erorr_image = $this->upload->display_errors($err_catalog,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }


/***************** end catalog ***********/ 
        
        /***************** truck ******************/

        public function truck($parent = 0, $id = null)
	{
          /*  
            $truck = $this->mysql->get_All('truck');  
            foreach($truck as $item)
            {
                $this->mysql->update('truck',array('ord'=>$item['id']),array('id'=>$item['id']));
            }    
            */
            
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->truck($parent);
            $data['crumbs_truck'] = $this->display->crumbs_truck($this->mysql->get_row('truck',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['truck'] = $this->admin_model->get_sub_items('truck',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('truck',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/truck');
               else  redirect('admin/truck/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('truck',array('id'=>$parent)),0,'truck');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/truck/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('truck',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/truck',$data);
	}

        public function truck_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->truck();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_truck');
                $data['action'] = '/admin/edit_truck';
                $data['page'] = $this->mysql->get_row('truck',array('id'=>$id));               
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('truck',array('id'=>$parent)),0,'truck');
            }
            else
            {
                $data['title_page'] = lang('add_truck');
                $data['action'] = '/admin/add_truck';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('truck',array('id'=>$parent)),0,'truck');
            }

            $this->display->backend('catalog/truck_form',$data);
        }

        public function edit_truck()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->truck();
             $truck = $this->input->post('page');
             $this->admin_model->edit_auto($truck,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //$this->output->enable_profiler(TRUE);
             redirect('admin/truck/'.$this->input->post('parent'));
        }

        public function add_truck()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->truck();
                $this->admin_model->add_truck($this->input->post('page'),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/truck/'.$this->input->post('parent'));
        }

        public function del_truck($parent = 0)
        {
               $this->admin_model->del_auto($this->input->post('selected'));
               redirect('admin/truck/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end truck ***********/    
        
        
        
         /***************** moto ******************/

        public function moto($parent = 0, $id = null)
	{
          /*  
            $truck = $this->mysql->get_All('truck');  
            foreach($truck as $item)
            {
                $this->mysql->update('truck',array('ord'=>$item['id']),array('id'=>$item['id']));
            }    
            */
            
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->moto($parent);
            $data['crumbs_moto'] = $this->display->crumbs_moto($this->mysql->get_row('moto',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['moto'] = $this->admin_model->get_sub_items('moto',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder2('moto',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/moto');
               else  redirect('admin/moto/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('moto',array('id'=>$parent)),0,'moto');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/moto/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('moto',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/moto',$data);
	}

        public function moto_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->moto();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_moto');
                $data['action'] = '/admin/edit_moto';
                $data['page'] = $this->mysql->get_row('moto',array('id'=>$id));               
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('moto',array('id'=>$parent)),0,'moto');
            }
            else
            {
                $data['title_page'] = lang('add_moto');
                $data['action'] = '/admin/add_moto';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('moto',array('id'=>$parent)),0,'moto');
            }

            $this->display->backend('catalog/moto_form',$data);
        }

        public function edit_moto()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->moto();
             $moto = $this->input->post('page');
             $this->admin_model->edit_moto($moto,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
              //incarcam imagrinea
             $erorr_image = $this->upload_photo_moto($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             

//$this->output->enable_profiler(TRUE);
             redirect('admin/moto/'.$this->input->post('parent'));
        }

        public function add_moto()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->moto();
                $this->admin_model->add_moto($this->input->post('page'),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/moto/'.$this->input->post('parent'));
        }

        public function del_moto($parent = 0)
        {
               $this->admin_model->del_moto($this->input->post('selected'));
               redirect('admin/moto/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
        
         public function upload_photo_moto($pid,$fisier)
        {
           $erorr_image = '';
				
           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
            
             
             $dir = 'uploads/moto/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
           
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('moto',array('photo' =>'/'.$dir.$file),array('id'=>$pid));
                else $this->mysql->update('moto',array('photo' =>'/'.$new_name),array('id'=>$pid));

//                if($fisier=='photo_ro')  $this->mysql->update('pages',array('photo_ro' =>'/'.$dir2.$file),array('id_pages'=>$pid));
//                else $this->mysql->update('pages',array('photo_en' =>'/'.$dir2.$file),array('id_pages'=>$pid));
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_catalog,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }


/***************** end moto ***********/ 
        
         
/***************** zona ******************/

        public function zona($parent = 0, $id = null)
	{
          /*  
            $zona = $this->mysql->get_All('zona');  
            foreach($zona as $item)
            {
                $this->mysql->update('zona',array('ord'=>$item['id']),array('id'=>$item['id']));
            }    
            */
            
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->zona($parent);
            $data['crumbs_zona'] = $this->display->crumbs_zona($this->mysql->get_row('zona',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['zona'] = $this->admin_model->get_sub_items('zona',$parent,'subzona','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('zona',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/zona');
               else  redirect('admin/zona/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('zona',array('id'=>$parent)),0,'zona');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/zona/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('zona',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/zona',$data);
	}

        public function zona_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->zona();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_zona');
                $data['action'] = '/admin/edit_zona';
                $data['page'] = $this->mysql->get_row('zona',array('id'=>$id));               
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_zona');
                $data['action'] = '/admin/add_zona';
                $data['nivele'] = 0;
            }

            $this->display->backend('catalog/zona_form',$data);
        }

        public function edit_zona()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->zona();
             $zona = $this->input->post('page');
             $this->admin_model->edit_zona($zona,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //$this->output->enable_profiler(TRUE);
             redirect('admin/zona/'.$this->input->post('parent'));
        }

        public function add_zona()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->zona();
                $this->admin_model->add_zona($this->input->post('page'),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/zona/'.$this->input->post('parent'));
        }

        public function del_zona($parent = 0)
        {
               $this->admin_model->del_zona($this->input->post('selected'));
               redirect('admin/zona/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end zona ***********/             
/***************** obiective ******************/

        public function obiective($parent = 0, $id = null)
	{
         
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->obiective($parent);
            $data['crumbs_obiective'] = $this->display->crumbs_obiective($this->mysql->get_row('obiective',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['obiective'] = $this->admin_model->get_sub_items('obiective',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('obiective',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/obiective');
               else  redirect('admin/obiective/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('obiective',array('id'=>$parent)),0,'obiective');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/obiective/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('obiective',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/obiective',$data);
	}

        public function obiective_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->obiective();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_obiective');
                $data['action'] = '/admin/edit_obiective';
                $data['page'] = $this->mysql->get_row('obiective',array('id'=>$id)); 
                $data['judet']      = $this->mysql->get_All('judet',array('parent'=>0));
                $data['localitatea']= $this->mysql->get_All('judet',array('parent'=>$data['page']['judet_id']));                 
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('obiective',array('id'=>$parent)),0,'obiective');
            }
            else
            {
                $data['title_page'] = lang('add_obiective');
                $data['action'] = '/admin/add_obiective';
                $data['judet']      = $this->mysql->get_All('judet',array('parent'=>0));
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('obiective',array('id'=>$parent)),0,'obiective');
            }

            $this->display->backend('catalog/obiective_form',$data);
        }

        public function edit_obiective()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->obiective();
             $obiective = $this->input->post('page');
             $this->admin_model->edit_obiective($obiective,$this->input->post(),$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //$this->output->enable_profiler(TRUE);
             redirect('admin/obiective/'.$this->input->post('parent'));
        }

        public function add_obiective()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->obiective();
                $this->admin_model->add_obiective($this->input->post('page'),$this->input->post(),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/obiective/'.$this->input->post('parent'));
        }

        public function del_obiective($parent = 0)
        {
               $this->admin_model->del_obiective($this->input->post('selected'));
               redirect('admin/obiective/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end obiective ***********/    
                                        
/***************** banners ******************/
        /*
         * Ajax Call
         * return json
         */
        public function ajax_get_categorii()
        {   
            $categorii = $this->mysql->get_All('categoria',array('rubrica_id'=>$this->input->get('id')));
            $this->load->library('html');
            echo  $this->html->option_tag($categorii);
        }
        
        public function banners($parent = 0, $id=null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->banners($parent);
            $data['crumbs_banners'] = $this->display->crumbs_banners($this->mysql->get_row('banners',array('id_banners'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['banners'] = $this->mysql->get_All('banners',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('banners',$id,$_GET['ac']);
                redirect('admin/banners');
            }             
            $this->display->backend('catalog/banners',$data);
	}

        public function banners_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->banners();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_banners');
                $data['action'] = '/admin/edit_banners';
                $data['page'] = $this->mysql->get_row('banners',array('id_banners'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_banners');
                $data['action'] = '/admin/add_banners';
            }

            $this->display->backend('catalog/banners_form',$data);
        }

        public function edit_banners()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->banners();
             $banners = $this->input->post('page');
             $this->admin_model->edit_banners($banners,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_banners($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/banners/'.$this->input->post('parent'));
        }

        public function add_banners()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->banners();
                $this->admin_model->add_banners($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('banners','id_banners');
               $erorr_image = $this->upload_photo_banners($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/banners/'.$this->input->post('parent'));
        }

        public function del_banners($parent = 0)
        {
               $this->admin_model->del_banners($this->input->post('selected'));
               redirect('admin/banners/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_banners($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/banners/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('banners',array('photo' =>'/'.$new_name),array('id_banners'=>$pid));
                else $this->mysql->update('banners',array('photo' =>'/'.$new_name),array('id_banners'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end banners ***********/    
         
         
         /***************** social ******************/
        /*
         * Ajax Call
         * return json
         */
       
        
        public function social($parent = 0, $id=null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->social($parent);
            $data['crumbs_social'] = $this->display->crumbs_social($this->mysql->get_row('social',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['social'] = $this->mysql->get_All('social',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('social',$id,$_GET['ac']);
                redirect('admin/social');
            }             
            $this->display->backend('catalog/social',$data);
	}
        
        public function social_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->social();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_social');
                $data['action'] = '/admin/edit_social';
                $data['page'] = $this->mysql->get_row('social',array('id'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_social');
                $data['action'] = '/admin/add_social';
            }

            $this->display->backend('catalog/social_form',$data);
        }

        public function edit_social()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->social();
             $social = $this->input->post('page');
             $this->admin_model->edit_social($social,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_social($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/social/'.$this->input->post('parent'));
        }

        public function add_social()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->social();
                $this->admin_model->add_social($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('social','id');
               $erorr_image = $this->upload_photo_social($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/social/'.$this->input->post('parent'));
        }

        public function del_social($parent = 0)
        {
               $this->admin_model->del_social($this->input->post('selected'));
               redirect('admin/social/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_social($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/social/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('social',array('photo' =>'/'.$new_name),array('id'=>$pid));
                else $this->mysql->update('social',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end social ***********/    
         
         
         
         /***************** news ******************/
        /*
         * Ajax Call
         * return json
         */
//        public function ajax_get_categorii()
//        {   
//            $categorii = $this->mysql->get_All('categoria',array('rubrica_id'=>$this->input->get('id')));
//            $this->load->library('html');
//            echo  $this->html->option_tag($categorii);
//        }
        
        public function news($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->news($parent);
            $data['crumbs_news'] = $this->display->crumbs_news($this->mysql->get_row('news',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['news'] = $this->mysql->get_All('news',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('news',$id,$_GET['ac']);
                redirect('admin/news');
            }             
            $this->display->backend('catalog/news',$data);
	}

        public function news_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->news();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = 'Editeaza Noutati';//lang('edit_news');
                $data['action'] = '/admin/edit_news';
                $data['page'] = $this->mysql->get_row('news',array('id'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_news');
                $data['action'] = '/admin/add_news';
            }

            $this->display->backend('catalog/news_form',$data);
        }

        public function edit_news()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->news();
             $news = $this->input->post('page');
             $this->admin_model->edit_news($news,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_news($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/news/'.$this->input->post('parent'));
        }

        public function add_news()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->news();
                $this->admin_model->add_news($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('news','id');
               $erorr_image = $this->upload_photo_news($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/news/'.$this->input->post('parent'));
        }

        public function del_news($parent = 0)
        {
               $this->admin_model->del_news($this->input->post('selected'));
               redirect('admin/news/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_news($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/news/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('news',array('photo' =>'/'.$new_name),array('id'=>$pid));
                else $this->mysql->update('news',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end news ***********/      
         
         
       
                  /***************** text ******************/
        /*
         * Ajax Call
         * return json
         */
//        public function ajax_get_categorii()
//        {   
//            $categorii = $this->mysql->get_All('categoria',array('rubrica_id'=>$this->input->get('id')));
//            $this->load->library('html');
//            echo  $this->html->option_tag($categorii);
//        }
        
        public function text($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
			
            $data['breadcrumbs'] = $this->breadcrumbs->text($parent);
			
            $data['crumbs_text'] = $this->display->crumbs_news($this->mysql->get_row('text',array('id'=>$parent)));
			
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['text'] = $this->mysql->get_All('text',array('parent'=>$parent),'','','ord','asc');
			//print_r( $data['text']);die();
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('text',$id,$_GET['ac']);
                redirect('admin/text');
            }             
            $this->display->backend('catalog/text',$data);
	}

        public function text_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->text();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = 'Editeaza Text';//lang('edit_text');
                $data['action'] = '/admin/edit_text';
                $data['page'] = $this->mysql->get_row('text',array('id'=>$id));
            }
            else
            {
                $data['title_page']  = lang('add_text');
                $data['action']      = '/admin/add_text';
            }

            $this->display->backend('catalog/text_form',$data);
        }

        public function edit_text()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->text();
             $text = $this->input->post('page');
             $this->admin_model->edit_text($text,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_text($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/text/'.$this->input->post('parent'));
        }

        public function add_text()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->text();
                $this->admin_model->add_text($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('text','id');
               $erorr_image = $this->upload_photo_text($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/text/'.$this->input->post('parent'));
        }

        public function del_text($parent = 0)
        {
               $this->admin_model->del_text($this->input->post('selected'));
               redirect('admin/text/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_text($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/text/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('text',array('photo' =>'/'.$new_name),array('id'=>$pid));
                else $this->mysql->update('text',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end text ***********/   
         
    
/***************** slider ******************/

        
        public function slider($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->slider($parent);
            $data['crumbs_slider'] = $this->display->crumbs_news($this->mysql->get_row('slider',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['slider'] = $this->mysql->get_All('slider',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder2('slider',$id,$_GET['ac']);
                redirect('admin/slider');
            }             
            $this->display->backend('catalog/slider',$data);
	}

        public function slider_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->slider();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = 'Editeaza Slider';//lang('edit_text');
                $data['action'] = '/admin/edit_slider';
                $data['page'] = $this->mysql->get_row('slider',array('id'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_slider');
                $data['action'] = '/admin/add_slider';
            }

            $this->display->backend('catalog/slider_form',$data);
        }

        public function edit_slider()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->slider();
             $slider = $this->input->post('page');
             $this->admin_model->edit_slider($slider,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_slider($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/slider/'.$this->input->post('parent'));
        }

        public function add_slider()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->slider();
                $this->admin_model->add_slider($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('slider','id');
               $erorr_image = $this->upload_photo_slider($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/slider/'.$this->input->post('parent'));
        }

        public function del_slider($parent = 0)
        {
               $this->admin_model->del_slider($this->input->post('selected'));
               redirect('admin/slider/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_slider($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_slider = lang('photo_general').' :';
           else  $err_slider = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/slider/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('slider',array('photo' =>'/'.$new_name),array('id'=>$pid));
                else $this->mysql->update('slider',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_slider,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end slider ***********/   
         
            /***************** offers ******************/
        /*
         * Ajax Call
         * return json
         */

        
        public function offers($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->offers($parent);
            $data['crumbs_offers'] = $this->display->crumbs_news($this->mysql->get_row('offers',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['offers'] = $this->mysql->get_All('offers',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder2('offers',$id,$_GET['ac']);
                redirect('admin/offers');
            }             
            $this->display->backend('catalog/offers',$data);
	}

        public function offers_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->offers();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = 'Edit Offers';//lang('edit_text');
                $data['action'] = '/admin/edit_offers';
                $data['page'] = $this->mysql->get_row('offers',array('id'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_offers');
                $data['action'] = '/admin/add_offers';
            }

            $this->display->backend('catalog/offers_form',$data);
        }

        public function edit_offers()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->offers();
             $offers = $this->input->post('page');
             $this->admin_model->edit_offers($offers,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_offers($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/offers/'.$this->input->post('parent'));
        }

        public function add_offers()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->offers();
                $this->admin_model->add_offers($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('offers','id');
               $erorr_image = $this->upload_photo_offers($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/offers/'.$this->input->post('parent'));
        }

        public function del_offers($parent = 0)
        {
               $this->admin_model->del_offers($this->input->post('selected'));
               redirect('admin/offers/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_offers($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_offers = lang('photo_general').' :';
           else  $err_offers = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/offers';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .'/'.$pid.$file_ext;
                else  $new_name =  $dir .'/'.$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir.'/'.$file, $new_name);

                if($fisier=='image')  $this->mysql->update('offers',array('photo' =>'/'.$new_name),array('id'=>$pid));
                else $this->mysql->update('offers',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_offers,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end offers ***********/   
         
                    
/***************** baner ******************/

        public function baner($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->baner($parent);
            $data['crumbs_baner'] = $this->display->crumbs_baner($this->mysql->get_row('baner',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
          //paginare   
           $data['baner'] = $this->mysql->get_All('baner','',$settings['per_page_admin'],$this->uri->segment(3),'position','asc');
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/baner/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('baner'));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare            
            
            
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('baner',$id,$_GET['ac']);
                redirect('admin/baner');
            }      
            
            $this->display->backend('catalog/baner',$data);
	}

        public function baner_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->baner();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            $data['rubrici']   = $this->mysql->get_All('rubrica');
            
            
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_baner');
                $data['action'] = '/admin/edit_baner';
                $data['page'] = $this->mysql->get_row('baner',array('id'=>$id));
                if($data['page']['categoria_id']) $data['categorii'] = $this->mysql->get_All('categoria',array('rubrica_id'=>$data['page']['rubrica_id']));
                else  $data['categorii'] = $this->mysql->get_All('categoria',array('rubrica_id'=>$data['rubrici'][0]['id']));
            }
            else
            {
                $data['title_page'] = lang('add_baner');
                $data['action'] = '/admin/add_baner';
                $data['categorii'] = $this->mysql->get_All('categoria',array('rubrica_id'=>$data['rubrici'][0]['id']));
            }

            $this->display->backend('catalog/baner_form',$data);
        }

        public function edit_baner()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->baner();
             $baner = $this->input->post('page');
             $this->admin_model->edit_baner($baner,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_baner($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             
             
             
             if($baner[1]['position'] ==0 ) {
                 $this->session->set_flashdata('error','Pozitia nu poate avea valoare 0');
                 redirect('admin/baner_form/'.$this->input->post('parent').'/'.$this->input->post('id'));
             }
             if(!is_numeric($baner[1]['position'])) {
                 $this->session->set_flashdata('error','Pozitia trebuie sa contina numai numere');
                 redirect('admin/baner_form/'.$this->input->post('parent').'/'.$this->input->post('id'));
             }             
             
             $row = $this->mysql->get_row('baner',array('position'=>$baner[1]['position'],'rubrica_id'=>$baner[1]['rubrica_id'],'categoria_id' => $baner[1]['categoria_id'],'id !='=>$this->input->post('id')));
             
             if(isset($row['title_ro'])) {
                 $this->session->set_flashdata('error','Banerul cu denumirea : '.$row['title_ro'].' deja are asa pozitie !!!');
                 $last_id = $this->mysql->get_max('baner','id');
                 $this->mysql->update('baner',array('position'=>$last_id),array('id'=>$this->input->post('id')));
                 redirect('admin/baner_form/'.$this->input->post('parent').'/'.$this->input->post('id'));
             }             
             
             redirect('admin/baner/'.$this->input->post('parent'));
        }

        public function add_baner()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->baner();
                $this->admin_model->add_baner($this->input->post('page'),$this->input->post('parent'));
                 $baner = $this->input->post('page');
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('baner','id');
               $erorr_image = $this->upload_photo_baner($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
             if(!is_numeric($baner[1]['position'])) {
                 $this->session->set_flashdata('error','Pozitia trebuie sa contina numai numere');
                 $this->mysql->update('baner',array('position'=>$last_id),array('id'=>$this->input->post('id')));                 
                 redirect('admin/baner_form/'.$this->input->post('parent').'/'.$last_id);
             }               
            redirect('admin/baner/'.$this->input->post('parent'));
        }

        public function del_baner($parent = 0)
        {
               $this->admin_model->del_baner($this->input->post('selected'));
               redirect('admin/baner/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_baner($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/baner/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);

                if($fisier=='image')  $this->mysql->update('baner',array('photo' =>'/'.$new_name),array('id '=>$pid));
                else $this->mysql->update('baner',array('photo' =>'/'.$new_name),array('id'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end baner ***********/                 
     
 
         public function get_photo_add()
    {     
          if($this->input->post('anunt_id')) {$data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$this->input->post('anunt_id')),'','','ord','asc');}   
          else  { $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc'); }  
          if($data['photo'])
          {             
              echo $this->load->view('/catalog/foto_anunt',$data,TRUE);
              
          }
    }  
    

    public function del_photo()
    {     
          $id         = $this->input->post('id');
          $anunt_id   = $this->input->post('anunt_id');
          $foto       = $this->mysql->get_row('photo_anunt',array('id'=>$id));
          if($foto)
          {   
              $this->mysql->delete('photo_anunt',array('id'=>$id));
              if($anunt_id) $data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$anunt_id),'','','ord','asc');   
              else  $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc');   
              echo $this->load->view('/catalog/foto_anunt',$data,TRUE);
          } 
    }    
        
    public function move_photo()
    {     
          $id        = $this->input->post('id');
          $type      = $this->input->post('type');
          $anunt_id  = $this->input->post('anunt_id');
          $foto      = $this->mysql->get_row('photo_anunt',array('id'=>$id));
          if($foto)
          {   
              if($anunt_id)
              {
                if($this->mysql->reorder_photo('photo_anunt',$id,$type,'anunt_id',$anunt_id))
                {        
                  $data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$anunt_id),'','','ord','asc');                   
                  echo $this->load->view('/catalog/foto_anunt',$data,TRUE);
                }                  
              }
              else
              {    
                if($this->mysql->reorder_photo('photo_anunt',$id,$type,'time',$this->input->post('time')))
                {        
                  $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc');                   
                  echo $this->load->view('/catalog/foto_anunt',$data,TRUE);
                }
              }
          } 
    } 
    
    function _create_thumbnail($fileName) 
    {
     $this->load->library('image_lib');
             $config['image_library'] = 'gd2';
             $config['source_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;       
             $config['create_thumb'] = TRUE;
             $config['master_dim'] = 'auto';
             $config['maintain_ratio'] = FALSE;
             $config['width'] = 161;
             $config['height'] = 100;
             $config['new_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;               
             $this->image_lib->initialize($config);
             if(!$this->image_lib->resize()) echo
             $this->image_lib->display_errors();

   }    
   /*
   * Resize Image
   * @param (string)$file_id    -path image
   * @param (string)$file_name  -name of image
   * @param (int)$width         - width image
   * @param (int)$height        - height image
   * @param (string)$mode       - exact/prop
   */  
 public function resize_image($file_id,$data_file,$width,$height,$mode='')
{
    // $file_id = path
    $src = $_SERVER["DOCUMENT_ROOT"].'/'.$file_id;
    $explode[0] = str_replace( '.', '_', $data_file['raw_name'] );
    
    $file_name = $data_file['client_name'];
    $ext = $data_file['file_ext'];
    $lib = ('.bmp' == $ext) ? "ImageMagick" : "GD2";
    $this->load->library('image_lib');
    
             switch ($mode) {
                       case 'exact':

                                        //resize and crop

                                        $image_path = $data_file['full_path'];
                        
                                      
                                        //The original sizes
                                        $original_size = getimagesize($image_path);
                                        $original_width = $original_size[0];
                                        $original_height = $original_size[1];
                                        $ratio = $original_width / $original_height;

                                        //The requested sizes
                                        $requested_width = $width;
                                        $requested_height = $height;

                                        //Initialising 
                                        $new_width = 0;
                                        $new_height = 0;

                                        //Calculations
                                        if ($requested_width > $requested_height) {
                                                $new_width = $requested_width;
                                                $new_height = $new_width / $ratio;
                                                if ($requested_height == 0)
                                                        $requested_height = $new_height;

                                                if ($new_height < $requested_height) {
                                                        $new_height = $requested_height;
                                                        $new_width = $new_height * $ratio;
                                                }

                                        }
                                        else {
                                                $new_height = $requested_height;
                                                $new_width = $new_height * $ratio;
                                                if ($requested_width == 0)
                                                        $requested_width = $new_width;

                                                if ($new_width < $requested_width) {
                                                        $new_width = $requested_width;
                                                        $new_height = $new_width / $ratio;
                                                }
                                        }

                                        $new_width = ceil($new_width);
                                        $new_height = ceil($new_height);

                                        //Resizing

                                        $config = array();
                                        $config['image_library']    = $lib;
                                        
                                        $config['source_image']     = $image_path;
                                        $config['new_image']        = $explode[0].'_'.$width.$height.$ext;
                                        $config['maintain_ratio']   = FALSE;
                                        $config['quality']			= 100;
                                        $config['height']           = $new_height;
                                        $config['width']            = $new_width;
                                        $this->image_lib->initialize($config);
                                        $this->image_lib->resize();
                                        $this->image_lib->clear();

                                        //Crop if both width and height are not zero
                                        if (($width != 0) && ($height != 0)) {
                                                $x_axis = floor(($new_width - $width) / 2);
                                                $y_axis = floor(($new_height - $height) / 2);

                                                //Cropping
                                                $config = array();
                                                $config['image_library']    = $lib;
                                                
                                                $config['source_image']     = $_SERVER['DOCUMENT_ROOT'].'/'.$file_id.'/'.$explode[0].'_'.$width.$height.$ext;
                                                $config['maintain_ratio']   = FALSE;
                                                $config['quality']			= 100;
                                                $config['new_image']        = $explode[0].'_'.$width.$height.$ext;
                                                $config['width']            = $width;
                                                $config['height']           = $height;
                                                $config['x_axis']           = $x_axis;
                                                $config['y_axis']           = $y_axis;
                                                $this->image_lib->initialize($config);
                                                $this->image_lib->crop();
                                                $this->image_lib->clear();
                                            $array = array(
                                                'path' => $file_id.$explode[0].'_'.$width.$height.$ext,
                                                'name' =>  $file_name.'_'.$ext
                                        ); 
                                               
                                        }
                                             

                                break;
                                              

                               

                                case 'prop':

                                        $config = array(
                                            'image_library'     => $lib,
                                            'quality'			=> 100,
                                            
                                            'source_image'      => $file_id,
                                            'new_image'         => $file_name.'_'.$width.'x'.$height.$ext,
                                            'maintain_ratio'    => TRUE,
                                            'width'             => $width,
                                            'height'            => $height
                                        );

                                        $this->image_lib->initialize($config);
                                        $this->image_lib->resize();
                                        $this->image_lib->clear();

                                                
                        break;

                }




               
                return $array;

        }
 
    public function do_upload() {
     //  echo "<br />time = ".$this->input->post('time'); 
        $all = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')));
        $nr = count($all);       
        if($nr < 10)
        {    
            $this -> load -> library('image_lib'); 
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/photo_anunt/';
            $config['allowed_types'] = 'gif|jpg|png|jpe|jpeg|gif*';  
            $config['encrypt_name'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $this -> load -> library('upload', $config);
            // Output json as response
            if(!$this -> upload -> do_upload()) {
                    $json['status'] = 'error';
                    $json['issue'] = $this -> upload -> display_errors('', '');  
            } else {
                    $upload_arr = $this -> upload -> data();                        
                    $json['status'] = 'success';
                    foreach($this->upload->data() as $k => $v) {
                            $json[$k] = $v;
                    }
                    $new_name = '/uploads/photo_anunt/'.$upload_arr['file_name'];
                    //$insert_id = $this->mysql->insert('photo_anunt',array('anunt_id' =>$this->input->post('pid')));                      
                    $raw_name = $upload_arr['raw_name'];
                    $file_ext = $upload_arr['file_ext'];                    
                    
                    $timp = time();
                    $new_name = $config['upload_path'].$timp.$file_ext;
                    $file = $upload_arr['file_name'];                    
                    /*rename($config['upload_path'] . $file, $new_name);*/ 
                    
                    if($this->input->post('anunt_id')!='undefined') {$insert_id = $this->mysql->insert('photo_anunt',array('path' =>'/uploads/photo_anunt/'.$raw_name.$file_ext,'anunt_id' =>$this->input->post('anunt_id')));}
                    else  {$insert_id = $this->mysql->insert('photo_anunt',array('path' =>'/uploads/photo_anunt/'.$raw_name.$file_ext,'time' =>$this->input->post('time')));}
                    $path = '/uploads/photo_anunt/';
                    $thumb = $this->resize_image($path,$upload_arr,161,60,'exact');
                    $thumb2 = $this->resize_image($path,$upload_arr,86,60,'exact');
                    /*$this->_create_thumbnail('/uploads/photo_anunt/'.$timp.$file_ext);   */                   
                    $thum_name = $thumb['path'];/*'/uploads/photo_anunt/'.$timp. '_thumb'.$file_ext;*/
                    $thum_name2 = $thumb2['path'];
                    
                    // ordinea
                     if($this->input->post('anunt_id')!='undefined')  { $max_ord = $this->mysql->get_max('photo_anunt','ord',array('anunt_id'=>$this->input->post('anunt_id')));}
                     else  { $max_ord = $this->mysql->get_max('photo_anunt','ord',array('time'=>$this->input->post('time')));}
                    
                    $this->mysql->update('photo_anunt',array('thumb' =>$thum_name,'thumb2' =>$thum_name2, 'ord'=>($max_ord+1)),array('id'=>$insert_id));  
            }                  
           //  echo "post_id = ".$this->input->post('anunt_id');
            
           // echo json_encode($json);                                               
	}
    }
    
    
     /*
     * Ajax Call
     * return json
     */
         
         
         
       /*  public function do_upload() {
           echo 'sd'; 
        $table = 'app_photo_products';
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/admin/uploads/app_photo_products/';
		$config['allowed_types'] = 'gif|jpg|png|jpe|jpeg';
               
                //$config['file_name'] =$id_folder.$config['file_ext'];                
		$this -> load -> library('upload', $config);
		// Output json as response
		if(!$this -> upload -> do_upload()) { 
                    
			$json['status'] = 'error';
			$json['issue'] = $this -> upload -> display_errors('', '');                       
                        
		} else {
			$upload_arr = $this -> upload -> data();                        
			$json['status'] = 'success';
			foreach($this->upload->data() as $k => $v) {
				$json[$k] = $v;
			}
                        
                        $raw_name = $upload_arr['raw_name'];
                        $file_ext = $upload_arr['file_ext'];
                        
                         
                        
   						
                        $src_name = '/uploads/'.$table.'/'.$upload_arr['file_name'];
                        $new_name = '/uploads/'.$table.'/'.$raw_name.$file_ext;
			$this->_create_thumbnail($src_name,$new_name,800,800); 
                        
                        
                        
			//$new_name_rs = '/uploads/'.$table.'/'.$raw_name.$file_ext;;						
                        $insert_id = $this->mysql->insert($table,array('pid' =>$this->input->post('pid')));  
                        $this->mysql->update($table,array('path' =>$new_name),array('phid'=>$insert_id));  
                        
                        
                        $thum_name = '/uploads/'.$table.'/'.$raw_name. '_thumb'.$file_ext;;
                        $this->_create_thumbnail($src_name,$thum_name,210,210);  
                        
                        $this->mysql->update($table,array('path_thumb' =>$thum_name),array('phid'=>$insert_id));  
                        
                        unlink($_SERVER['DOCUMENT_ROOT'].$src_name);
                       
		}                  
                echo json_encode($json);                                
               

	}*/
        

}
?>
