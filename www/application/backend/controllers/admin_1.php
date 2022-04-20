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
            $this->langs = $this->mysql->get_All('langs');
            
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
   
          
/***************** produse ******************/

        public function produse($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->produse($parent);
            $data['crumbs_produse'] = $this->display->crumbs_produse($this->mysql->get_row('produse',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            $data['nivele'] = $this->mysql->get_nivele2($this->mysql->get_row('produse',array('id'=>$parent)),0,'produse');
            
            if($data['nivele']>1)
            {    
               $data['produse'] = $this->admin_model->get_sub_items_new2('produse_options',$parent,'ord','desc',$settings['per_page_admin'],$this->uri->segment(4));
               
               
                // calculam nivele
               $data['nivele'] = 0;
              //paginare   
               $this->load->library('pagination');
               $config['base_url']    = base_url().'/admin/produse/'.$parent;
               $config['total_rows']  = count($this->mysql->get_All('produse_options',array('produs_id'=>$parent)));
               $config['per_page']    = $settings['per_page_admin'];          
               $config['uri_segment'] = 4;
               $config['num_links']    = 8;
               $config['full_tag_open'] = '<div class="links">';
               $config['full_tag_close'] = '</div>';    
               $config['first_link'] = lang('first');
               $config['last_link'] = lang('last');
               $this->pagination->initialize($config); 
               $data['pagination']=$this->pagination->create_links();
               // end paginare
               $this->display->backend('products/produse_optoins',$data);
            
            }
            else
            {    
                $data['produse'] = $this->admin_model->get_sub_items_new('produse',$parent,'ord','desc',$settings['per_page_admin'],$this->uri->segment(4));
              
              //paginare   
               $this->load->library('pagination');
               $config['base_url']    = base_url().'/admin/produse/'.$parent;
               $config['total_rows']  = count($this->mysql->get_All('produse',array('parent'=>$parent)));
               $config['per_page']    = $settings['per_page_admin'];          
               $config['uri_segment'] = 4;
               $config['num_links']    = 8;
               $config['full_tag_open'] = '<div class="links">';
               $config['full_tag_close'] = '</div>';    
               $config['first_link'] = lang('first');
               $config['last_link'] = lang('last');
               $this->pagination->initialize($config); 
               $data['pagination']=$this->pagination->create_links();
               // end paginare               
             //  $data['produse'] = $this->mysql->get_All('produse',array('parent'=>$parent));
               $this->display->backend('products/produse',$data);
            }
	}

        public function produse_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->produse();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_produse');
                $data['action'] = '/admin/edit_produse';
                $data['page'] = $this->mysql->get_row('produse',array('id'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = $this->mysql->get_nivele2($this->mysql->get_row('produse',array('id'=>$parent)),0,'produse');
            }
            else
            {
                $data['title_page'] = lang('add_produse');
                $data['action'] = '/admin/add_produse';
                $data['nivele'] = $this->mysql->get_nivele2($this->mysql->get_row('produse',array('id'=>$parent)),0,'produse');
            }

            $this->display->backend('products/produse_form',$data);
        }

        public function edit_produse()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->produse();
             $produse = $this->input->post('page');
             $this->admin_model->edit_produse($produse,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_produse($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             $this->output->enable_profiler(TRUE);
             redirect('admin/produse/'.$this->input->post('parent'));
        }

        public function add_produse()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->produse();
                $this->admin_model->add_produse($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('produse','id');
               $erorr_image = $this->upload_photo_produse($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/produse/'.$this->input->post('parent'));
        }

        public function del_produse($parent = 0)
        {
               $this->admin_model->del_produse($this->input->post('selected'));
               redirect('admin/produse/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_produse($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/produse/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
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
				
	        if($this->_create_thumbnail('/'.$new_name,120,120)) {unlink($new_name);}
			 
                if($fisier=='image')
                {
                    $this->mysql->update('produse',array('path_img' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));
                }
                                
                else $this->mysql->update('produse',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('produse',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));                
			
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end produse ***********/    
/***************** produse_options ******************/

        public function produse_options($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->produse_options($parent);
            $data['crumbs_produse_options'] = $this->display->crumbs_produse_options($this->mysql->get_row('produse_options',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            $data['nivele'] = 0;
            
             
                $data['produse_options'] = $this->admin_model->get_sub_items_new('produse',$parent,'ord','desc',$settings['per_page_admin'],$this->uri->segment(4));
              
              //paginare   
               $this->load->library('pagination');
               $config['base_url']    = base_url().'/admin/produse_options/'.$parent;
               $config['total_rows']  = count($this->mysql->get_All('produse_options',array('produs_id'=>$parent)));
               $config['per_page']    = $settings['per_page_admin'];          
               $config['uri_segment'] = 4;
               $config['num_links']    = 8;
               $config['full_tag_open'] = '<div class="links">';
               $config['full_tag_close'] = '</div>';    
               $config['first_link'] = lang('first');
               $config['last_link'] = lang('last');
               $this->pagination->initialize($config); 
               $data['pagination']=$this->pagination->create_links();
               // end paginare               
             //  $data['produse_options'] = $this->mysql->get_All('produse_options',array('parent'=>$parent));
               $this->display->backend('products/produse_options',$data);
           
	}

        public function produse_options_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->produse_options();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_produse_options');
                $data['action'] = '/admin/edit_produse_options';
                $data['page'] = $this->mysql->get_row('produse_options',array('id'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
              //  $data['nivele'] = $this->mysql->get_nivele3($this->mysql->get_row('produse_options',array('id'=>$parent)),0,'produse_options');
            }
            else
            {
                $data['title_page'] = lang('add_produse_options');
                $data['action'] = '/admin/add_produse_options';
              //  $data['nivele'] = $this->mysql->get_nivele3($this->mysql->get_row('produse_options',array('id'=>$parent)),0,'produse_options');
            }

            $this->display->backend('products/produse_options_form',$data);
        }

        public function edit_produse_options()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->produse_options();
             $produse_options = $this->input->post('page');
             $this->admin_model->edit_produse_options($produse_options,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_produse_options($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             redirect('admin/produse/'.$this->input->post('parent'));
        }

        public function add_produse_options()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->produse_options();
                $this->admin_model->add_produse_options($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('produse_options','id');
               $erorr_image = $this->upload_photo_produse_options($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/produse/'.$this->input->post('parent'));
        }

        public function del_produse_options($parent = 0)
        {
               $this->admin_model->del_produse_options($this->input->post('selected'));
               redirect('admin/produse/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_produse_options($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/produse_options/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
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
				
				if($this->_create_thumbnail('/'.$new_name,700,1000)) {unlink($new_name);}
			 
                if($fisier=='image')
				{
				$this->mysql->update('produse_options',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));
				}
                else $this->mysql->update('produse_options',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('produse_options',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));                
			
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end produse_options ***********/             
/***************** category ******************/

        public function category($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->category($parent);
            $data['crumbs_category'] = $this->display->crumbs_category($this->mysql->get_row('category',array('id_category'=>$parent)));
            
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            $data['level'] = 1;
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            $data['category'] = $this->admin_model->get_sub_items('category',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(4));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('category',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/category');
               else  redirect('admin/category/'.$parent);
            } 
            
            if($parent>0)
            {
                $data['scheme'] = $this->mysql->get_All('scheme',array('id_category'=>$parent),'','','ord','asc');
                $data['level'] = 2;
            } 
            
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');     
            $this->display->backend('catalog/category',$data);
	}

        public function category_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->category();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_category');
                $data['action'] = '/admin/edit_category';
                $data['page'] = $this->mysql->get_row('category',array('id_category'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');
            }
            else
            {
                $data['title_page'] = lang('add_category');
                $data['action'] = '/admin/add_category';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');
            }

            $this->display->backend('catalog/category_form',$data);
        }

        public function edit_category()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->category();
             $category = $this->input->post('page');
             $this->admin_model->edit_category($category,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_category($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             redirect('admin/category/'.$this->input->post('parent'));
        }

        public function add_category()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->category();
                $this->admin_model->add_category($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('category','id_category');
               $erorr_image = $this->upload_photo_category($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/category/'.$this->input->post('parent'));
        }

        public function del_category($parent = 0)
        {
               $this->admin_model->del_category($this->input->post('selected'));
               redirect('admin/category/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_category($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/category/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
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
				
				if($this->_create_thumbnail('/'.$new_name,700,1000)) {unlink($new_name);}
			 
                if($fisier=='image')
				{
				$this->mysql->update('category',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));
				}
                else $this->mysql->update('category',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('category',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));                
			
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end category ***********/       
        
/***************** news ******************/

        public function news($parent = 0,$id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->news($parent);
            $data['crumbs_news'] = $this->display->crumbs_news($this->mysql->get_row('news',array('id_news'=>$parent)));
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
                $data['title_page'] = lang('edit_news');
                $data['action'] = '/admin/edit_news';
                $data['page'] = $this->mysql->get_row('news',array('id_news'=>$id));
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
               $last_id = $this->mysql->get_max('news','id_news');
               $erorr_image = $this->upload_photo_news($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

               $this->send_news($last_id);
                
                //$this->output->enable_profiler(TRUE);
                redirect('admin/news/'.$this->input->post('parent'));
        }

        public function del_news($parent = 0)
        {
               $this->admin_model->del_news($this->input->post('selected'));
               redirect('admin/news/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
        
        public function send_news($id)
        {
            $abonati = $this->mysql->get_All('users');
            foreach($abonati as $item)
            {
                $msg  ='A apărut o noutate nouă. </br>';
                $msg  .=" Pentru a accesa faceţi click <a href='".base_url()."/news_detail/$id'>aici</a> </br>";
               $this->send_mail($item['mail'],'Va saluta Levir',$msg);
            }
        }
        
   public function resize_image($path,$width,$height,$mode='',$id = '')
   {
       // echo phpinfo();
        // $file_id = path
        $src = $_SERVER["DOCUMENT_ROOT"].$path;       
        $explode= explode('.',$path);
        $nr_explode = count($explode);
        $ext = $explode[$nr_explode-1];
        $file_name    = $id.'_'.$width.'_'.$height;  
        if($ext=='gif')
        {   
            $ext = 'jpg';  
            $time = microtime();
            $converted_filename = $_SERVER["DOCUMENT_ROOT"].'/tmp/pdf/'.$time.'.'.$ext;
            $new_pic = imagecreatefromgif($src);
            // Create a new true color image with the same size
            $w = imagesx($new_pic);
            $h = imagesy($new_pic);
            $white = imagecreatetruecolor($w, $h);
            // Fill the new image with white background
            $bg = imagecolorallocate($white, 255, 255, 255);
            imagefill($white, 0, 0, $bg);
            // Copy original transparent image onto the new image
            imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);
            $new_pic = $white;
            imagejpeg($new_pic, $converted_filename);
            imagedestroy($new_pic);
            $src = $converted_filename;
            $file_name = $time;
        }  
          
            $new_image    = $_SERVER["DOCUMENT_ROOT"].'/uploads/proiecte/'.$file_name.'.'.$ext; 
            $new_image_db = '/uploads/proiecte/'.$file_name.'.'.$ext; 
            

        $this->load->library('image_lib');     
                switch ($mode) {
                                               case 'exact':

                                        //resize and crop

                                        $image_path = $src;
                        
                                      
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
                                        $config['image_library'] = 'gd2';
                                        $config['source_image'] = $image_path;
                                        $config['new_image'] = $new_image;
                                        $config['maintain_ratio'] = FALSE;
                                        $config['height'] = $new_height;
                                        $config['width'] = $new_width;
                                        $this->image_lib->initialize($config);
                                        $this->image_lib->resize();
                                        $this->image_lib->clear();

                                        //Crop if both width and height are not zero
                                        if (($width != 0) && ($height != 0)) {
                                                $x_axis = floor(($new_width - $width) / 2);
                                                $y_axis = floor(($new_height - $height) / 2);

                                                //Cropping
                                                $config = array();

                                                $config['source_image'] = $new_image;
                                                $config['maintain_ratio'] = FALSE;
                                                $config['new_image'] = $new_image;
                                                $config['width'] = $width;
                                                $config['height'] = $height;
                                                $config['x_axis'] = $x_axis;
                                                $config['y_axis'] = $y_axis;
                                                $this->image_lib->initialize($config);
                                                $this->image_lib->crop();
                                                $this->image_lib->clear();
                                            $array = array(
                                            'path' => $new_image_db,
                                            'name' =>  $file_name.'_'.$ext
                                            
                                        ); 
                                               
                                        }
                                             

                                break;

                                    case 'prop':
                                            $config = array(
                                                    'image_library'		=> 'GD2',
                                                    'source_image'		=> $src,
                                                    'new_image'			=> $new_image,
                                                    'maintain_ratio'	=> TRUE,
                                                    'width'				=> $width,
                                                    'height'			=> $height
                                                    );

                                                            $this->image_lib->initialize($config);
                                                            $this->image_lib->resize();
                                                            $this->image_lib->clear();
                            break;
                    }               
                    return $array;
   }

        function _create_thumbnail($fileName,$width,$height) 
        {
         $this->load->library('image_lib');
                 $config['image_library'] = 'gd2';
                 $config['source_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;       
                 $config['create_thumb'] = TRUE;
                 $config['maintain_ratio'] = TRUE;
                 $config['width'] = $width;
                 $config['height'] = $height;
                 $config['new_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;               
                 $this->image_lib->initialize($config);
                 if(!$this->image_lib->resize()) echo
                 $this->image_lib->display_errors();

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
             $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/news/';
             $dir2 = 'uploads/news/';
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

                if($fisier=='image')  $this->mysql->update('news',array('photo' =>'/'. $dir2 .$pid.$file_ext),array('id_news'=>$pid));
                else $this->mysql->update('news',array('photo' =>'/'. $dir .$pid.$file_ext),array('id_news'=>$pid));
                
                //thumb
                // $this->_create_thumbnail('/'.$new_name,85,85);  
               //  $this->mysql->update('news',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_news'=>$pid));
                
                
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
              
/***************** blog ******************/

        public function blog($parent = 0,$id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->blog($parent);
            $data['crumbs_blog'] = $this->display->crumbs_blog($this->mysql->get_row('blog',array('id_blog'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['blog'] = $this->mysql->get_All('blog',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('blog',$id,$_GET['ac']);
                redirect('admin/blog');
            }             
            $this->display->backend('catalog/blog',$data);
	}

        public function blog_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->blog();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_blog');
                $data['action'] = '/admin/edit_blog';
                $data['page'] = $this->mysql->get_row('blog',array('id_blog'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_blog');
                $data['action'] = '/admin/add_blog';
            }

            $this->display->backend('catalog/blog_form',$data);
        }

        public function edit_blog()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->blog();
             $blog = $this->input->post('page');
             $this->admin_model->edit_blog($blog,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_blog($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/blog/'.$this->input->post('parent'));
        }

        public function add_blog()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->blog();
                $this->admin_model->add_blog($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('blog','id_blog');
               $erorr_image = $this->upload_photo_blog($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/blog/'.$this->input->post('parent'));
        }

        public function del_blog($parent = 0)
        {
               $this->admin_model->del_blog($this->input->post('selected'));
               redirect('admin/blog/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       
        
       public function upload_photo_blog($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/blog/';
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

                if($fisier=='image')  $this->mysql->update('blog',array('photo' =>'/'.$new_name),array('id_blog'=>$pid));
                else $this->mysql->update('blog',array('photo' =>'/'.$new_name),array('id_blog'=>$pid));
                
                //thumb
                 $this->_create_thumbnail('/'.$new_name,85,85);  
                 $this->mysql->update('blog',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_blog'=>$pid));
                
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end blog ***********/
         
/***************** services ******************/

        public function services($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->services($parent);
            $data['crumbs_services'] = $this->display->crumbs_services($this->mysql->get_row('services',array('id_services'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['services'] = $this->mysql->get_All('services',array('parent'=>$parent),'','id_services','asc');
            $this->display->backend('catalog/services',$data);
	}

        public function services_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->services();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_services');
                $data['action'] = '/admin/edit_services';
                $data['page'] = $this->mysql->get_row('services',array('id_services'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_services',array('pid'=>$id)); 
            }
            else
            {
                $data['title_page'] = lang('add_services');
                $data['action'] = '/admin/add_services';
            }

            $this->display->backend('catalog/services_form',$data);
        }

        public function edit_services()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->services();
             $services = $this->input->post('page');
             $this->admin_model->edit_services($services,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_photo2'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_services($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             
             $erorr_image2 = $this->upload_photo_services($this->input->post('id'),'image2');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image.','.$erorr_image2);
             
             redirect('admin/services/'.$this->input->post('parent'));
        }

        public function add_services()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->services();
                $this->admin_model->add_services($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('services','id_services');
               $erorr_image = $this->upload_photo_services($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/services/'.$this->input->post('parent'));
        }

        public function del_services($parent = 0)
        {
               $this->admin_model->del_services($this->input->post('selected'));
               redirect('admin/services/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_services($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/services/';
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
                else  $new_name =  $dir .$pid.'_photo2'.$file_ext;
                //stregem foto daca exista si daca acela vechi
                
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}
                rename($dir . $file, $new_name);
                
                if($fisier=='image') $this->mysql->update('services',array('photo' =>'/'.$new_name),array('id_services'=>$pid));
                else $this->mysql->update('services',array('photo2' =>'/'.$new_name),array('id_services'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end services ***********/
             
/***************** partners ******************/

        public function partners($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->partners($parent);
            $data['crumbs_partners'] = $this->display->crumbs_partners($this->mysql->get_row('partners',array('id_partners'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['partners'] = $this->mysql->get_All('partners',array('parent'=>$parent),'','id_partners','asc');
            $this->display->backend('catalog/partners',$data);
	}

        public function partners_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->partners();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_partners');
                $data['action'] = '/admin/edit_partners';
                $data['page'] = $this->mysql->get_row('partners',array('id_partners'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_partners');
                $data['action'] = '/admin/add_partners';
            }

            $this->display->backend('catalog/partners_form',$data);
        }

        public function edit_partners()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->partners();
             $partners = $this->input->post('page');
             $this->admin_model->edit_partners($partners,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_partners($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/partners/'.$this->input->post('parent'));
        }

        public function add_partners()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->partners();
                $this->admin_model->add_partners($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('partners','id_partners');
               $erorr_image = $this->upload_photo_partners($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/partners/'.$this->input->post('parent'));
        }

        public function del_partners($parent = 0)
        {
               $this->admin_model->del_partners($this->input->post('selected'));
               redirect('admin/partners/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_partners($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/partners/';
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

                if($fisier=='image')  $this->mysql->update('partners',array('photo' =>'/'.$new_name),array('id_partners'=>$pid));
                else $this->mysql->update('partners',array('photo' =>'/'.$new_name),array('id_partners'=>$pid));
                
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end partners ***********/      
            
/***************** slider ******************/

        public function slider($parent = 0,$id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->slider($parent);
            $data['crumbs_slider'] = $this->display->crumbs_slider($this->mysql->get_row('slider',array('id_slider'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['slider'] = $this->mysql->get_All('slider',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('slider',$id,$_GET['ac']);
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
                $data['title_page'] = lang('edit_slider');
                $data['action'] = '/admin/edit_slider';
                $data['page'] = $this->mysql->get_row('slider',array('id_slider'=>$id));
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
             $this->admin_model->edit_slider($slider,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_photo2'));
             //incarcam imagrinea
             
             $erorr_image = $this->upload_photo_slider($this->input->post('id'),'image');
             
             $erorr_image = $this->upload_photo_slider($this->input->post('id'),'image2');
             
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/slider/'.$this->input->post('parent'));
        }

        public function add_slider()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->slider();
                $this->admin_model->add_slider($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('slider','id_slider');
               $erorr_image = $this->upload_photo_slider($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
               
               $erorr_image = $this->upload_photo_slider($this->input->post('id'),'image2');

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

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/slider/';
             $dir2 = 'uploads/slider/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png';
            
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

                if($fisier=='image')  $this->mysql->update('slider',array('photo' =>'/'.$dir2.$file),array('id_slider'=>$pid));
                else $this->mysql->update('slider',array('text_ro' =>'/'.$dir2.$file),array('id_slider'=>$pid));
                
                
                //thumb
                // $this->_create_thumbnail('/'.$new_name,250,123);  
               //  $this->mysql->update('slider',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_slider'=>$pid));                
                
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end slider ***********/ 
           
/***************** banners ******************/

        public function banners($parent = 0)
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
/***************** faq ******************/

        public function faq($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->faq($parent);
            $data['crumbs_faq'] = $this->display->crumbs_faq($this->mysql->get_row('faq',array('id_faq'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['faq'] = $this->mysql->get_All('faq',array('parent'=>$parent),'','id_faq','asc');
            $this->display->backend('catalog/faq',$data);
	}

        public function faq_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->faq();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_faq');
                $data['action'] = '/admin/edit_faq';
                $data['page'] = $this->mysql->get_row('faq',array('id_faq'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_faq');
                $data['action'] = '/admin/add_faq';
            }

            $this->display->backend('catalog/faq_form',$data);
        }

        public function edit_faq()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->faq();
             $faq = $this->input->post('page');
             $this->admin_model->edit_faq($faq,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_faq($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/faq/'.$this->input->post('parent'));
        }

        public function add_faq()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->faq();
                $this->admin_model->add_faq($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('faq','id_faq');
               $erorr_image = $this->upload_photo_faq($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/faq/'.$this->input->post('parent'));
        }

        public function del_faq($parent = 0)
        {
               $this->admin_model->del_faq($this->input->post('selected'));
               redirect('admin/faq/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_faq($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/faq/';
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

                if($fisier=='image')  $this->mysql->update('faq',array('photo' =>'/'.$new_name),array('id_faq'=>$pid));
                else $this->mysql->update('faq',array('photo' =>'/'.$new_name),array('id_faq'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end faq ***********/    
/***************** text ******************/

        public function text($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->text($parent);
            $data['crumbs_text'] = $this->display->crumbs_text($this->mysql->get_row('text',array('id_text'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['text'] = $this->mysql->get_All('text',array('parent'=>$parent),'','id_text','asc');
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
                $data['title_page'] = lang('edit_text');
                $data['action'] = '/admin/edit_text';
                $data['page'] = $this->mysql->get_row('text',array('id_text'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_text');
                $data['action'] = '/admin/add_text';
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
               $last_id = $this->mysql->get_max('text','id_text');
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

                if($fisier=='image')  $this->mysql->update('text',array('photo' =>'/'.$new_name),array('id_text'=>$pid));
                else $this->mysql->update('text',array('photo' =>'/'.$new_name),array('id_text'=>$pid));
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
         
/***************** catalog ******************/

        public function catalog($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->catalog($parent);
            $data['crumbs_catalog_category'] = $this->display->crumbs_catalog($this->mysql->get_row('catalog_category',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['catalog'] = $this->admin_model->get_sub_items('catalog',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(4));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('catalog',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/catalog');
               else  redirect('admin/catalog/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = 0;
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/catalog/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('catalog',array('category_id'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 4;
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
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_catalog');
                $data['action'] = '/admin/edit_catalog';
                $data['page'] = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_catalog');
                $data['action'] = '/admin/add_catalog';
                $data['nivele'] = 0;
            }
            
            $data['autor'] = $this->mysql->get_All('auth_user',array('id_auth_group' => 2));
            $data['editor'] = $this->mysql->get_All('auth_user',array('id_auth_group >'=>2,'id_auth_group <'=>5));
            $data['redactor'] = $this->mysql->get_All('auth_user',array('id_auth_group' => 5));
            

            $this->display->backend('catalog/catalog_form',$data);
        }

        public function edit_catalog()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->catalog();
             $catalog = $this->input->post('page');
             $this->admin_model->edit_catalog($catalog,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_pdf1'),$this->input->post('del_pdf2'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_catalog($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             $erorr_image1 = $this->upload_photo_catalog($this->input->post('id'),'pdf1');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_catalog($this->input->post('id'),'pdf2');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);
/*
             $erorr_image3 = $this->upload_photo_catalog($this->input->post('id'),'doc3_ro');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);

             
             $erorr_image1 = $this->upload_photo_catalog($this->input->post('id'),'doc1_en');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_catalog($this->input->post('id'),'doc2_en');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);

             $erorr_image3 = $this->upload_photo_catalog($this->input->post('id'),'doc3_en');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);
            */ 
             // $parent_cat = $this->mysql->get_row('catalog_category',array('id'=>$this->input->post('parent')));
             
             //$this->output->enable_profiler(TRUE);
             redirect('admin/catalog/'.$this->input->post('parent'));
        }

        public function add_catalog()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->catalog();
                $this->admin_model->add_catalog($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
                /*
               $last_id = $this->mysql->get_max('catalog','id_catalog');
               $erorr_image = $this->upload_photo_catalog($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
*/
               // $this->output->enable_profiler(TRUE);
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
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/catalog/';
             $dir = 'uploads/catalog/';
             $config['upload_path'] =  $dir2;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
             
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                switch ($fisier) {
                    case 'image':
                        $this->mysql->update('catalog',array('photo' =>'/'.$dir.$file),array('id_catalog'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('catalog',array($fisier =>'/'.$dir.$file),array('id_catalog'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('catalog',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_catalog'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end catalog ***********/    
 
         
/***************** raport ******************/
        
	public function get_raport()
	{           
           // $data['raport'] = $this->mysql->get_All('catalog');
             $data['raport'] = $this->mysql->get_raport();
           $this->load->view('excel',$data);
	}
        
	public function export()
	{           
          
           $this->export_to_excel($this->db->get('catalog'),'exceloutput');
	}        
        
function export_to_excel($query, $filename='exceloutput')
{
    $headers = ''; // just creating the var for field headers to append to below
    $data = ''; // just creating the var for field data to append to below

    $obj =& get_instance();

    $fields = $query->list_fields();

    if ($query->num_rows() == 0) {
        echo '<p>The table appears to have no data.</p>';
    } else {
        foreach ($fields as $field) {
           $headers .= $field . "\t";
        }

        foreach ($query->result() as $row) {
            $line = '';
            foreach($row as $value) {                                            
                if ((!isset($value)) OR ($value == "")) {
                    $value = "\t";
                } else {
                    $value = str_replace('"', '""', $value);
                    $value = '"' . $value . '"' . "\t";
                }
                $line .= $value;
            }
            $data .= trim($line)."\n";
        }

        $data = str_replace("\r","",$data);

        header("Content-type: application/x-msexcel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.xls");
        echo "$headers\n$data";
    }
}        
        public function raport($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->raport($parent);
            $data['crumbs_raport_category'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['raport'] = $this->mysql->get_raport($settings['per_page_admin'],$this->uri->segment(3));
            // $data['raport'] = $this->mysql->get_raport($settings['per_page_admin'],$this->uri->segment(3));
            // calculam nivele
            $data['nivele'] = 0;
            
          //paginare
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/raport/'.$parent;
           $config['total_rows']  = count($this->mysql->get_raport());
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
             
            
            $this->display->backend('catalog/raport',$data);
	}

        public function raport_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->raport();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_raport');
                $data['action'] = '/admin/edit_raport';
                $data['page'] = $this->mysql->get_row('raport',array('id_raport'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_raport');
                $data['action'] = '/admin/add_raport';
                $data['nivele'] = 0;
            }
            
            $data['autor'] = $this->mysql->get_All('auth_user',array('id_auth_group' => 2));
            $data['editor'] = $this->mysql->get_All('auth_user',array('id_auth_group >'=>2,'id_auth_group <'=>5));
            $data['redactor'] = $this->mysql->get_All('auth_user',array('id_auth_group' => 5));
            

            $this->display->backend('raport/raport_form',$data);
        }

        public function edit_raport()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->raport();
             $raport = $this->input->post('page');
             $this->admin_model->edit_raport($raport,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_raport($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             $erorr_image1 = $this->upload_photo_raport($this->input->post('id'),'doc1_ro');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_raport($this->input->post('id'),'doc2_ro');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);

             $erorr_image3 = $this->upload_photo_raport($this->input->post('id'),'doc3_ro');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);

             
             $erorr_image1 = $this->upload_photo_raport($this->input->post('id'),'doc1_en');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_raport($this->input->post('id'),'doc2_en');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);

             $erorr_image3 = $this->upload_photo_raport($this->input->post('id'),'doc3_en');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);
             
             // $parent_cat = $this->mysql->get_row('raport_category',array('id'=>$this->input->post('parent')));
             
             //$this->output->enable_profiler(TRUE);
             redirect('admin/raport/'.$this->input->post('parent'));
        }

        public function add_raport()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->raport();
                $this->admin_model->add_raport($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
                /*
               $last_id = $this->mysql->get_max('raport','id_raport');
               $erorr_image = $this->upload_photo_raport($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
*/
               // $this->output->enable_profiler(TRUE);
                redirect('admin/raport/'.$this->input->post('parent'));
        }

        public function del_raport($parent = 0)
        {
               $this->admin_model->del_raport($this->input->post('selected'));
               redirect('admin/raport/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
      public function upload_photo_raport($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/raport/';
             $dir = 'uploads/raport/';
             $config['upload_path'] =  $dir2;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
             
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                switch ($fisier) {
                    case 'image':
                        $this->mysql->update('raport',array('photo' =>'/'.$dir.$file),array('id_raport'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('raport',array($fisier =>'/'.$dir.$file),array('id_raport'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('raport',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_raport'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end raport ***********/    
                 
         
/***************** catalog_category ******************/

        public function catalog_category($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->catalog_category($parent);
            $data['crumbs_catalog_category'] = $this->display->crumbs_catalog_category($this->mysql->get_row('catalog_category',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['catalog_category'] = $this->admin_model->get_sub_items('catalog_category',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(4));
          
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog_category',array('id'=>$parent)),0,'catalog_category');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/catalog_category/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('catalog_category',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 4;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('catalog/catalog_category',$data);
	}

        public function catalog_category_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->catalog_category();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_catalog_category');
                $data['action'] = '/admin/edit_catalog_category';
                $data['page'] = $this->mysql->get_row('catalog_category',array('id'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog_category',array('id'=>$parent)),0,'catalog_category');
            }
            else
            {
                $data['title_page'] = lang('add_catalog_category');
                $data['action'] = '/admin/add_catalog_category';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('catalog_category',array('id'=>$parent)),0,'catalog_category');
            }

            $this->display->backend('catalog/catalog_category_form',$data);
        }

        public function edit_catalog_category()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->catalog_category();
             $catalog_category = $this->input->post('page');
             $this->admin_model->edit_catalog_category($catalog_category,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             
             $this->output->enable_profiler(TRUE);
             redirect('admin/catalog_category/'.$this->input->post('parent'));
        }

        public function add_catalog_category()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->catalog_category();
                $this->admin_model->add_catalog_category($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('catalog_category','id');
               $erorr_image = $this->upload_photo_catalog_category($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/catalog_category/'.$this->input->post('parent'));
        }

        public function del_catalog_category($parent = 0)
        {
               $this->admin_model->del_catalog_category($this->input->post('selected'));
               redirect('admin/catalog_category/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
      public function upload_photo_catalog_category($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/catalog_category/';
             $dir = 'uploads/catalog_category/';
             $config['upload_path'] =  $dir2;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
             
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                switch ($fisier) {
                    case 'image':
                        $this->mysql->update('catalog_category',array('photo' =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('catalog_category',array($fisier =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('catalog_category',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end catalog_category ***********/    

/***************** end catalog_category_category ***********/             
/***************** promotii ******************/

        public function promotii($type = 'promotii')
	{
            
            $table_promotii = 'catalog';
            
            $data['parent'] = 0;
            $data['type'] = $type;
            
            $parent = 0;
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->promotii(0);
            
            $data['crumbs_promotii'] = $this->display->crumbs_promotii($this->mysql->get_row($table_promotii,array($type=>1)),$table_promotii);
            
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            $data['uri'] = $this->uri->segment(3);
            
            
            $data['promotii'] = $this->mysql->get_All($table_promotii,array($type=>1),$settings['per_page_admin'],$this->uri->segment(3),'ord','asc');
           
         
            
            // calculam nivele
            $data['nivele'] =0;
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/promotii/'.$type;
           $config['total_rows']  = count($this->mysql->get_All($table_promotii,array($type=>1)));
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
             
            
            $this->display->backend('catalog/promotii',$data);
	}

        public function promotii_form($parent = 0,$id = null,$uri = null)
        {
            $erorrs = 0;
            $data['langs'] = $this->langs;
            $data['parent'] = 0;
            $data['breadcrumbs'] =  $this->breadcrumbs->promotii();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            $table_promotii = 'catalog';            
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_promotii');
                $data['action'] = '/admin/edit_promotii/'.$parent;
                $data['page'] = $this->mysql->get_row($table_promotii,array('id_'.$table_promotii=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_promotii');
                $data['action'] = '/admin/edit_promotii/'.$parent;
                $data['nivele'] = 0;
            }
            $this->display->backend('catalog/catalog_form',$data);
            
        }

        public function edit_promotii($parent)
        {
             $data['breadcrumbs'] = '';
             $promotii = $this->input->post('page');
             $this->admin_model->edit_catalog($promotii,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_promotii($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             $erorr_image1 = $this->upload_photo_promotii($this->input->post('id'),'doc1_ro');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_promotii($this->input->post('id'),'doc2_ro');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);

             $erorr_image3 = $this->upload_photo_promotii($this->input->post('id'),'doc3_ro');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);

             
             $erorr_image1 = $this->upload_photo_promotii($this->input->post('id'),'doc1_en');
             if($erorr_image1) $this->session->set_flashdata('error',$erorr_image1);

             $erorr_image2 = $this->upload_photo_promotii($this->input->post('id'),'doc2_en');
             if($erorr_image2) $this->session->set_flashdata('error',$erorr_image2);

             $erorr_image3 = $this->upload_photo_promotii($this->input->post('id'),'doc3_en');
             if($erorr_image3) $this->session->set_flashdata('error',$erorr_image3);
             
             //$this->output->enable_profiler(TRUE);
             redirect('/admin/promotii/'.$parent);
        }

        public function add_promotii()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->promotii();
                $this->admin_model->add_promotii($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('promotii','id_promotii');
               $erorr_image = $this->upload_photo_promotii($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/promotii/'.$this->input->post('parent'));
        }

        public function del_promotii($parent = 0)
        {
               $this->admin_model->del_promotii($this->input->post('selected'));
               redirect('admin/promotii/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
      public function upload_photo_promotii($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/promotii/';
             $dir = 'uploads/promotii/';
             $config['upload_path'] =  $dir2;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
             
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                
                switch ($fisier) {
                    case 'image':
                        $this->mysql->update('promotii',array('photo' =>'/'.$dir.$file),array('id_promotii'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('promotii',array($fisier =>'/'.$dir.$file),array('id_promotii'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('promotii',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_promotii'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end promotii ***********/    
         
/***************** portfolio ******************/

        public function portfolio($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->portfolio($parent);
            $data['crumbs_portfolio'] = $this->display->crumbs_portfolio($this->mysql->get_row('portfolio',array('id_portfolio'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
           
            // cu un nivel            
            $data['portfolio'] = $this->admin_model->get_sub_items('portfolio',$parent,'ord','asc');
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('portfolio',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/portfolio');
               else  redirect('admin/portfolio/'.$parent);
            }
            // end cu un nivel
            
           
            
            $this->display->backend('catalog/portfolio',$data);
	}

        public function portfolio_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->portfolio();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_portfolio');
                $data['action'] = '/admin/edit_portfolio';
                $data['page'] = $this->mysql->get_row('portfolio',array('id_portfolio'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_portfolio',array('pid'=>$id)); 
            }
            else
            {
                $data['title_page'] = lang('add_portfolio');
                $data['action'] = '/admin/add_portfolio';
            }

            $this->display->backend('catalog/portfolio_form',$data);
        }

        public function edit_portfolio()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->portfolio();
             $portfolio = $this->input->post('page');
             $this->admin_model->edit_portfolio($portfolio,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_portfolio($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             redirect('admin/portfolio/'.$this->input->post('parent'));
        }

        public function add_portfolio()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->portfolio();
                $this->admin_model->add_portfolio($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('portfolio','id_portfolio');
               $erorr_image = $this->upload_photo_portfolio($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/portfolio/'.$this->input->post('parent'));
        }

        public function del_portfolio($parent = 0)
        {
               $this->admin_model->del_portfolio($this->input->post('selected'));
               redirect('admin/portfolio/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_portfolio($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/portfolio/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
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

                if($fisier=='image')  $this->mysql->update('portfolio',array('photo' =>'/'.$new_name),array('id_portfolio'=>$pid));
                else $this->mysql->update('portfolio',array('photo' =>'/'.$new_name),array('id_portfolio'=>$pid));
                
               
                //thumb
                 $this->_create_thumbnail('/'.$new_name,110,97);  
                 $this->mysql->update('portfolio',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_portfolio'=>$pid));                
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end portfolio ***********/                
/***************** proiecte ******************/

        public function proiecte($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->proiecte($parent);
            $data['crumbs_proiecte'] = $this->display->crumbs_proiecte($this->mysql->get_row('proiecte',array('id_proiecte'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
           
            // cu un nivel            
            $data['proiecte'] = $this->admin_model->get_sub_items('proiecte',$parent,'ord','asc');
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('proiecte',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/proiecte');
               else  redirect('admin/proiecte/'.$parent);
            }
            // end cu un nivel
            
           
            
            $this->display->backend('catalog/proiecte',$data);
	}

        public function proiecte_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->proiecte();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_proiecte');
                $data['action'] = '/admin/edit_proiecte';
                $data['page'] = $this->mysql->get_row('proiecte',array('id_proiecte'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_proiecte',array('pid'=>$id)); 
            }
            else
            {
                $data['title_page'] = lang('add_proiecte');
                $data['action'] = '/admin/add_proiecte';
            }

            $this->display->backend('catalog/proiecte_form',$data);
        }

        public function edit_proiecte()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->proiecte();
             $proiecte = $this->input->post('page');
             $this->admin_model->edit_proiecte($proiecte,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_proiecte($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             redirect('admin/proiecte/'.$this->input->post('parent'));
        }

        public function add_proiecte()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->proiecte();
                $this->admin_model->add_proiecte($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('proiecte','id_proiecte');
               $erorr_image = $this->upload_photo_proiecte($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/proiecte/'.$this->input->post('parent'));
        }

        public function del_proiecte($parent = 0)
        {
               $this->admin_model->del_proiecte($this->input->post('selected'));
               redirect('admin/proiecte/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_proiecte($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/proiecte/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
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

                if($fisier=='image')  $this->mysql->update('proiecte',array('photo' =>'/'.$new_name),array('id_proiecte'=>$pid));
                else $this->mysql->update('proiecte',array('photo' =>'/'.$new_name),array('id_proiecte'=>$pid));
                
               
                //thumb
                //  $this->_create_thumbnail('/'.$new_name,210,210); 
                $array  = $this->resize_image('/'.$new_name,210,210,'exact',$pid);                  
                
                 $this->mysql->update('proiecte',array('thumb' =>$array['path']),array('id_proiecte'=>$pid));                
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end proiecte ***********/                
                  
  /***************** comments ******************/

        public function comments($parent = 0)
	{
            $data['breadcrumbs'] = $this->breadcrumbs->comments($parent);
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['comments'] = $this->mysql->get_comments();
            $this->display->backend('menu/comments',$data);
	}

        public function del_comments($parent = 0)
        {
               $this->admin_model->del_comments($this->input->post('selected'));
               redirect('admin/comments/'.$parent);
            //$this->output->enable_profiler(TRUE);
        }

      
        public function comments_form($id = null,$erorrs = null)
        { 
            $data['langs'] = $this->langs;
            $data['breadcrumbs'] =  $this->breadcrumbs->comments(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_comments');
                $data['action'] = '/admin/edit_comments';
                $data['var'] = $this->mysql->get_row('comments',array('id_comments'=>$id));                
            }
            else
            {
                $data['title_page'] = lang('add_comments');   
                $data['action'] = '/admin/add_comments';                
            }            
           
            $this->display->backend('menu/comments_form',$data);            
        }
        
        public function edit_comments()
        {            
          
                $data['breadcrumbs'] = $this->breadcrumbs->comments();  
                if($this->input->post('id'))
                        $this->mysql->update('comments',$this->input->post('msg'),array('id_comments'=>$this->input->post('id')));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/comments');
            
        }
        


/***************** end comments ***********/      
       
/***************** offerts ******************/

        public function offerts($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->offerts($parent);
            $data['crumbs_offerts'] = $this->display->crumbs_offerts($this->mysql->get_row('offerts',array('id_offerts'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['offerts'] = $this->mysql->get_All('offerts',array('parent'=>$parent),'','id_offerts','asc');
            $this->display->backend('catalog/offerts',$data);
	}

        public function offerts_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->offerts();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_offerts');
                $data['action'] = '/admin/edit_offerts';
                $data['page'] = $this->mysql->get_row('offerts',array('id_offerts'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_offerts');
                $data['action'] = '/admin/add_offerts';
            }

            $this->display->backend('catalog/offerts_form',$data);
        }

        public function edit_offerts()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->offerts();
             $offerts = $this->input->post('page');
             $this->admin_model->edit_offerts($offerts,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_offerts($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/offerts/'.$this->input->post('parent'));
        }

        public function add_offerts()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->offerts();
                $this->admin_model->add_offerts($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('offerts','id_offerts');
               $erorr_image = $this->upload_photo_offerts($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/offerts/'.$this->input->post('parent'));
        }

        public function del_offerts($parent = 0)
        {
               $this->admin_model->del_offerts($this->input->post('selected'));
               redirect('admin/offerts/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_offerts($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/offerts/';
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

                if($fisier=='image')  $this->mysql->update('offerts',array('photo' =>'/'.$new_name),array('id_offerts'=>$pid));
                else $this->mysql->update('offerts',array('photo' =>'/'.$new_name),array('id_offerts'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end offerts ***********/
        
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
                $data['title_page'] = lang('edit_pages');
                $data['action'] = '/admin/edit_pages';
                $data['page'] = $this->mysql->get_row('pages',array('id_pages'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_pages',array('pid'=>$id));   
            }
            else
            {
                $data['title_page'] = lang('add_pages');
                $data['action'] = '/admin/add_pages';
            }

            $this->display->backend('catalog/pages_form',$data);
        }
        
        public function get_last_photo()
        {
              $table = $this->input->post('table');
              $data['photo'] = $this->mysql->get_row($table,array('phid'=>$this->mysql->get_max($table,'phid')));   
              $this->load->view('backend/catalog/one_photo',$data);
            
        }
        public function del_photo()
        {
             //if($dif>14) unlink($path.$oldFile[$i]); 
             if($this->input->post('id')) 
             {   
                 $table = $this->input->post('table');
                 $photo =$this->mysql->get_row($table,array('phid'=>$this->input->post('id')));   
                 unlink($_SERVER['DOCUMENT_ROOT'].$photo['path']);
                 unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']);
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
        
/***************** doc_add ******************/
         
        public function down_doc($table,$id)
	{
               $this->load->helper('download'); 
               $doc = $this->mysql->get_row($table,array('id_'.$table=>$id));
               if($doc)
               {    
                     $data = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/admin/'.$doc['file']); // Read the file's contents
                     $name = $doc['name_ro'];    
                     force_download($name, $data,'',$doc['ext']);                   
               } 
        }
        
        public function doc_add($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());

            $data['breadcrumbs'] = $this->breadcrumbs->doc_add($parent);
            $data['crumbs_doc_add'] = $this->display->crumbs_doc_add($this->mysql->get_row('doc_add',array('id_doc_add'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
            if($this->session->userdata('group')==1) 
            {    
                $doc_all = $this->mysql->get_All('doc_add');                
                $data['doc_add']  = $this->mysql->get_All('doc_add','',$settings['per_page_admin'],$this->uri->segment(3),'ord','desc');
            } 
            elseif($this->session->userdata('group')==3 || $this->session->userdata('group')==4) 
            {    
                $doc_all = $this->mysql->get_All('doc_add',array('parent'=>$parent,'uid_attr'=>$this->session->userdata('uid')));
                $data['doc_add']  = $this->mysql->get_All('doc_add',array('parent'=>$parent,'uid_attr'=>$this->session->userdata('uid')),$settings['per_page_admin'],$this->uri->segment(3),'ord','desc');
            }             
            else 
            { 
                $doc_all = $this->mysql->get_All('doc_add',array('parent'=>$parent,'uid'=>$this->session->userdata('uid')));
                $data['doc_add']  = $this->mysql->get_All('doc_add',array('parent'=>$parent,'uid'=>$this->session->userdata('uid')),$settings['per_page_admin'],$this->uri->segment(3),'ord','desc');
            }
            
            // calculam nivele
            $data['nivele'] = 0;
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/doc_add/'.$parent;
           $config['total_rows']  = count($doc_all);
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
            
            
            $this->display->backend('catalog/doc_add',$data);
            // $this->output->enable_profiler(TRUE);
	}

        public function doc_add_form($parent = 0,$id = null,$id_page = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $erorrs = '';
            $data['breadcrumbs'] =  $this->breadcrumbs->doc_add();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            $data['users']  = $this->mysql->get_All('auth_user',array('id_auth_group >'=>2,'id_auth_group <'=>5));
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_doc_add');
                $data['action'] = '/admin/edit_doc_add/'.$id_page;
                $data['page'] = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
                $data['gallery'] = '';
            }
            else
            {
                $data['title_page'] = lang('add_doc_add');
                $data['action'] = '/admin/add_doc_add';
            }

            $this->display->backend('catalog/doc_add_form',$data);
        }
        
     
        public function edit_doc_add($id_page = null)
        {
             $data['breadcrumbs'] = $this->breadcrumbs->doc_add();
             $doc_add = $this->input->post('page');
             $this->admin_model->edit_doc_add($doc_add,$this->input->post('id'),$this->input->post('del_file'));
             
                //incarcam imagrinea
             $erorr_image = $this->upload_photo_doc_add($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

             $erorr_image = $this->upload_photo_doc_add($this->input->post('id'),'image2');
             
           //  $this->output->enable_profiler(TRUE);
             redirect('admin/doc_add/'.$this->input->post('parent').'/'.$id_page);
        }
      

        public function add_doc_add()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->doc_add();
                $this->admin_model->add_doc_add($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('doc_add','id_doc_add');
               $erorr_image = $this->upload_photo_doc_add($last_id,'image');
               if($erorr_image)
               {
                   $this->session->set_flashdata('error',$erorr_image);
                   $this->mysql->delete('doc_add',array('id_doc_add'=>$last_id));
               }

               // daca este bifat send news
               if($this->input->post('send')) $this->send_news($last_id);
                //$this->output->enable_profiler(TRUE);
                redirect('admin/doc_add/'.$this->input->post('parent'));
        }

        public function del_doc_add($parent = 0)
        {
               $this->admin_model->del_doc_add($this->input->post('selected'));
               redirect('admin/doc_add/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_doc_add($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/doc_add/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'pdf|doc_add|doc_addx|xls|xlsx|txt';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_name = $data['upload_data']['raw_name'];
                $file_ext = $data['upload_data']['file_ext'];
                $file_size = $data['upload_data']['file_size'];
                
                if($fisier=='image') $new_name =  $dir .(str_replace(' ','_',$file_name)).$file_ext;
                else  $new_name =  $dir .$pid.'_2'.$file_ext;
                //stregem foto daca exista si daca acela vechi
               // if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);

                $letter = '';
                
                if($fisier=='image') {
                    $this->mysql->update('doc_add',array('name_ro'=>$file_name,'size'=>$file_size,'ext'=>$file_ext,'file' =>'/'.$new_name),array('id_doc_add'=>$pid));
                  
                }
                else 
                    {
                    $this->mysql->update('doc_add',array('photo2' =>'/'.$new_name),array('id_doc_add'=>$pid));
                    
                }
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }
        
/***************** end doc_add ***********/

        
/***************** doc_edit ******************/
        
        public function doc_edit($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());

            $data['breadcrumbs'] = $this->breadcrumbs->doc_edit($parent);
            $data['crumbs_doc_edit'] = $this->display->crumbs_doc_edit($this->mysql->get_row('doc_edit',array('id_doc_edit'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            if($this->session->userdata('uid')==1) 
            {    
                $doc_all = $this->mysql->get_All('doc_edit');
                $data['doc_edit']  = $this->mysql->get_All('doc_edit',array('parent'=>$parent),$settings['per_page_admin'],$this->uri->segment(3),'ord','desc');
            }    
            else 
            { 
                $doc_all = $this->mysql->get_All('doc_edit',array('parent'=>$parent,'uid'=>$this->session->userdata('uid')));
                $data['doc_edit']  = $this->mysql->get_All('doc_edit',array('parent'=>$parent,'uid'=>$this->session->userdata('uid')),$settings['per_page_admin'],$this->uri->segment(3),'ord','desc');
            }
            
            // calculam nivele
            $data['nivele'] = 0;
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/doc_edit/'.$parent;
           $config['total_rows']  = count($doc_all);
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
            
            
            $this->display->backend('catalog/doc_edit',$data);
            // $this->output->enable_profiler(TRUE);
	}

        public function doc_edit_form($parent = 0,$id = null,$id_page = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $erorrs = '';
            $data['breadcrumbs'] =  $this->breadcrumbs->doc_edit();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_doc_edit');
                $data['action'] = '/admin/edit_doc_edit/'.$id_page;
                $data['page'] = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
                $data['gallery'] = '';
            }
            else
            {
                $data['title_page'] = lang('add_doc_edit');
                $data['action'] = '/admin/add_doc_edit';
            }

            $this->display->backend('catalog/doc_edit_form',$data);
        }
        
     
        public function edit_doc_edit($id_page = null)
        {
             $data['breadcrumbs'] = $this->breadcrumbs->doc_edit();
             $doc_edit = $this->input->post('page');
             $this->admin_model->edit_doc_edit($doc_edit,$this->input->post('id'),$this->input->post('del_file'));
             
                //incarcam imagrinea
             $erorr_image = $this->upload_photo_doc_edit($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

             $erorr_image = $this->upload_photo_doc_edit($this->input->post('id'),'image2');
             
           //  $this->output->enable_profiler(TRUE);
             redirect('admin/doc_edit/'.$this->input->post('parent').'/'.$id_page);
        }
      

        public function add_doc_edit()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->doc_edit();
                $this->admin_model->add_doc_edit($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('doc_edit','id_doc_edit');
               $erorr_image = $this->upload_photo_doc_edit($last_id,'image');
               if($erorr_image)
               {
                   $this->session->set_flashdata('error',$erorr_image);
                   $this->mysql->delete('doc_edit',array('id_doc_edit'=>$last_id));
               }

               // daca este bifat send news
               if($this->input->post('send')) $this->send_news($last_id);
                //$this->output->enable_profiler(TRUE);
                redirect('admin/doc_edit/'.$this->input->post('parent'));
        }

        public function del_doc_edit($parent = 0)
        {
               $this->admin_model->del_doc_edit($this->input->post('selected'));
               redirect('admin/doc_edit/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_doc_edit($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/doc_edit/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'pdf|doc_edit|doc_editx|xls|xlsx|txt';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_name = $data['upload_data']['raw_name'];
                $file_ext = $data['upload_data']['file_ext'];
                $file_size = $data['upload_data']['file_size'];
                
                if($fisier=='image') $new_name =  $dir .(str_replace(' ','_',$file_name)).$file_ext;
                else  $new_name =  $dir .$pid.'_2'.$file_ext;
                //stregem foto daca exista si daca acela vechi
               // if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);

                $letter = '';
                
                if($fisier=='image') {
                    $this->mysql->update('doc_edit',array('name_ro'=>$file_name,'size'=>$file_size,'ext'=>$file_ext,'file' =>'/'.$new_name),array('id_doc_edit'=>$pid));
                  
                }
                else 
                    {
                    $this->mysql->update('doc_edit',array('photo2' =>'/'.$new_name),array('id_doc_edit'=>$pid));
                    
                }
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }
        
/***************** end doc_edit ***********/

        
/***************** end doc_edit ***********/         
         
/***************** projects ******************/

        public function projects($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->projects($parent);
            $data['crumbs_projects'] = $this->display->crumbs_projects($this->mysql->get_row('projects',array('id_projects'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['projects'] = $this->mysql->get_All('projects',array('parent'=>$parent),'','id_projects','asc');
            $this->display->backend('catalog/projects',$data);
	}

        public function projects_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->projects();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_projects');
                $data['action'] = '/admin/edit_projects';
                $data['page'] = $this->mysql->get_row('projects',array('id_projects'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_projects');
                $data['action'] = '/admin/add_projects';
            }

            $this->display->backend('catalog/projects_form',$data);
        }

        public function edit_projects()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->projects();
             $projects = $this->input->post('page');
             $this->admin_model->edit_projects($projects,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_projects($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/projects/'.$this->input->post('parent'));
        }

        public function add_projects()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->projects();
                $this->admin_model->add_projects($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('projects','id_projects');
               $erorr_image = $this->upload_photo_projects($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/projects/'.$this->input->post('parent'));
        }

        public function del_projects($parent = 0)
        {
               $this->admin_model->del_projects($this->input->post('selected'));
               redirect('admin/projects/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_projects($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/projects/';
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

                if($fisier=='image')  $this->mysql->update('projects',array('photo' =>'/'.$new_name),array('id_projects'=>$pid));
                else $this->mysql->update('projects',array('photo' =>'/'.$new_name),array('id_projects'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end projects ***********/
/***************** rev ******************/

        public function rev($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->rev($parent);
            $data['crumbs_rev'] = $this->display->crumbs_rev($this->mysql->get_row('rev',array('id_rev'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['rev'] = $this->mysql->get_All('rev',array('parent'=>$parent),'','id_rev','asc');
            $this->display->backend('catalog/rev',$data);
	}

        public function rev_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->rev();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_rev');
                $data['action'] = '/admin/edit_rev';
                $data['page'] = $this->mysql->get_row('rev',array('id_rev'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_rev');
                $data['action'] = '/admin/add_rev';
            }

            $this->display->backend('catalog/rev_form',$data);
        }

        public function edit_rev()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->rev();
             $rev = $this->input->post('page');
             $this->admin_model->edit_rev($rev,$this->input->post('id'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_rev($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/rev/'.$this->input->post('parent'));
        }

        public function add_rev()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->rev();
                $this->admin_model->add_rev($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('rev','id_rev');
               $erorr_image = $this->upload_photo_rev($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/rev/'.$this->input->post('parent'));
        }

        public function del_rev($parent = 0)
        {
               $this->admin_model->del_rev($this->input->post('selected'));
               redirect('admin/rev/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_rev($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/rev/';
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

                if($fisier=='image')  $this->mysql->update('rev',array('photo' =>'/'.$new_name),array('id_rev'=>$pid));
                else $this->mysql->update('rev',array('photo' =>'/'.$new_name),array('id_rev'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end rev ***********/
/***************** sfaturi ******************/

        public function sfaturi($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->sfaturi($parent);
            $data['crumbs_sfaturi'] = $this->display->crumbs_sfaturi($this->mysql->get_row('sfaturi',array('id_sfaturi'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['sfaturi'] = $this->mysql->get_All('sfaturi',array('parent'=>$parent),'','id_sfaturi','asc');
            $this->display->backend('catalog/sfaturi',$data);
	}

        public function sfaturi_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->sfaturi();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_sfaturi');
                $data['action'] = '/admin/edit_sfaturi';
                $data['page'] = $this->mysql->get_row('sfaturi',array('id_sfaturi'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_sfaturi');
                $data['action'] = '/admin/add_sfaturi';
            }

            $this->display->backend('catalog/sfaturi_form',$data);
        }

        public function edit_sfaturi()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->sfaturi();
             $sfaturi = $this->input->post('page');
             $this->admin_model->edit_sfaturi($sfaturi,$this->input->post('id'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_sfaturi($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/sfaturi/'.$this->input->post('parent'));
        }

        public function add_sfaturi()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->sfaturi();
                $this->admin_model->add_sfaturi($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('sfaturi','id_sfaturi');
               $erorr_image = $this->upload_photo_sfaturi($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/sfaturi/'.$this->input->post('parent'));
        }

        public function del_sfaturi($parent = 0)
        {
               $this->admin_model->del_sfaturi($this->input->post('selected'));
               redirect('admin/sfaturi/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_sfaturi($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/sfaturi/';
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

                if($fisier=='image')  $this->mysql->update('sfaturi',array('photo' =>'/'.$new_name),array('id_sfaturi'=>$pid));
                else $this->mysql->update('sfaturi',array('photo' =>'/'.$new_name),array('id_sfaturi'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end sfaturi ***********/
         
/***************** command ******************/

        public function command($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->command($parent);
            $data['crumbs_command'] = $this->display->crumbs_command($this->mysql->get_row('command',array('id_command'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['command'] = $this->mysql->get_All('command',array('parent'=>$parent),'','','id_command','desc');
            $this->display->backend('catalog/command',$data);
	}

        public function command_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->command();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_command');
                $data['action'] = '/admin/edit_command';
                $data['page'] = $this->mysql->get_row('command',array('id_command'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_command');
                $data['action'] = '/admin/add_command';
            }

            $this->display->backend('catalog/command_form',$data);
        }

        public function edit_command()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->command();
             $command = $this->input->post('page');
             $this->admin_model->edit_command($command,$this->input->post('id'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_command($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/command/'.$this->input->post('parent'));
        }

        public function add_command()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->command();
                $this->admin_model->add_command($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('command','id_command');
               $erorr_image = $this->upload_photo_command($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/command/'.$this->input->post('parent'));
        }

        public function del_command($parent = 0)
        {
               $this->admin_model->del_command($this->input->post('selected'));
               redirect('admin/command/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_command($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/command/';
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

                if($fisier=='image')  $this->mysql->update('command',array('photo' =>'/'.$new_name),array('id_command'=>$pid));
                else $this->mysql->update('command',array('photo' =>'/'.$new_name),array('id_command'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end command ***********/         
         /***************** video ******************/

        public function video($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->video($parent);
            $data['crumbs_video'] = $this->display->crumbs_video($this->mysql->get_row('video',array('id_video'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['video'] = $this->mysql->get_All('video',array('parent'=>$parent),'','id_video','asc');
            $this->display->backend('catalog/video',$data);
	}

        public function video_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->video();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_video');
                $data['action'] = '/admin/edit_video';
                $data['page'] = $this->mysql->get_row('video',array('id_video'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_video');
                $data['action'] = '/admin/add_video';
            }

            $this->display->backend('catalog/video_form',$data);
        }

        public function edit_video()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->video();
             $video = $this->input->post('page');
             $this->admin_model->edit_video($video,$this->input->post('id'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_video($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             if($this->input->post('del_photo')=='on')
             {
                 $photo = $this->mysql->get_row('video',array('id_video'=>$this->input->post('id')));
                 
                 if(file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))
                 {
                    $this->mysql->update('video',array('photo'=>''),array('id_video'=>$this->input->post('id'))); 
                    unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
                 }        
             }
           // $this->output->enable_profiler(TRUE);
             redirect('admin/video/'.$this->input->post('parent'));
        }

        public function add_video()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->video();
                $this->admin_model->add_video($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('video','id_video');
               $erorr_image = $this->upload_photo_video($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/video/'.$this->input->post('parent'));
        }

        public function del_video($parent = 0)
        {
               $this->admin_model->del_video($this->input->post('selected'));
               redirect('admin/video/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_video($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/video/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = '*';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                $new_name =  $dir .$file;
                //stregem foto daca exista si daca acela vechi
               // if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                

                if($fisier=='image')  $this->mysql->update('video',array('photo' =>'/'.$new_name),array('id_video'=>$pid));
                else $this->mysql->update('video',array('photo' =>'/'.$new_name),array('id_video'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end video ***********/

/***************** price ******************/

        public function price($parent = 0)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->price($parent);
            $data['crumbs_price'] = $this->display->crumbs_price($this->mysql->get_row('price',array('id_price'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['price'] = $this->mysql->get_All('price',array('parent'=>$parent),'','','id_price','desc');
            $this->display->backend('catalog/price',$data);
	}

        public function price_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->price();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_price');
                $data['action'] = '/admin/edit_price';
                $data['page'] = $this->mysql->get_row('price',array('id_price'=>$id));
            }
            else
            {
                $data['title_page'] = lang('add_price');
                $data['action'] = '/admin/add_price';
            }

            $this->display->backend('catalog/price_form',$data);
        }

        public function edit_price()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->price();
             $price = $this->input->post('page');
             $this->admin_model->edit_price($price,$this->input->post('id'));
             
             // $this->output->enable_profiler(TRUE);
            redirect('admin/price/'.$this->input->post('parent'));
        }

        public function add_price()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->price();
                $this->admin_model->add_price($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('price','id_price');
               

                //$this->output->enable_profiler(TRUE);
                redirect('admin/price/'.$this->input->post('parent'));
        }

        public function del_price($parent = 0)
        {
               $this->admin_model->del_price($this->input->post('selected'));
               redirect('admin/price/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_price($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/price/';
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

                if($fisier=='image')  $this->mysql->update('price',array('photo' =>'/'.$new_name),array('id_price'=>$pid));
                else $this->mysql->update('price',array('photo' =>'/'.$new_name),array('id_price'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end price ***********/
                     
/***************** gallery ******************/

        public function gallery($parent = 0,$id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->gallery($parent);
            $data['crumbs_gallery'] = $this->display->crumbs_gallery($this->mysql->get_row('gallery',array('id_gallery'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['gallery'] = $this->mysql->get_All('gallery',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('gallery',$id,$_GET['ac']);
                redirect('admin/gallery');
            }             
            $this->display->backend('catalog/gallery',$data);
	}

        public function gallery_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->gallery();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_gallery');
                $data['action'] = '/admin/edit_gallery';
                $data['page'] = $this->mysql->get_row('gallery',array('id_gallery'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_gallery',array('pid'=>$id)); 	
            }
            else
            {
                $data['title_page'] = lang('add_gallery');
                $data['action'] = '/admin/add_gallery';
            }

            $this->display->backend('catalog/gallery_form',$data);
        }

        public function edit_gallery()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->gallery();
             $gallery = $this->input->post('page');
             $this->admin_model->edit_gallery($gallery,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_gallery($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
            //$this->output->enable_profiler(TRUE);
             redirect('admin/gallery/'.$this->input->post('parent'));
        }

        public function add_gallery()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->gallery();
                $this->admin_model->add_gallery($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('gallery','id_gallery');
               $erorr_image = $this->upload_photo_gallery($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/gallery/'.$this->input->post('parent'));
        }

        public function del_gallery($parent = 0)
        {
               $this->admin_model->del_gallery($this->input->post('selected'));
               redirect('admin/gallery/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_gallery($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/gallery/';
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

                if($fisier=='image')  $this->mysql->update('gallery',array('photo' =>'/'.$new_name),array('id_gallery'=>$pid));
                else $this->mysql->update('gallery',array('photo' =>'/'.$new_name),array('id_gallery'=>$pid));
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end gallery ***********/ 

}
?>
