<?php
class Product extends MY_AdminPanel {   

        public $uid;       
        public $langs;
	public function __construct() 
        {
            parent::__construct();
            $this->uid = $this->session->userdata('uid');               
            $this->lang->load('vars','romanian');
            $this->load->model('product_model');
            $this->db->set_dbprefix('app_');
            $this->langs = $this->mysql->get_All('langs');
        }        
        
	public function index()
	{
            $this->main();         
	}
                  
	public function main($reset=null)
	{           
           $data['breadcrumbs'] = $this->breadcrumbs->main_panel();
           $this->display->backend('main',$data);
	}
   
         
/***************** rubrica ******************/

        public function rubrica($id = null)
	{
            $parent = 0;
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->rubrica($parent);
            $data['crumbs_rubrica_category'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['rubrica'] = $this->product_model->get_sub_items_rubrica('rubrica','','ord','asc');
           
            if(isset($_GET['ac']))
            {
               $this->product_model->reorder('app_rubrica',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/rubrica');
               else  redirect('admin/rubrica/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = 0;
            
            $this->display->backend('product/rubrica',$data);
	}

        public function rubrica_form($id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = 0;
            $data['breadcrumbs'] =  $this->breadcrumbs->rubrica();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_rubrica');
                $data['action'] = '/admin/edit_rubrica';
                $data['page'] = $this->mysql->get_row('rubrica',array('id'=>$id));              
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_rubrica');
                $data['action'] = '/admin/add_rubrica';
                $data['nivele'] = 0;
            }
            

            $this->display->backend('product/rubrica_form',$data);
        }

        public function edit_rubrica()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->rubrica();
             $rubrica = $this->input->post('page');
             $this->product_model->edit_rubrica($rubrica,$this->input->post('id'),$this->input->post('del_photo'));

             
             redirect('admin/rubrica/'.$this->input->post('parent'));
        }

        public function add_rubrica()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->rubrica();
                $this->product_model->add_rubrica($this->input->post('page'),$this->input->post('parent'));
                redirect('admin/rubrica/'.$this->input->post('parent'));
        }

        public function del_rubrica($parent = 0)
        {
               $this->product_model->del_rubrica($this->input->post('selected'));
               redirect('admin/rubrica/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }

         

/***************** end rubrica ***********/    
/***************** categoria ******************/

        public function categoria($parent = 0,$id = null)
	{
         
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->categoria($parent);
            $data['crumbs_categoria'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;             
            $settings = $this->mysql->get_row('settings',array('id'=>1));
            $data['rubrica'] = $this->mysql->get_row('rubrica',array('id'=>$parent));  
            
            if(!$parent)  redirect('admin/rubrici');
            
            $data['categoria'] = $this->product_model->get_sub_items_categoria('categoria',$parent,'ord','asc');
           
            if(isset($_GET['ac']))
            {
               $this->product_model->reorder('app_categoria',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/categoria');
               else  redirect('admin/categoria/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = 0;
            
            $this->display->backend('product/categoria',$data);
	}

        public function categoria_form($parent , $id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = 0;
            $data['breadcrumbs'] =  $this->breadcrumbs->categoria(0);
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            $data['parent'] = $parent;
             
            $data['criteriu_cat'] = $this->mysql->get_All('criteriu_cat');              
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_categoria');
                $data['action'] = '/admin/edit_categoria';
                $data['page'] = $this->mysql->get_row('categoria',array('id'=>$id));                              
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_categoria');
                $data['action'] = '/admin/add_categoria';
                $data['nivele'] = 0;
            }
            $this->display->backend('product/categoria_form',$data);
        }

        public function edit_categoria()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->categoria();
             $categoria = $this->input->post('page');
             $this->product_model->edit_categoria($categoria,$this->input->post('id'),$this->input->post('del_photo'));
             redirect('admin/categoria/'.$this->input->post('parent'));
        }

        public function add_categoria()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->categoria();
                $this->product_model->add_categoria($this->input->post('page'),$this->input->post('parent'));
                redirect('admin/categoria/'.$this->input->post('parent'));
        }

        public function del_categoria($parent = 0)
        {
               $this->product_model->del_categoria($this->input->post('selected'));
               redirect('admin/categoria/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end categoria ***********/ 
          

/***************** categoria_detaliu ******************/

        public function categoria_detaliu($parent = 0,$parent_parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());

            $data['breadcrumbs'] = $this->breadcrumbs->categoria_detaliu($parent);
            $data['crumbs_categoria_detaliu'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            $data['parent_parent'] = $parent_parent; 
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            
          //  if(!$parent) redirect('/admin/rubrici');
            
            $data['categoria'] = $this->mysql->get_row('categoria',array('id'=>$parent));  
            
            $data['detaliu'] = $this->mysql->get_row('categoria_detaliu',array('id'=>$parent_parent));  
            
            $data['rubrica'] = $this->mysql->get_row('rubrica',array('id'=>$data['categoria']['rubrica_id']));                         
            
            $data['categoria_detaliu'] = $this->product_model->get_sub_items_detaliu('categoria_detaliu',$parent,$parent_parent,'ord','asc');
           
            if(isset($_GET['ac']))
            {
               $this->product_model->reorder('categoria_detaliu',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/categoria_detaliu');
               else  redirect('admin/categoria_detaliu/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('categoria_detaliu',array('id'=>$parent)),0,'categoria_detaliu');
            
            
            $this->display->backend('product/categoria_detaliu',$data);
            // $this->output->enable_profiler(TRUE);
	}

        public function categoria_detaliu_form($parent = 0,$parent_parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['parent_parent'] = $parent_parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->categoria_detaliu();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_categoria_detaliu');
                $data['action'] = '/admin/edit_categoria_detaliu';
                $data['page'] = $this->mysql->get_row('categoria_detaliu',array('id'=>$id));
                
            }
            else
            {
                $data['title_page'] = lang('add_categoria_detaliu');
                $data['action'] = '/admin/add_categoria_detaliu';
            }

            $this->display->backend('product/categoria_detaliu_form',$data);
        }

        public function edit_categoria_detaliu()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->categoria_detaliu();
             $categoria_detaliu = $this->input->post('page');
             $this->product_model->edit_categoria_detaliu($categoria_detaliu,$this->input->post('id'));
             if($this->input->post('parent_parent')) redirect('admin/categoria_detaliu/'.$this->input->post('parent').'/'.$this->input->post('parent_parent'));
             else  redirect('admin/categoria_detaliu/'.$this->input->post('parent'));
        }


        public function add_categoria_detaliu()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->categoria_detaliu();
                $this->product_model->add_categoria_detaliu($this->input->post('page'),$this->input->post('parent'),$this->input->post('parent_parent'));
                
                 if($this->input->post('parent_parent')) redirect('admin/categoria_detaliu/'.$this->input->post('parent').'/'.$this->input->post('parent_parent'));
                else  redirect('admin/categoria_detaliu/'.$this->input->post('parent'));
                redirect('admin/categoria_detaliu/'.$this->input->post('parent'));
        }

        public function del_categoria_detaliu($parent = 0,$parent_parent = 0)
        {
               $this->product_model->del_categoria_detaliu($this->input->post('selected'));
               redirect('admin/categoria_detaliu/'.$parent.'/'.$parent_parent);

            //$this->output->enable_profiler(TRUE);
        }

        
/***************** end categoria_detaliu ***********/
                   
/***************** criteriu ******************/
        
        public function add_crit_val()
        {
             $id = $this->input->post('id');
             $data['id'] = $this->mysql->insert('criteriu_val',array('criteriu_id'=>$id));
             $this->load->view('product/add_crit_val',$data);
        }
        
        public function del_crit_val()
        {
             $id = $this->input->post('id');
             if($id) $this->mysql->delete('criteriu_val',array('id'=>$id));
             
        }        
        public function criteriu($parent = 0,$id = null)
	{
         
        /*
        $table = 'rubrica';
        $table = 'criteriu';
        $rubrici = $this->mysql->get_All($table);        
        foreach ($rubrici as $item) 
        {
            $this->mysql->update($table,array('ord'=>$item['id']),array('id'=>$item['id']));
        }
        */
        
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->criteriu($parent);
            $data['crumbs_criteriu'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;             
            $settings = $this->mysql->get_row('settings',array('id'=>1));
            
            $data['criteriu_cat'] = $this->mysql->get_row('criteriu_cat',array('id'=>$parent));  
            
            
            if(!$parent)  redirect('admin/rubrici');
            
            $data['criteriu'] = $this->product_model->get_sub_items('criteriu',$parent,'ord','asc');
           
            if(isset($_GET['ac']))
            {
               $this->product_model->reorder_child('app_criteriu',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/criteriu');
               else  redirect('admin/criteriu/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = 0;
            
            $this->display->backend('product/criteriu',$data);
	}

        public function criteriu_form($parent , $id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = 0;
            $data['breadcrumbs'] =  $this->breadcrumbs->criteriu($parent);
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

             $data['parent'] = $parent;
             
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_criteriu');
                $data['action'] = '/admin/edit_criteriu';
                $data['page'] = $this->mysql->get_row('criteriu',array('id'=>$id));    
                $data['criterii_val_list'] = $this->mysql->get_All('criteriu_val',array('criteriu_id'=>$id)); 
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_criteriu');
                $data['action'] = '/admin/add_criteriu';
                $data['nivele'] = 0;
            }
            $this->display->backend('product/criteriu_form',$data);
        }

        public function edit_criteriu($redirect = null)
        {
             $data['breadcrumbs'] = $this->breadcrumbs->criteriu();
             $criteriu = $this->input->post('page');
             
             $criteriu_val = $this->input->post('page_val');
             if($criteriu_val) $this->product_model->edit_criteriu_val($criteriu_val,$this->input->post('id'));
             
             
             
             $this->product_model->edit_criteriu($criteriu,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_criteriu($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             if($redirect) redirect('admin/criteriu_form/'.$this->input->post('parent').'/'.$this->input->post('id'));
             else  redirect('admin/criteriu/'.$this->input->post('parent'));
             
             $this->output->enable_profiler(TRUE);
        }

        public function add_criteriu()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->criteriu();
                $this->product_model->add_criteriu($this->input->post('page'),$this->input->post('parent'));
                redirect('admin/criteriu/'.$this->input->post('parent'));
        }

        public function del_criteriu($parent = 0)
        {
               $this->product_model->del_criteriu($this->input->post('selected'));
               redirect('admin/criteriu/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
      public function upload_photo_criteriu($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/criteriu/';
             $dir = 'uploads/criteriu/';
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
                        $this->mysql->update('criteriu',array('photo' =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('criteriu',array($fisier =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('criteriu',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end criteriu ***********/    
                  
/***************** criteriu_cat ******************/
      
        public function criteriu_cat($parent = 0,$id = null)
	{
         
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->criteriu_cat($parent);
            $data['crumbs_criteriu_cat'] = '';
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;         
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));
            $data['criteriu_cat'] = $this->product_model->get_sub_criteriu_cat('criteriu_cat',$parent,'ord','asc');
            $data['nivele'] = 1;
            
            $this->display->backend('product/criteriu_cat',$data);
	}

        public function criteriu_cat_form($parent , $id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = 0;
            $data['breadcrumbs'] =  $this->breadcrumbs->criteriu_cat($parent);
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

             $data['parent'] = $parent;
             
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_criteriu_cat');
                $data['action'] = '/admin/edit_criteriu_cat';
                $data['page'] = $this->mysql->get_row('criteriu_cat',array('id'=>$id));    
                $data['criterii_val_list'] = $this->mysql->get_All('criteriu_val',array('criteriu_id'=>$id)); 
                $data['nivele'] = 0;
            }
            else
            {
                $data['title_page'] = lang('add_criteriu_cat');
                $data['action'] = '/admin/add_criteriu_cat';
                $data['nivele'] = 0;
            }
            $this->display->backend('product/criteriu_cat_form',$data);
        }

        public function edit_criteriu_cat($redirect = null)
        {
             $data['breadcrumbs'] = $this->breadcrumbs->criteriu_cat();
             $criteriu_cat = $this->input->post('page');
             
             $criteriu_cat_val = $this->input->post('page_val');
             if($criteriu_cat_val) $this->product_model->edit_criteriu_cat_val($criteriu_cat_val,$this->input->post('id'));
             
             
             
             $this->product_model->edit_criteriu_cat($criteriu_cat,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_criteriu_cat($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             
             if($redirect) redirect('admin/criteriu_cat_form/'.$this->input->post('parent').'/'.$this->input->post('id'));
             else  redirect('admin/criteriu_cat/'.$this->input->post('parent'));
             
             $this->output->enable_profiler(TRUE);
        }

        public function add_criteriu_cat()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->criteriu_cat();
                $this->product_model->add_criteriu_cat($this->input->post('page'),$this->input->post('parent'));
                redirect('admin/criteriu_cat/'.$this->input->post('parent'));
        }

        public function del_criteriu_cat($parent = 0)
        {
               $this->product_model->del_criteriu_cat($this->input->post('selected'));
               redirect('admin/criteriu_cat/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
      public function upload_photo_criteriu_cat($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir2 = $_SERVER['DOCUMENT_ROOT'].'/uploads/criteriu_cat/';
             $dir = 'uploads/criteriu_cat/';
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
                        $this->mysql->update('criteriu_cat',array('photo' =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                    
                    default:
                        $this->mysql->update('criteriu_cat',array($fisier =>'/'.$dir.$file),array('id'=>$pid));
                        break;
                }
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('criteriu_cat',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id'=>$pid));                
			
               
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end criteriu_cat ***********/       

         
/***************** anunturi ******************/

        public function anunturi($parent = 0, $id = null)
	{
            $data['breadcrumbs'] = $this->breadcrumbs->anunturi($parent);
            $data['crumbs_anunturi'] = $this->display->crumbs_anunturi($this->mysql->get_row('anunt',array('id'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['anunturi'] = $this->mysql->get_All('anunt','',$settings['per_page_admin'],$this->uri->segment(3),'id','asc');
            $data['users'] = $this->mysql->get_All('user');
            $data['categorii'] = $this->mysql->get_All('categoria');
          
            // calculam nivele
            $data['nivele'] = 1;
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/anunturi/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('anunt'));
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
             
            
            $this->display->backend('product/anunturi',$data);
	}

        public function anunturi_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->anunturi();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_anunturi');
                $data['action'] = '/admin/edit_anunturi';
                $data['page'] = $this->mysql->get_row('anunturi',array('id'=>$id));               
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('anunturi',array('id'=>$parent)),0,'anunturi');
            }
            else
            {
                $data['title_page'] = lang('add_anunturi');
                $data['action'] = '/admin/add_anunturi';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('anunturi',array('id'=>$parent)),0,'anunturi');
            }

            $this->display->backend('catalog/anunturi_form',$data);
        }

        public function edit_anunturi()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->anunturi();
             $anunturi = $this->input->post('page');
             $this->product_model->edit_anunturi($anunturi,$this->input->post('id'),$this->input->post('del_photo'),$this->input->post('del_doc_1'),$this->input->post('del_doc_2'),$this->input->post('del_doc_3'));
             //$this->output->enable_profiler(TRUE);
             redirect('admin/anunturi/'.$this->input->post('parent'));
        }

        public function add_anunturi()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->anunturi();
                $this->product_model->add_anunturi($this->input->post('page'),$this->input->post('parent'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/anunturi/'.$this->input->post('parent'));
        }

        public function del_anunturi($parent = 0)
        {
               $this->product_model->del_anunturi($this->input->post('selected'));
             //  redirect('admin/anunturi/'.$parent);
               redirect($_SERVER['HTTP_REFERER']);

            //$this->output->enable_profiler(TRUE);
        }


/***************** end anunturi ***********/    
         
         
         
}
?>
