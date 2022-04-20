<?php
include('admin.php');
class Systems extends Admin { 
        
	public function __construct() 
        {
           parent::__construct();
           
           $this->load->model('systems_model');
           $this->db->set_dbprefix('app_');
           
        }
/**************** langs ***************/        
	public function index()
	{           
           $this->langs();
	}
        
        public function change_lang($lng) {
            $this->mysql->update('langs',array('default'=>0));
            $this->mysql->update('langs',array('default'=>1),array('id_langs'=>$lng));
            redirect('admin/langs');  
        }
        public function langs()
	{           
            $data['breadcrumbs'] = $this->breadcrumbs->langs();
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            
            $data['langs'] = $this->langs;
            $this->display->backend('system/langs',$data);
            //$this->output->enable_profiler(TRUE);
	}
        
        public function langs_form($id = null,$erorrs = null)
        {  
            $data['breadcrumbs'] = $this->breadcrumbs->langs();            
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['heading_title'] = lang('edit_lang');
                $data['action'] = '/admin/edit_langs';
                $data['lang'] = $this->mysql->get_item('langs',$id);
                $data['status'] = $this->display->status_opt($data['lang']['status']);               
            }
            else
            {   
                $data['heading_title'] = lang('add_lang');   
                $data['status'] = $this->display->status_opt();
                $data['action'] = '/admin/add_langs';
            }
            $this->display->backend('system/langs_form',$data);                
            
        }
         
        private function valid_langs_form()
        {
            $error = '';
            //$this->session->set_userdata('errors_modify',lang('no_add_premission'));
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
            $this->form_validation->set_rules($this->systems_model->langs_rules());
            if($this->form_validation->run() == FALSE)
            {
               $error = true;
            }             
            return $error;
        }
         
        private function valid_del_langs()
        {
            $error = '';
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
           return $error;
        }
        
        public function add_langs()
        {              
            if($this->valid_langs_form())
            {              
               $this->langs_form('',$this->session->userdata('error_modify'));
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_ext = $this->mysql->get_row('langs',array('ext'=>$this->input->post('ext')));
                if($double_ext)
                {
                  $this->langs_form('',lang('ext_exist'));  
                }
                else
                {    
                 $data = array(
                    'name' => $this->input->post('name'),
                    'ext' => $this->input->post('ext'),
                    'status' => $this->input->post('status'),
                    'image' => $this->input->post('ext').'.png',
                    'uid' => $this->uid
                 );
                 if($this->systems_model->add_langs($data)) $this->session->set_flashdata('succes',lang('add_succes'));
                 else $this->session->set_flashdata('error',lang('add_error'));
                 redirect('admin/langs');  
                }
            }
            
        }        
        
        public function edit_langs()
        {
            if($this->valid_langs_form())
            {              
               $this->langs_form($this->input->post('id'),$this->session->userdata('error_modify'));
            }
            else
            {    
               $data['breadcrumbs'] = $this->breadcrumbs->langs();
               $double_ext = $this->mysql->get_row('langs',array('ext'=>$this->input->post('ext'),'id_langs !='=>$this->input->post('id')));
               if($double_ext)
               {
                  $this->langs_form($this->input->post('id'),lang('ext_exist'));  
               }
               else
               {
                   $data = array(
                        'name' => $this->input->post('name'),
                        'ext' => $this->input->post('ext'),
                        'status' => $this->input->post('status'),
                        'image' => $this->input->post('ext').'.png'
                   );
                   $this->systems_model->edit_langs($data,$this->input->post('id'));
                   redirect('admin/langs'); 
               }
            }
        }
        
        public function del_langs()
        {
            if($this->valid_del_langs())
            {              
               $this->langs('',$this->session->userdata('error_modify'));
            }
            else
            {            
               $this->systems_model->del_langs($this->input->post('selected'));                
            }
            //$this->output->enable_profiler(TRUE);
            redirect('admin/langs');
        }
        
