<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Auth_model extends CI_Model {

    /* reguli  logare */
   
    public $login_rules = array
    (
        array
        (
          'field' => 'user_name',
          'label' => 'Login',
          'rules' => 'trim|required|max_length[20]|alpha_numeric'
        ),
        array
        (
          'field' => 'pwd',
          'label' => 'Parola',
          'rules' => 'trim|required|max_length[20]|alpha_numeric'
        )
    );
    
    public $email_rules= array (
       array(
           'field' => 'email',
           'label' => 'E-mail',
           'rules' => 'trim|require|max_length[20]'
       )
    );
    public $add_user_rules = array
    (
        array
        (
            'field' => 'nume',
            'label' => 'Nume',
            'rules' => 'trim|require|max_length[20]'
        ),
        array
        (
            'field' => 'prenume',
            'label' => 'Prenume',
            'rules' => 'trim|require|max_length[20]'
        )
    );
 

    
}

?>
