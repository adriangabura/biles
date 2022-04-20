<?php
class Admin_auth extends CI_Controller
{    
        function __construct() {      
                parent::__construct();	
                $this->lang->load('vars','romanian'); 
        }

        function index()
        {        	
                if($this->auth_lib->if_is_logged()) { redirect('admin/pages');}
                else $this->load->view('login');

        }
      
	public function acces_denied()
	{
             $data['breadcrumbs'] = $this->breadcrumbs->acces_denied();
             $this->display->backend('erorr/permissions',$data);
            //$this->output->enable_profiler(TRUE);
	}
       
        
        public function login()
        {
            $this->form_validation->set_rules($this->auth_model->login_rules);
            if($this->form_validation->run() == FALSE)
            {
               $this->load->view('login');
            }
            else
            {
              $logged = $this->auth_lib->do_login($this->input->post('user_name',TRUE),$this->input->post('pwd',TRUE));
              if(!$logged)
              {
                $this->session->set_flashdata('eroare',lang('error_login'));
                redirect('admin/login');
              }
              else
              {             
                redirect('admin/main');
              }
            }         
            //$this->output->enable_profiler(TRUE);
        }   
}
?>