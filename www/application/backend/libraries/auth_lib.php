<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Auth_lib {

     public function do_login($login,$pwd)
     {
         $CI = & get_instance();
         $pwd= sha1($pwd);
		 $sql = "SELECT * FROM `app_auth_user` WHERE `password`=? and `username`=? ";
		 
         $query1 = $CI->db->query($sql,array($pwd,$login));       
		 
         if($query1->num_rows()>0)
         {
             $row = $query1->row();
             $sesiunea = array();
             $sesiunea['is_logged'] = TRUE;
             $sesiunea['user_name'] = $row->username;
             $sesiunea['uid'] = $row->id; 
             $sesiunea['is_staff'] = $row->is_staff;
             $sesiunea['is_active'] = $row->is_active;
             $sesiunea['is_superuser'] = $row->is_superuser;             
             $sesiunea['group'] = $row->id_auth_group; 
             
             $CI->session->set_userdata($sesiunea);             
             return TRUE;
         }        
         else
         {
             return FALSE;
         }
         
     }

     public function do_logout()
     {
         $CI =&get_instance();       
         $CI->session->sess_destroy();         
         redirect(base_url().'admin/login');
     }
     
     public function do_logout_main()
     {
         $CI =&get_instance();       
         $CI->session->sess_destroy();         
         redirect(base_url().'main');
     }
     public function if_is_logged()
     {
         $CI = &get_instance();
         if($CI->session->userdata('is_logged') == TRUE)
         {            
             return TRUE;
         }
         else
         {
             return FALSE;
         }
     }
     
     public function if_is_logged_main()
     {
         $CI = &get_instance();
         if($CI->session->userdata('is_logged') == TRUE)
         {            
             return TRUE;
         }
         else
         {
             return FALSE;
         }
     }

 }

?>
