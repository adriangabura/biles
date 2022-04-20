<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_AdminPanel extends CI_Controller
{    
       public $base_lang;
       
       function __construct()
       {  
            parent::__construct();   
            $this->base_lang = 'ru';
            // verificam daca este logat utilizatorul
            if(!$this->auth_lib->if_is_logged()) {redirect(base_url().'admin/login');}
            // if($this)    
             $this->db->set_dbprefix('app_');

       }               
       
       public function send_mail($to,$subject,$msg)
       {
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            
            $this->email->from('admin@servicemagic.md', 'We Team Service Magin');
            $this->email->to($to);
            //$this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');
            $this->email->subject($subject);
            $this->email->message($msg);
            $this->email->send();
       }
       
        public function logout()
        { 
            $this->auth_lib->if_is_logged();
            $this->auth_lib->do_logout();            
        }
        
        public function delete_row()
        {
            $this->auth_lib->if_is_logged();
            $id = $this->input->post('id');
            $table = $this->input->post('table');           
            if($this->mysql_model->delete($table,array('id_'.$table=>$id)))
               {
                   echo '1';
               }
            else { echo '0';}
            
        }       
 
}
