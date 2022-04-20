        
/***************** doc_edit ******************/

        public function doc_edit($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());

            $data['breadcrumbs'] = $this->breadcrumbs->doc_edit($parent);
            $data['crumbs_doc_edit'] = $this->display->crumbs_doc_edit($this->mysql->get_row('doc_edit',array('id_doc_edit'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent;
            $data['doc_edit'] = $this->mysql->get_All('doc_edit',array('parent'=>$parent),'','','ord','asc');
            if(isset($_GET['ac']))
            {
                $this->admin_model->reorder('doc_edit',$id,$_GET['ac']);
                redirect('admin/doc_edit');
            }    
            $this->display->backend('catalog/doc_edit',$data);
            // $this->output->enable_profiler(TRUE);
	}

        public function doc_edit_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->doc_edit();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_doc_edit');
                $data['action'] = '/admin/edit_doc_edit';
                $data['page'] = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
                $data['gallery'] = '';
            }
            else
            {
                $data['title_page'] = lang('add_doc_edit');
                $data['action'] = '/admin/add_doc_edit';
            }

            $this->display->backend('catalog/doc_edit_form',$data);
        }
        
     
        public function edit_doc_edit()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->doc_edit();
             $doc_edit = $this->input->post('page');
             $this->admin_model->edit_doc_edit($doc_edit,$this->input->post('id'),$this->input->post('del_file'));
             
                //incarcam imagrinea
             $erorr_image = $this->upload_photo_doc_edit($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

             $erorr_image = $this->upload_photo_doc_edit($this->input->post('id'),'image2');
             
           //  $this->output->enable_profiler(TRUE);
             redirect('admin/doc_edit/'.$this->input->post('parent'));
        }
      

        public function add_doc_edit()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->doc_edit();
                $this->admin_model->add_doc_edit($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('doc_edit','id_doc_edit');
               $erorr_image = $this->upload_photo_doc_edit($last_id,'image');
               if($erorr_image)
               {
                   $this->session->set_flashdata('error',$erorr_image);
                   $this->mysql->delete('doc_edit',array('id_doc_edit'=>$last_id));
               }

               // daca este bifat send news
               if($this->input->post('send')) $this->send_news($last_id);
                //$this->output->enable_profiler(TRUE);
                redirect('admin/doc_edit/'.$this->input->post('parent'));
        }

        public function del_doc_edit($parent = 0)
        {
               $this->admin_model->del_doc_edit($this->input->post('selected'));
               redirect('admin/doc_edit/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_doc_edit($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/doc_edit/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'pdf|doc_edit|doc_editx|xls|xlsx|txt';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_name = $data['upload_data']['raw_name'];
                $file_ext = $data['upload_data']['file_ext'];
                $file_size = $data['upload_data']['file_size'];
                
                if($fisier=='image') $new_name =  $dir .(str_replace(' ','_',$file_name)).$file_ext;
                else  $new_name =  $dir .$pid.'_2'.$file_ext;
                //stregem foto daca exista si daca acela vechi
               // if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

               // rename($dir . $file, $new_name);

                $letter = '';
                
                if($fisier=='image') {
                    $this->mysql->update('doc_edit',array('name_ro'=>$file_name,'size'=>$file_size,'ext'=>$file_ext,'file' =>'/'.$new_name),array('id_doc_edit'=>$pid));
                  
                }
                else 
                    {
                    $this->mysql->update('doc_edit',array('photo2' =>'/'.$new_name),array('id_doc_edit'=>$pid));
                    
                }
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }
        
/***************** end doc_edit ***********/