/****************end langs ***************/        
/**************** users *******************/        
        public function users()
	{
            $data['breadcrumbs'] = $this->breadcrumbs->users();
            $data['errors'] = $this->session->flashdata('error');
            $data['users'] = $this->mysql->left_join('auth_user','auth_group','auth_user.id_auth_group=auth_group.id_auth_group');
            $data['users_group'] = $this->mysql->get_All('auth_group','','','id_auth_group','asc');                 
            $this->display->backend('system/users',$data);            
	}
        
        public function users_form($id = null,$erorrs = null,$change_pwd =null)
        { 
            if($erorrs==1) $erorrs = 0;
            $data['redir'] = 0;
            if($erorrs=='edit') 
            {
                $erorrs = 0;
                $data['redir'] = 1;
            }    
            $data['breadcrumbs'] =  $this->breadcrumbs->users(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_user');
                $data['action'] = '/admin/edit_users';
                $data['user'] = $this->mysql->get_row('auth_user',array('id'=>$id));
                if($change_pwd) $data['pwd'] = 'yes';else $data['pwd'] = 'no';                
            }
            else
            {
                $data['title_page'] = lang('add_user');   
                $data['action'] = '/admin/add_users';                  
            }            
            $data['users_group'] = $this->mysql->get_All('auth_group','','','id_auth_group','asc');    
            
           
            
            $this->display->backend('system/users_form',$data);            
        }
          
        private function valid_users_form($rules)
        {
            $error = '';
            //$this->session->set_userdata('errors_modify',lang('no_add_premission'));
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE)
            {
               $error = true;
            } 
                
            return $error;
        }
         
        private function valid_del_users()
        {
            $error = '';
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
           return $error;
        }        
       
        public function edit_users()
        {
            if($this->input->post('change_pwd')) {$change_pwd = 1;$rules = $this->systems_model->users_rules_pwd();}
            else {$rules = $this->systems_model->users_rules();$change_pwd = 0;}
            
            if($this->valid_users_form($rules))
            {              
               $this->users_form($this->input->post('id'),$this->session->userdata('error_modify'),$change_pwd);
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_username = $this->mysql->get_row('auth_user',array('username'=>$this->input->post('username'),'id !='=>$this->input->post('id')));
                $double_mail = $this->mysql->get_row('auth_user',array('email'=>$this->input->post('mail'),'id !='=>$this->input->post('id')));
                if($double_username)
                {
                  $this->users_form($this->input->post('id'),lang('username_exist'),$change_pwd);  
                }
                elseif($double_mail)
                {
                  $this->users_form($this->input->post('id'),lang('mail_exist'),$change_pwd);  
                }
                else
                {  
                    $is_staff = $this->input->post('is_staff');
                    $is_active = $this->input->post('is_active');
                    if($this->input->post('is_superuser')==1) 
                    {    
                       $id_auth_group = 1; 
                       $is_staff = 1;
                       $is_active = 1;
                    } else $id_auth_group =$this->input->post('id_auth_group');
                    $data = array(
                        'username'=>$this->input->post('username'),
                        'first_name'=>$this->input->post('fname'),
                        'last_name'=>$this->input->post('lname'),
                        'email'=>$this->input->post('mail'),                                                
                        'id_auth_group'=>$id_auth_group,
                    );
                    if($this->input->post('pwd')!='') $data['password'] = sha1($this->input->post('pwd'));
                            
                    $this->systems_model->edit_users($data,$this->input->post('id'));   
                    
                   // echo "uid=".$this->input->post('redir');
                    
                    if($this->input->post('redir')==1) redirect('admin/main'); 
                    elseif($this->session->userdata('uid')>1) redirect('admin/main'); 
                    else  redirect('admin/settings'); 
                    //$this->output->enable_profiler(TRUE);
                }
            }
        }
        
        public function add_users()
        {  
            if($this->valid_users_form($this->systems_model->users_rules_pwd()))
            {              
               $this->users_form('',$this->session->userdata('error_modify'));
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_username = $this->mysql->get_row('auth_user',array('username'=>$this->input->post('username')));
                $double_mail = $this->mysql->get_row('auth_user',array('email'=>$this->input->post('mail')));
                if($double_username)
                {
                  $this->users_form('',lang('username_exist'));  
                }
                elseif($double_mail)
                {
                  $this->users_form('',lang('mail_exist'));  
                }
                else
                {                     
                    $is_staff = $this->input->post('is_staff');
                    $is_active = $this->input->post('is_active');
                    if($this->input->post('is_superuser')==1) 
                    {    
                       $id_auth_group = 1; 
                       $is_staff = 1;
                       $is_active = 1;
                    } else $id_auth_group =$this->input->post('id_auth_group');
                    $data = array(
                        'username'=>$this->input->post('username'),
                        'first_name'=>$this->input->post('fname'),
                        'last_name'=>$this->input->post('lname'),
                        'email'=>$this->input->post('mail'),                         
                        'is_active'=>$is_active,
                        'is_staff'=>$is_staff,  
                        'is_superuser'=>$this->input->post('is_superuser'),
                        'id_auth_group'=>$id_auth_group,
                    );
                    $this->systems_model->add_users($data);                
                    redirect('admin/users');
                }
            }
        }
        
        public function del_users()
        {
            $perm ='';
            if($perm)
            {              
               
            }
            else
            {            
               $this->systems_model->del_users($this->input->post('selected'));   
               redirect('admin/users');
            }
            //$this->output->enable_profiler(TRUE);            
        }        
