<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends Base_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function documents()
    {
            $info = new stdClass();

            $config['upload_path']      = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('dir_upload_documents');
            $config['allowed_types']    = '*';
            $config['max_size']         = '30000';
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('attachments'))
            {
                $error = array('error' => $this->upload->display_errors());
                echo json_encode($error);

            }
            else
            {       
                $data = $this->upload->data();
                $data_insert = array(
                  'user_id'     => $this->_user_id,
                  'path'        => $data['file_name'],
                  'orig_name'   => $data['orig_name'],
                  'file_size'   => $data['file_size'],
                  'file_ext'    => $data['file_ext']
                );

                $last_id = $this->base_model->insert('document',$data_insert);

                //set the data for the json array   
                $info->name = $data['orig_name'];
                $info->type = $data['file_type'];
                $info->file_size = $data['file_size'];
                $info->id = $last_id;
                echo json_encode(array($info));
         }
    }
    
    public function audio()
    {
            $info = new stdClass();

            $config['upload_path']      = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('dir_upload_audio');
            $config['allowed_types']    = '*';
            $config['max_size']         = '30000';
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);
            
            
            if ( ! $this->upload->do_upload('attachments'))
            {
                $error = array('error' => $this->upload->display_errors());
                echo json_encode($error);

            }
            else
            {       
                $data = $this->upload->data();
                $data_insert = array(
                  'user_id'     => $this->input->post('id'),
                  'admin_id'    => $this->_user_id,
                  'path'        => $data['file_name'],
                  'orig_name'   => $data['orig_name'],
                  'file_size'   => $data['file_size'],
                  'file_ext'    => $data['file_ext']
                );

                $last_id = $this->base_model->insert('audio',$data_insert);

                //set the data for the json array   
                $info->name = $data['orig_name'];
                $info->type = $data['file_type'];
                $info->file_size = $data['file_size'];
                $info->id = $last_id;
                echo json_encode(array($info));
         }
    }
    
    public function cards()
    {
         $info = new stdClass();

            $config['upload_path']      = $_SERVER['DOCUMENT_ROOT'].'/'.$this->config->item('dir_upload_card');
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '30000';
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);
            
            
            if ( ! $this->upload->do_upload('attachments'))
            {
                $error = array('error' => $this->upload->display_errors());
                echo json_encode($error);

            }
            else
            {       
                $data = $this->upload->data();
                
                //create small variant of image
                $small_full_name = get_full_file_name($data['file_path'], $data['raw_name']."_thumb", $data['file_ext']);
              
                $res = resize_image_by_smallest_size_to_value_and_crop(
                $data['full_path'], $small_full_name,
                    275,
                    0, 275, 0, 205, 
                    array(
                        'quality' => 100,
                    )
                );
                
                $data_insert = array(
                    'path'        => $data['raw_name'].$data['file_ext'],
                    'thumb'       => $data['raw_name']."_thumb".$data['file_ext'],
                    'active'      => $this->input->post('active'),
                    'card_id'     => ($this->input->post('card')) ? $this->input->post('card') : 0
                );

                $last_id = $this->base_model->insert('card_photo',$data_insert);

                $info->thumb = $this->config->item('dir_upload_card').$data['raw_name']."_thumb".$data['file_ext'];
                $info->id = $last_id;
                echo json_encode(array($info));
         }
    }
}