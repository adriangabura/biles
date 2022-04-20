<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{    
       public $base_lang;
       
       public $_response = NULL;
       
       function __construct()
       {  
            parent::__construct();   
            $this->base_lang = 'ro';
            $this->db->set_dbprefix('app_');         
            $response = new Response();
        
            $this->_response = $response->getInstance();
       }               
    
       
       public function send_mail($to,$subject,$msg)
       {
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $this->email->initialize($config);
            
            $this->email->from('no-reply@fertilitatea.md', 'Fertilitatea');
            $this->email->to($to);
            //$this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');
            $this->email->subject($subject);
            $this->email->message($msg);
            $this->email->send();
       }       
       
       /**
    * отдает полученный из поста параметр, дополнительно обработанный в соответствии с тпом
    * @param $type string тип параметра или значений массива ,если массив. принимает значения number/boolean/string
    */
    protected function _safe_post($name,$type='string',$secure = false,$default_value = '')
    {
        if(!isset($_POST[$name]) && !isset($_GET[$name])) return $default_value;
        
        if(isset($_POST[$name]))
            $var = $this->input->post($name,$secure);
        else
            $var = $this->input->get($name,$secure);
        
        if(is_array($var)){
            switch($type){
                case 'double':
                    array_walk_recursive($var,function(&$item,$key){
                        $item = doubleval($item);
                    });
                break;
                case 'float':
                    array_walk_recursive($var,function(&$item,$key){
                        $item = floatval($item);
                    });
                break;
                case 'numeric':
                case 'int':
                case 'integer':
                case 'number':
                    array_walk_recursive($var,function(&$item,$key){
                        $item = (int)$item;    
                    });
                break;
                case 'boolean':
                    array_walk_recursive($var,function(&$item,$key){
                        $item = (bool)$item;    
                    });    
                break;
                case 'string':
                default:
                    array_walk_recursive($var,function(&$item,$key){
                        $item = strip_tags(trim($item));    
                    });
                break;    
            }
        }else{
            switch($type){
                case 'double':
                    $var = doubleval($var);
                break;
                case 'float':
                    $var = floatval($var);
                break;
                case 'boolean':
                    $var = (bool)$var;    
                break;
                case 'numeric':
                case 'int':
                case 'integer':
                case 'number':
                    $var = intval($var);
                break;
                case 'string':
                default:
                    $var = trim(strip_tags($var));
                break;    
            }
        }  
        return $var;  
    }
     
 
}
