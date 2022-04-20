<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function do_login($username,$pwd)
    {
        $this->db->where('email',$username);
        $this->db->where('password',  md5($pwd));
        $this->db->where('active',1);
        $this->db->limit('1');
        $q = $this->db->get('user');
        if ($q->num_rows() == 1) {
            return $q->row_array();
        } else {
            return FALSE;
        }
    }
    
    public function activate_user($time=null,$id = null)
    {
		if(is_numeric($id)) 
        { 
          if($time) 
              
              $user = $this->db->where(array('id'=>$id,'active'=>$time))
                                ->get('user')
                                ->row_array();
        }
        
        if(isset($user) and $user) 		
		{
            $this->db->where(array('id'=>$user['id']));
            $this->db->update('user',array('active'=>1));
			
			return TRUE;
		} 
        else 
        {
            return FALSE;
        }
            
    }

 }   
?>
