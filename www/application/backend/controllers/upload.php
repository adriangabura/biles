<?php if(!defined('BASEPATH'))exit('No direct script access allowed');

class Upload extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		$this ->load->library('image_lib');                
	}

        function _create_thumbnail($fileName,$new_name,$width,$height,$type = null) 
        {
                $this->load->library('image_lib');
                 $config['image_library'] = 'gd2';
                 $config['source_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;                       
                 $config['maintain_ratio'] = TRUE;
                 $config['width'] = $width;
                 $config['height'] = $height;
                 $config['new_image'] = $_SERVER['DOCUMENT_ROOT'].$new_name;               
                 $this->image_lib->initialize($config);
                 $this->image_lib->resize(); 

       }

	
	public function do_upload() {
        $table =$this->input->post('table');
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$table.'/';
		$config['allowed_types'] = 'gif|jpg|png|jpe|jpeg|';
               
                //$config['file_name'] =$id_folder.$config['file_ext'];                
		$this -> load -> library('upload', $config);
		// Output json as response
		if(!$this -> upload -> do_upload()) {
                    
			$json['status'] = 'error';
			$json['issue'] = $this -> upload -> display_errors('', '');                       
                       // echo "blea";
		} else {
			$upload_arr = $this -> upload -> data();                        
			$json['status'] = 'success';
			foreach($this->upload->data() as $k => $v) {
				$json[$k] = $v;
			}
                        
                        $raw_name = $upload_arr['raw_name'];
                        $file_ext = $upload_arr['file_ext'];
                        
                         
                        
   						
                        $src_name = '/uploads/'.$table.'/'.$upload_arr['file_name'];                       
                        $thum_name = '/uploads/'.$table.'/'.$raw_name. '_thumb'.$file_ext;;
                        $this->_create_thumbnail($src_name,$thum_name,210,210); 
                        
							
                        $insert_id = $this->mysql->insert($table,array('pid' =>$this->input->post('pid')));  
                        $this->mysql->update($table,array('path' =>$src_name,'thumb' =>$thum_name),array('phid'=>$insert_id));  
                       
		}                  
                echo json_encode($json);                                
               

	}

	
}
