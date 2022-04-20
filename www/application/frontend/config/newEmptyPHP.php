         
/***************** category ******************/

        public function category($parent = 0, $id = null)
	{
            $this->session->set_flashdata('cale',$this->uri->uri_string());
            $data['breadcrumbs'] = $this->breadcrumbs->category($parent);
            $data['crumbs_category'] = $this->display->crumbs_category($this->mysql->get_row('category',array('id_category'=>$parent)));
            $data['errors'] = $this->session->flashdata('error');
            $data['succes'] = $this->session->flashdata('succes');
            $data['base_lang'] = $this->base_lang;
            $data['parent'] = $parent; 
            
            $settings = $this->mysql->get_row('settings',array('id'=>1));  
            $data['category'] = $this->admin_model->get_sub_items('category',$parent,'ord','desc',$settings['per_page_admin'],$this->uri->segment(4));
           
            if(isset($_GET['ac']))
            {
               $this->admin_model->reorder('category',$id,$_GET['ac']);            
               if($parent==0) redirect('admin/category');
               else  redirect('admin/category/'.$parent);
            } 
            
            // calculam nivele
            $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');
            
          //paginare   
          
           $this->load->library('pagination');
           $config['base_url']    = base_url().'/admin/category/'.$parent;
           $config['total_rows']  = count($this->mysql->get_All('category',array('parent'=>$parent)));
           $config['per_page']    = $settings['per_page_admin'];          
           $config['uri_segment'] = 4;
           $config['num_links']    = 8;
           $config['full_tag_open'] = '<div class="links">';
           $config['full_tag_close'] = '</div>';    
           $config['first_link'] = lang('first');
           $config['last_link'] = lang('last');
           $this->pagination->initialize($config); 
           $data['pagination']=$this->pagination->create_links();
           // end paginare
             
            
            $this->display->backend('category/category',$data);
	}

        public function category_form($parent = 0,$id = null,$erorrs = null)
        {
            $data['langs'] = $this->langs;
            $data['parent'] = $parent;
            $data['breadcrumbs'] =  $this->breadcrumbs->category();
            $data['errors'] = $erorrs.$this->session->flashdata('error');
            if($id)  $data['type'] =  'edit';
            else  $data['type'] = 'add';

            if($data['type'] == 'edit')
            {
                $data['title_page'] = lang('edit_category');
                $data['action'] = '/admin/edit_category';
                $data['page'] = $this->mysql->get_row('category',array('id_category'=>$id));
                $data['gallery'] = $this->mysql->get_All('photo_products',array('pid'=>$id));
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');
            }
            else
            {
                $data['title_page'] = lang('add_category');
                $data['action'] = '/admin/add_category';
                $data['nivele'] = $this->mysql->get_nivele($this->mysql->get_row('category',array('id_category'=>$parent)),0,'category');
            }

            $this->display->backend('category/category_form',$data);
        }

        public function edit_category()
        {
             $data['breadcrumbs'] = $this->breadcrumbs->category();
             $category = $this->input->post('page');
             $this->admin_model->edit_category($category,$this->input->post('id'),$this->input->post('del_photo'));
             //incarcam imagrinea
             $erorr_image = $this->upload_photo_category($this->input->post('id'),'image');
             if($erorr_image) $this->session->set_flashdata('error',$erorr_image);
             //$this->output->enable_profiler(TRUE);
             redirect('admin/category/'.$this->input->post('parent'));
        }

        public function add_category()
        {
                $data['breadcrumbs'] = $this->breadcrumbs->category();
                $this->admin_model->add_category($this->input->post('page'),$this->input->post('parent'));
               //incarcam imagrinea
               $last_id = $this->mysql->get_max('category','id_category');
               $erorr_image = $this->upload_photo_category($last_id,'image');
               if($erorr_image) $this->session->set_flashdata('error',$erorr_image);

                //$this->output->enable_profiler(TRUE);
                redirect('admin/category/'.$this->input->post('parent'));
        }

        public function del_category($parent = 0)
        {
               $this->admin_model->del_category($this->input->post('selected'));
               redirect('admin/category/'.$parent);

            //$this->output->enable_profiler(TRUE);
        }
       public function upload_photo_category($pid,$fisier)
        {
           $erorr_image = '';

           if($fisier=='image') $err_text = lang('photo_general').' :';
           else  $err_text = lang('photo_curent').' :';
           //upload photo
           $this->load->library('upload');
           // upload main photo
           if(!empty($_FILES[$fisier]['name']))
           {
             $dir = 'uploads/category/';
             $config['upload_path'] =$dir;
             $config['allowed_types'] = 'jpg|jpeg|png|pdf';
             $config['overwrite'] = true;
             $this->upload->initialize($config);

             if ($this->upload->do_upload($fisier))
             {
                $data = $this->upload->data();
                $data = array('upload_data' => $this->upload->data());
                $file = $data['upload_data']['file_name'];
                $file_ext = $data['upload_data']['file_ext'];
                if($fisier=='image') $new_name =  $dir .$pid.$file_ext;
                else  $new_name =  $dir .$pid.$file_ext;
                //stregem foto daca exista si daca acela vechi
                if (file_exists($new_name)) {unlink($new_name);}
                //if (file_exists($dir . $file)) {unlink($dir . $file);}

                rename($dir . $file, $new_name);
				
				if($this->_create_thumbnail('/'.$new_name,700,1000)) {unlink($new_name);}
			 
                if($fisier=='image')
				{
				$this->mysql->update('category',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));
				}
                else $this->mysql->update('category',array('photo' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));
                
               
                //thumb
             // $this->_create_thumbnail('/'.$new_name,700,1000); 
            //     $this->mysql->update('category',array('thumb' =>'/'.$dir .$pid.'_thumb'.$file_ext),array('id_category'=>$pid));                
			
                
             }
             else
             {
                $erorr_image = $this->upload->display_errors($err_text,'<br />');
             }
             return $erorr_image;

           }
           // end upload photo
         }

/***************** end category ***********/       