/**************** end users ***************/
/**************** users_site *******************/        
        public function users_site($parent = 0, $id = null)
	{
            $data['breadcrumbs'] = $this->breadcrumbs->users_site();
            $data['errors'] = $this->session->flashdata('error');
            $data['parent'] = $parent;             
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['users_site'] = $this->admin_model->get_sub_items('user',$parent,'ord','asc',$settings['per_page_admin'],$this->uri->segment(3));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('users_site',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/users_site');
               else  redirect('admin/users_site/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('user',array('id'=>$parent)),0,'auto');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/users_site/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('user',array('parent'=>$parent)));
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
            
            $this->display->backend('system/users_site',$data);            
	}
        
        public function users_site_form($id = null,$erorrs = null,$change_pwd =null)
        { 
            if($erorrs==1) $erorrs = 0;
            $data['redir'] = 0;
            if($erorrs=='edit') 
            {
                $erorrs = 0;
                $data['redir'] = 1;
            }    
            $data['breadcrumbs'] =  $this->breadcrumbs->users_site(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_user');
                $data['action'] = '/admin/edit_users_site';
                $data['user'] = $this->mysql->get_row('user',array('id'=>$id));
                if($change_pwd) $data['pwd'] = 'yes';else $data['pwd'] = 'no';                
            }
            else
            {
                $data['title_page'] = lang('add_user');   
                $data['action'] = '/admin/add_users_site';                  
            }            
            
            $this->display->backend('system/users_site_form',$data);            
        }
          
        private function valid_users_site_form($rules)
        {
            $error = '';
            //$this->session->set_userdata('errors_modify',lang('no_add_premission'));
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
            $this->form_validation->set_rules($rules);
            if($this->form_validation->run() == FALSE)
            {
               $error = true;
            } 
                
            return $error;
        }
         
        private function valid_del_users_site()
        {
            $error = '';
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
           return $error;
        }        
       
        public function edit_users_site()
        {
            if($this->input->post('change_pwd')) {$change_pwd = 1;$rules = $this->systems_model->users_site_rules_pwd();}
            else {$rules = $this->systems_model->users_site_rules();$change_pwd = 0;}
            
            if($this->valid_users_site_form($rules))
            {              
               $this->users_site_form($this->input->post('id'),$this->session->userdata('error_modify'),$change_pwd);
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_username = $this->mysql->get_row('user',array('username'=>$this->input->post('username'),'id !='=>$this->input->post('id')));
                $double_mail = $this->mysql->get_row('user',array('email'=>$this->input->post('mail'),'id !='=>$this->input->post('id')));
                if($double_username)
                {
                  $this->users_site_form($this->input->post('id'),lang('username_exist'),$change_pwd);  
                }
                elseif($double_mail)
                {
                  $this->users_site_form($this->input->post('id'),lang('mail_exist'),$change_pwd);  
                }
                else
                {  
                    $data = array(
                        'username'=>$this->input->post('username'),
                        'first_name'=>$this->input->post('fname'),
                        'last_name'=>$this->input->post('lname'),
                        'email'=>$this->input->post('mail'),                                                
                    );
                    if($this->input->post('pwd')!='') $data['password'] = sha1($this->input->post('pwd'));
                            
                    $this->systems_model->edit_users_site($data,$this->input->post('id'));   
                    
                   // echo "uid=".$this->input->post('redir');
                    
                    if($this->input->post('redir')==1) redirect('admin/main'); 
                    elseif($this->session->userdata('uid')>1) redirect('admin/main'); 
                    else  redirect('admin/users_site'); 
                    //$this->output->enable_profiler(TRUE);
                }
            }
        }
        
        public function add_users_site()
        {  
            if($this->valid_users_site_form($this->systems_model->users_rules_pwd()))
            {              
               $this->users_site_form('',$this->session->userdata('error_modify'));
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_username = $this->mysql->get_row('user',array('username'=>$this->input->post('username')));
                $double_mail = $this->mysql->get_row('user',array('email'=>$this->input->post('mail')));
                if($double_username)
                {
                  $this->users_site_form('',lang('username_exist'));  
                }
                elseif($double_mail)
                {
                  $this->users_site_form('',lang('mail_exist'));  
                }
                else
                {                     
                    $data = array(
                        'username'=>$this->input->post('username'),
                        'first_name'=>$this->input->post('fname'),
                        'last_name'=>$this->input->post('lname'),
                        'email'=>$this->input->post('mail')
                    );
                    $this->systems_model->add_users_site($data);                
                    redirect('admin/users_site');
                }
            }
        }
        
        public function del_users_site()
        {
            $perm ='';
            if($perm)
            {              
               
            }
            else
            {            
               $this->systems_model->del_users_site($this->input->post('selected'));   
               redirect('admin/users_site');
            }
            //$this->output->enable_profiler(TRUE);            
        }        
/**************** end users_site ***************/        
/*************** abonati **********/
        public function abonati()
	{
            $data['breadcrumbs'] = $this->breadcrumbs->abonati();
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['users'] = $this->mysql->get_All('users','','','id','asc');
            $this->display->backend('system/abonati',$data);
	}
        public function del_abonati()
        {
               $this->systems_model->del_abonati($this->input->post('selected'));
               redirect('admin/abonati');
            //$this->output->enable_profiler(TRUE);
        }

/*************** end abonati **********/
/**************** users_group *******************/        
        public function users_group()
	{
            $data['breadcrumbs'] = $this->breadcrumbs->users_group();
            $data['errors'] = $this->session->flashdata('error');
            $data['users_group'] = $this->mysql->get_All('auth_group','','','id_auth_group','asc');            
            $this->display->backend('system/users_group',$data);            
	}
        
        public function users_group_form($id = null,$erorrs = null)
        { 
            $data['breadcrumbs'] =  $this->breadcrumbs->users_group(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_users_group');
                $data['action'] = '/admin/edit_users_group';
                $data['user'] = $this->mysql->get_row('auth_group',array('id_auth_group'=>$id));               
            }
            else
            {
                $data['title_page'] = lang('add_users_group');   
                $data['action'] = '/admin/add_users_group';                  
            }            
            $data['permissions'] = $this->mysql->get_All('content_permissions');
            $this->display->backend('system/users_group_form',$data);            
        }
          
        private function valid_users_group_form($rules=null)
        {
            $error = '';
            //$this->session->set_userdata('errors_modify',lang('no_add_premission'));
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
            return $error;
        }
         
        private function valid_del_users_group()
        {
            $error = '';
            $this->session->unset_userdata('error_modify');
            /*
            if (!$this->user->hasPermission('modify', 'catalog/category')) {
                    $this->error['warning'] = $this->language->get('error_permission');
               $errors = lang('no_add_premission'); 
            }
            */            
           return $error;
        }        
       
        public function edit_users_group()
        {
            if($this->valid_users_group_form())
            {              
               $this->users_group_form($this->input->post('id'),$this->session->userdata('error_modify'),$change_pwd);
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->langs();
                $double_rname = $this->mysql->get_row('auth_group',array('name'=>$this->input->post('name'),'id_auth_group !='=>$this->input->post('id')));
                if($double_rname)
                {
                  $this->users_group_form($this->input->post('id'),lang('username_exist'),$change_pwd);  
                }
                else
                {  
                    $data = array(
                        'name'=>$this->input->post('name'),
                        'permission'=>json_encode($this->input->post('permission'))
                    );
                    $this->systems_model->edit_users_group($data,$this->input->post('id'));                
                    redirect('admin/users_group');
                }
            }
        }
        
        public function add_users_group()
        {  
            if($this->valid_users_group_form())
            {              
               $this->users_group_form('',$this->session->userdata('error_modify'));
            }
            else
            {
                $data['breadcrumbs'] = $this->breadcrumbs->users_group();
                $double_name = $this->mysql->get_row('auth_group',array('name'=>$this->input->post('name')));
                if($double_name)
                {
                  $this->users_group_form('',lang('users_group_exist'));  
                }
                else
                {  
                    $data = array(
                        'name'=>$this->input->post('name'),
                        'permission'=>json_encode($this->input->post('permission'))
                    );
                    $this->systems_model->add_users_group($data);                
                    redirect('admin/users_group');                  
                    //$this->output->enable_profiler(TRUE);      
                }
            }
        }
        
        public function del_users_group()
        {
            $perm ='';
            if($perm)
            {              
               
            }
            else
            {            
               $this->systems_model->del_users_group($this->input->post('selected'));   
               redirect('admin/users_group');
            }
            //$this->output->enable_profiler(TRUE);            
        }        
/**************** end users_group ***************/ 
/***************** vars ******************/        
        public function vars()
	{            
            $data['breadcrumbs'] = $this->breadcrumbs->vars();
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            
            $data['vars'] = $this->mysql->get_All('vars','','','','id_vars','desc');
            $this->display->backend('system/vars',$data);    
            
            $data['nivele'] = 0;
	}
        
        public function vars_form($id = null,$erorrs = null)
        { 
            $data['langs'] = $this->langs;
            $data['breadcrumbs'] =  $this->breadcrumbs->vars(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_vars');
                $data['action'] = '/admin/edit_vars';
                $data['var'] = $this->mysql->get_row('vars',array('id_vars'=>$id));                
            }
            else
            {
                $data['title_page'] = lang('add_vars');   
                $data['action'] = '/admin/add_vars';                
            }            
           
            $this->display->backend('system/vars_form',$data);            
        }
        
        public function edit_vars()
        {            
          
                $data['breadcrumbs'] = $this->breadcrumbs->vars();  
                $this->systems_model->edit_vars($this->input->post('var'),$this->input->post('id'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/vars');
            
        }
        
        public function add_vars()
        {   
                $data['breadcrumbs'] = $this->breadcrumbs->vars();
                $this->systems_model->add_vars($this->input->post('var'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/vars');            
        }
/***************** end vars ***********/    
        
        
        /***************** long vars ******************/        
        public function snippets()
	{            
            $data['breadcrumbs'] = $this->breadcrumbs->snippets();
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            
            $data['snippets'] = $this->mysql->get_All('snippets','','','','id_snippets','desc');
            $this->display->backend('system/snippets',$data);    
            
            $data['nivele'] = 0;
	}
        
        public function snippets_form($id = null,$erorrs = null)
        { 
            $data['langs'] = $this->langs;
            $data['breadcrumbs'] =  $this->breadcrumbs->snippets(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = "Edit Snippets";
                $data['action'] = '/admin/edit_snippets';
                $data['snippets'] = $this->mysql->get_row('snippets',array('id_snippets'=>$id));                
            }
            else
            {
                $data['title_page'] = lang('add_snippets');   
                $data['action'] = '/admin/add_snippets';                
            }            
           
            $this->display->backend('system/snippets_form',$data);            
        }
        
        public function edit_snippets()
        {            
          
                $data['breadcrumbs'] = $this->breadcrumbs->snippets();  
                $this->systems_model->edit_snippets($this->input->post('snippet'),$this->input->post('id'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/snippets');
            
        }
        
        public function add_snippets()
        {   
                $data['breadcrumbs'] = $this->breadcrumbs->snippets();
                $this->systems_model->add_snippets($this->input->post('snippet'));
                
                
                //$this->output->enable_profiler(TRUE);
                redirect('admin/snippets');            
        }
/***************** end snippets ***********/   
        
        
/***************** logs ******************/       

        public function logs() 
	{            
           $data['breadcrumbs'] = $this->breadcrumbs->logs();                    
           $data['errors'] = $this->session->flashdata('error');
           $data['succes'] = $this->session->flashdata('succes');
           $data['base_lang'] = $this->base_lang;   
           
           $settings = $this->mysql->get_row('settings',array('id'=>1));          
           
           if($this->uri->segment(3)) $uri = $this->uri->segment(3);
           else $uri = 0;
           
           $data['logs'] = $this->mysql->get_logs($uri,$settings['per_page_admin']);
           //paginare                   
           $this->load->library('pagination');
           $config['base_url'] = base_url().'/admin/logs/';
           $config['total_rows'] = $this->db->count_all_results('logs'); 
           $config['per_page'] = $settings['per_page_admin'];          
           $config['uri_segment'] = 3;
           $config['num_links'] = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
           
           $this->display->backend('system/logs',$data);            
	}
 
/***************** end logs ***********/                   
/***************** settings ******************/       

        public function settings() 
	{
            
           $data['breadcrumbs'] = $this->breadcrumbs->settings();                    
           $data['errors'] = $this->session->flashdata('error');
           $data['succes'] = $this->session->flashdata('succes');
           $data['base_lang'] = $this->base_lang;   
           $data['action'] = '/admin/edit_settings';

           $data['langs'] =  $this->mysql->get_All('app_langs');
     
           $data['settings'] = $this->mysql->get_row('app_settings',array('id'=>1));
           $this->display->backend('system/settings',$data);            
	}
        public function edit_settings()
        {            
          
           $data['breadcrumbs'] = $this->breadcrumbs->settings();  
           $this->systems_model->edit_settings($this->input->post('settings'),1);
           //$this->output->enable_profiler(TRUE);
           redirect('admin/settings');            
        }
 
/***************** end settings ***********/           
/***************** email_text ******************/        
        public function email_text()
	{            
            $data['breadcrumbs'] = $this->breadcrumbs->email_text();
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            
            $data['email_text'] = $this->mysql->get_All('email_text','','','','id','asc');
            $this->display->backend('system/email_text',$data);            
	}
        
        public function email_text_form($id = null,$erorrs = null)
        { 
            $data['langs'] = $this->langs;
            $data['breadcrumbs'] =  $this->breadcrumbs->email_text(); 
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';
            
            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_email_text'); 
                $data['action'] = '/admin/edit_email_text';
                $data['email_text'] = $this->mysql->get_row('email_text',array('id'=>$id));               
            }
            else
            {
                $data['title_page'] = lang('add_email_text');   
                $data['action'] = '/admin/add_email_text';                
            }           
           
            $this->display->backend('system/email_text_form',$data);            
        }
        
        public function edit_email_text()
        {  
                $data['breadcrumbs'] = $this->breadcrumbs->email_text();  
                $this->systems_model->edit_email_text($this->input->post('email_text'),$this->input->post('id'));
                //$this->output->enable_profiler(TRUE);
                redirect('admin/email_text');
        }
        
        public function add_email_text()
        {   
                $data['breadcrumbs'] = $this->breadcrumbs->email_text();
                $this->systems_model->add_email_text($this->input->post('email_text'));
               //$this->output->enable_profiler(TRUE);
                redirect('admin/email_text');            
        }
/***************** end email_text ***********/    
         
 /*************** orders **********/
        public function orders()
	{
            $data['breadcrumbs'] = '';
            $data['parent'] = 0;
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['orders'] = $this->mysql->orders();
            
            $this->display->backend('system/orders',$data);
	}
        public function del_orders()
        {
               $this->systems_model->del_orders($this->input->post('selected'));
               redirect('admin/orders');
            //$this->output->enable_profiler(TRUE);
        }

/*************** end orders **********/    
        
 /*************** messages **********/
        public function messages()
	{
            $data['breadcrumbs'] = $this->breadcrumbs->messages();
            $data['parent'] = 0;
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            
                  if(isset($_GET['type'])) $type = $_GET['type'];
                  else $type = 'contacte';            
            $data['messages'] = $this->mysql->get_All('catalog_comment','','','','id','desc');
            
            $this->display->backend('system/m_contacte',$data);
	}
        public function del_messages()
        {
               $this->systems_model->del_messages($this->input->post('selected'));
               redirect('admin/messages');
            //$this->output->enable_profiler(TRUE);
        }

/*************** end messages **********/        
        
        public function set_no_pay($parent = 0, $id = null)
	{
            
            $user = $this->mysql->get_row('user',array('id'=>$id));
            if($user)
            {
                $data['succes'] = $this->session->set_flashdata('succes','Actiune cu succes'); 
               if($user['no_pay'] ==0 ) $this->mysql->update('user',array('no_pay'=>1),array('id'=>$id));
               else  $this->mysql->update('user',array('no_pay'=>0),array('id'=>$id));
            }          
             redirect('/admin/users_site/'.$parent);
          // redirect($this->uri->uri_string());
        }                 

}
?>
