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
        $this->db->where('pass',  md5($pwd));
        $this->db->where('active',1);
        $this->db->limit('1');
        $q = $this->db->get('users');
        if ($q->num_rows() == 1) {
            return $q->row_array();
        } else {
            return FALSE;
        }
    }

 }   
?>
