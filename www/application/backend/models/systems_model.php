<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systems_model extends CI_Model
{
    
    
        public function __construct() 
        {
            parent::__construct();           
            $this->db->set_dbprefix('');
        }              
  
      
/**************** users ***************/     
    
    public function users_rules_pwd ()
    {
        return $langs_rules = array
        (     
            array
            (
              'field' => 'username',
              'label' => lang('username'),
              'rules' => 'required'
            ),
            array
            (
              'field' => 'fname',
              'label' => lang('fname'),
              'rules' => 'alpha'
            ),
            array
            (
              'field' => 'is_active',
              'label' => lang('is_active'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'lname',
              'label' => lang('lname'),
              'rules' => 'alpha'  
            ),
            array
            (
              'field' => 'mail',
              'label' => lang('mail'),
              'rules' => 'required|valid_email'
            ),
            array
            (
              'field' => 'pwd',
              'label' => lang('pwd'),
              'rules' => 'required|min_length[5]'
            ),
            array
            (
              'field' => 'conf_pwd',
              'label' => lang('conf_pwd'),
              'rules' => 'required|matches[pwd]|min_length[5]'
            ),
            array
            (
              'field' => 'id_auth_group',
              'label' => lang('user_group'),
              'rules' => 'integer'
            ),            
            array
            (
              'field' => 'is_staff',
              'label' => lang('is_staff'),
              'rules' => 'is_staff'
            ),
            array
            (
              'field' => 'is_superuser',
              'label' => lang('is_superuser'),
              'rules' => 'integer'
            )
            
            
        );  
    }
    
     public function users_rules ()
    {
        return $langs_rules = array
        (     
            array
            (
              'field' => 'username',
              'label' => lang('username'),
              'rules' => 'required'
            ),
            array
            (
              'field' => 'fname',
              'label' => lang('fname'),
              'rules' => 'alpha'
            ),
            array
            (
              'field' => 'is_active',
              'label' => lang('is_active'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'is_staff',
              'label' => lang('is_staff'),
              'rules' => 'is_staff'
            ),
            array
            (
              'field' => 'is_superuser',
              'label' => lang('is_superuser'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'id_auth_group',
              'label' => lang('user_group'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'lname',
              'label' => lang('lname'),
              'rules' => 'alpha'  
            ),
            array
            (
              'field' => 'mail',
              'label' => lang('mail'),
              'rules' => 'required|valid_email'
            )
        );  
    }
   
    public function add_users($data)
    {
       if($this->mysql->insert('auth_user',$data)) $this->session->set_flashdata('succes',lang('add_succes'));   
       else $this->session->set_flashdata('error',lang('add_error'));         
    }

    public function del_users($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('auth_user',array('id'=>$id)))
             {
               $this->session->set_flashdata('succes',lang('del_succes'));
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }

    public function del_abonati($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('users',array('id'=>$id)))
             {
               $this->session->set_flashdata('succes',lang('del_succes'));
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
    
    public function edit_users($data,$id)
    {
        if($id) 
        {
            if($this->mysql->update('auth_user',$data,array('id'=>$id))) 
            {        
               $this->session->set_flashdata('succes',lang('edit_succes'));
              
            }        
            else $this->session->set_flashdata('error',lang('edit_error'));  
        }    
        else $this->session->set_flashdata('error',lang('id_error'));            
    }      
/***************** end users *******************/    
  public function add_users_site($data)
    {
       if($this->mysql->insert('user',$data)) $this->session->set_flashdata('succes',lang('add_succes'));   
       else $this->session->set_flashdata('error',lang('add_error'));         
    }

    public function del_users_site($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('user',array('id'=>$id)))
             {
               $this->session->set_flashdata('succes',lang('del_succes'));
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }

   
    
    public function edit_users_site($data,$id)
    {
        if($id) 
        {
            if($this->mysql->update('user',$data,array('id'=>$id))) 
            {        
               $this->session->set_flashdata('succes',lang('edit_succes'));
              
            }        
            else $this->session->set_flashdata('error',lang('edit_error'));  
        }    
        else $this->session->set_flashdata('error',lang('id_error'));            
    }      
/***************** end users_site *******************/        
/**************** users_group ***************/     
    
    public function users_group_rules_pwd ()
    {
        return $langs_rules = array
        (     
            array
            (
              'field' => 'username',
              'label' => lang('username'),
              'rules' => 'required'
            ),
            array
            (
              'field' => 'fname',
              'label' => lang('fname'),
              'rules' => 'alpha'
            ),
            array
            (
              'field' => 'status',
              'label' => lang('status'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'lname',
              'label' => lang('lname'),
              'rules' => 'alpha'  
            ),
            array
            (
              'field' => 'mail',
              'label' => lang('mail'),
              'rules' => 'required|valid_email'
            ),
            array
            (
              'field' => 'pwd',
              'label' => lang('pwd'),
              'rules' => 'required|min_length[5]'
            ),
            array
            (
              'field' => 'conf_pwd',
              'label' => lang('conf_pwd'),
              'rules' => 'required|matches[pwd]|min_length[5]'
            )
        );  
    }
    
     public function users_group_rules ()
    {
        return $langs_rules = array
        (     
            array
            (
              'field' => 'username',
              'label' => lang('username'),
              'rules' => 'required'
            ),
            array
            (
              'field' => 'fname',
              'label' => lang('fname'),
              'rules' => 'alpha'
            ),
            array
            (
              'field' => 'status',
              'label' => lang('status'),
              'rules' => 'integer'
            ),
            array
            (
              'field' => 'lname',
              'label' => lang('lname'),
              'rules' => 'alpha'  
            ),
            array
            (
              'field' => 'mail',
              'label' => lang('mail'),
              'rules' => 'required|valid_email'
            )
        );  
    }
   
    public function add_users_group($data)
    {
       if($this->mysql->insert('auth_group',$data)) $this->session->set_flashdata('succes',lang('add_succes'));   
       else $this->session->set_flashdata('error',lang('add_error'));         
    }
    
    public function del_users_group($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {
             if($this->mysql->delete('auth_group',array('id_auth_group'=>$id)))
             {
               $this->session->set_flashdata('succes',lang('del_succes'));         
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }
    
    public function edit_users_group($data,$id)
    {
        if($id) 
        {
            if($this->mysql->update('auth_group',$data,array('id_auth_group'=>$id))) $this->session->set_flashdata('succes',lang('edit_succes'));
            else $this->session->set_flashdata('error',lang('edit_error'));  
        }    
        else $this->session->set_flashdata('error',lang('id_error'));            
    }      
/***************** end users_group *******************/   
/************** vars ***********/ 
  
    public function add_vars($cat)
    {
        $cat_id = $this->mysql->insert('vars',array('uid'=>$this->session->userdata('uid')));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('vars',$item,array('id_vars'=>$cat_id)))                    
            $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
       
    }
    public function edit_vars($cat,$id)
    {       
        if($id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('vars',$item,array('id_vars'=>$id))) $this->session->set_flashdata('succes',lang('edit_succes'));             
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        //inregistram 4ine o facut ultima modificare
        $this->mysql->update('vars',array('uid'=>$this->session->userdata('uid')),array('id_vars'=>$id));
      
    }
    
/************** end vars ***********/   
    
    /************** snippets ***********/ 
  
    public function add_snippets($cat)
    {
        $cat_id = $this->mysql->insert('snippets',array('uid'=>$this->session->userdata('uid')));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('snippets',$item,array('id_snippets'=>$cat_id)))                    
            $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
       
    }
    public function edit_snippets($cat,$id)
    {       
        if($id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('snippets',$item,array('id_snippets'=>$id))) $this->session->set_flashdata('succes',lang('edit_succes'));             
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        //inregistram 4ine o facut ultima modificare
        $this->mysql->update('snippets',array('uid'=>$this->session->userdata('uid')),array('id_snippets'=>$id));
      
    }
    
/************** end snippets ***********/

 /************** settings ***********/ 

    public function edit_settings($cat,$id)
    {
          $i = 0;
         foreach($cat as $item)
         {   
            if($this->mysql->update('settings',$item,array('id'=>$id)))
            {         
               $this->session->set_flashdata('succes',lang('edit_succes'));             
               //notificam o singura data
              
               $i++;
            }   
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
    }
 
/************** end settings ***********/              
}
?>