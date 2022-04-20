<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 class Auth_lib {

     public function do_login($login,$pwd)
     {
         $CI = & get_instance();
         $pwd= sha1($pwd);
		 $sql = "SELECT * FROM `auth_user` WHERE `password`=? and `username`=? ";
		 
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
             
             //daca este operator TURE
             if($row->id_auth_group==2) $sesiunea['operator'] = TRUE;else $sesiunea['operator'] = FALSE;
             
             //produse din sessiune cos le punem in baza  
             
             if( $CI->session->userdata('product_select'))
             {
               //controlam daca este in baza cosul pentr utilizatorul logat in caz contrar introdu4em nou  
               $cart = $CI->mysql->get_row('cart_cart',array('uid_id'=>$row->id));
               if(empty($cart))  $cart_id = $CI->mysql->insert('cart_cart',array('uid_id'=>$row->id));else $cart_id = $cart['cid'];                
               $product_select = array_unique(json_decode($CI->session->userdata('product_select')));
               
               foreach($product_select as $pid)
               {
                   if(!$CI->mysql->get_row('cart_cart_products',array('cid_id'=>$cart_id,'pid_id'=>$pid)) and $pid) {$CI->mysql->insert('cart_cart_products',array('cid_id'=>$cart_id,'pid_id'=>$pid));}
               } 
               $CI->session->unset_userdata('product_select');               
             }              
             //end cart
                          
             if($row->is_staff)
             {    
              //adaugam permisiuni
              $CI =&get_instance();      
              $auth_group = $CI->mysql->get_row('auth_group',array('id_auth_group'=>$row->id_auth_group));
              $group = json_decode($auth_group['permission']);
              $form = array('category_form','products_form','orders_form','offerts_form','discount_form','users_form','users_group_form','langs_form','logout');
              if($group) $permission_final =array_merge($group, $form); else $permission_final =$form;
              $sesiunea['permissions'] =json_encode($permission_final); 
             } 
             
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
         if($CI->session->userdata('is_logged') == TRUE and $CI->session->userdata('is_staff') == 1 and $CI->session->userdata('is_active') == 1)
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
         if($CI->session->userdata('is_logged') == TRUE and $CI->session->userdata('is_active') == 1)
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
