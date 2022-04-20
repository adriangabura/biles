<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Sms extends MY_Controller
{   
    public function __construct()
    {
        parent::__construct();
       // $this->load->model('account_model');
    }
    
    public function index()
    {
        $this->sms();
    } 
    
    public function sms()
    {
        $data['erorr'] = $this->session->flashdata('erorr');
      
        $this->display->frontend('sms/sms',$data);
    } 
    public function termeni()
    {
        $data['erorr'] = $this->session->flashdata('erorr');
      
        $this->display->frontend('sms/termeni',$data);
    } 
    
     public function nieuws()
    {
        $data['erorr'] = $this->session->flashdata('erorr');
      
        $this->display->frontend('sms/nieuws',$data);
    } 
    
    public function sms_add($id = null)
    {
        //pr($_POST);
        if($this->input->post('ac')=='send_code')
        {
            if($this->input->post('cod'))
            {
                $cod = $this->mysql->get_row('cod_sms',array('cod'=>$this->input->post('cod')));
                if($cod)
                {                    
                   if($cod['status']==1) {$this->session->set_flashdata('erorr','Cod deja ulitizat !!!');redirect('/sms');}                  
                }
                else {  $this->session->set_flashdata('erorr','Cod invalid !!!');redirect('/sms');}
            }
            else
            {
                 $this->session->set_flashdata('erorr','Nu ai intordus Cod acces !!!');
                 redirect('/sms');
            }    
        } else {$this->session->set_flashdata('erorr','Nu ai intordus Cod acces !!!');redirect('/sms');}
        
        $data['cod_sms'] = $this->input->post('cod');         
        $data['action']     = '/sms/insert_anunt';
        $data['judet']      = $this->mysql->get_All('judet',array('parent'=>0));
        $data['moneda']     = $this->mysql->get_all('moneda');
        $data['time']       = time();
        $this->display->frontend('sms/sms_add',$data);
    } 
    
    
    public function insert_anunt()
    {
        $insert_id = $this->account->add_anunt($this->input->post());
        redirect('/sms/adaugat_cu_succes/'.$insert_id);
    }
    public function adaugat_cu_succes($id = null)
    {
        if(!$id) redirect('/sms');
        $data['anunt'] = $this->anunt->get_detaliu_anunt($id);
        if($data['anunt']) $this->display->frontend('sms/adaugat_cu_succes',$data);       
        else redirect('/sms/adaugat_cu_succes/'.$insert_id); 
    }    
    
    public function get_photo_add()
    {     
          if($this->input->post('anunt_id')) {$data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$this->input->post('anunt_id')),'','','ord','asc');}   
          else  { $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc'); }  
          if($data['photo'])
          {
              echo $this->load->view('/account/foto_anunt',$data,TRUE);
          }
    }  
    

    public function del_photo()
    {     
          $id         = $this->input->post('id');
          $anunt_id   = $this->input->post('anunt_id');
          $foto       = $this->mysql->get_row('photo_anunt',array('id'=>$id));
          if($foto)
          {   
              $this->mysql->delete('photo_anunt',array('id'=>$id));
              if($anunt_id) $data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$anunt_id),'','','ord','asc');   
              else  $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc');   
              echo $this->load->view('/account/foto_anunt',$data,TRUE);
          } 
    }    
        
    public function move_photo()
    {     
          $id        = $this->input->post('id');
          $type      = $this->input->post('type');
          $anunt_id  = $this->input->post('anunt_id');
          $foto      = $this->mysql->get_row('photo_anunt',array('id'=>$id));
          if($foto)
          {   
              if($anunt_id)
              {
                if($this->anunt->reorder_photo('photo_anunt',$id,$type,'anunt_id',$anunt_id))
                {        
                  $data['photo'] = $this->mysql->get_All('photo_anunt',array('anunt_id'=>$anunt_id),'','','ord','asc');                   
                  echo $this->load->view('/account/foto_anunt',$data,TRUE);
                }                  
              }
              else
              {    
                if($this->anunt->reorder_photo('photo_anunt',$id,$type,'time',$this->input->post('time')))
                {        
                  $data['photo'] = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')),'','','ord','asc');                   
                  echo $this->load->view('/account/foto_anunt',$data,TRUE);
                }
              }
          } 
    } 
    
    function _create_thumbnail($fileName) 
    {
     $this->load->library('image_lib');
             $config['image_library'] = 'gd2';
             $config['source_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;       
             $config['create_thumb'] = TRUE;
             $config['maintain_ratio'] = TRUE;
             $config['width'] = 175;
             $config['height'] = 135;
             $config['new_image'] = $_SERVER['DOCUMENT_ROOT'].$fileName;               
             $this->image_lib->initialize($config);
             if(!$this->image_lib->resize()) echo
             $this->image_lib->display_errors();

   }    
    
    public function do_upload() {
        
        $all = $this->mysql->get_All('photo_anunt',array('time'=>$this->input->post('time')));
        $nr = count($all);       
        if($nr < 10)
        {    
            $this -> load -> library('image_lib'); 
            $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/photo_anunt/';
            $config['allowed_types'] = 'gif|jpg|png|jpe|jpeg|gif*';              
            $this -> load -> library('upload', $config);
            // Output json as response
            if(!$this -> upload -> do_upload()) {
                    $json['status'] = 'error';
                    $json['issue'] = $this -> upload -> display_errors('', '');  
            } else {
                    $upload_arr = $this -> upload -> data();                        
                    $json['status'] = 'success';
                    foreach($this->upload->data() as $k => $v) {
                            $json[$k] = $v;
                    }
                    $new_name = '/uploads/photo_anunt/'.$upload_arr['file_name'];                    
                    $raw_name = $upload_arr['raw_name'];
                    $file_ext = $upload_arr['file_ext'];                    
                    
                    $timp = time();
                    $new_name = $config['upload_path'].$timp.$file_ext;
                    $file = $upload_arr['file_name'];                    
                    rename($config['upload_path'] . $file, $new_name); 
                    
                    if($this->input->post('anunt_id')!='undefined') {$insert_id = $this->mysql->insert('photo_anunt',array('path' =>'/uploads/photo_anunt/'.$timp.$file_ext,'anunt_id' =>$this->input->post('anunt_id')));}
                    else  {$insert_id = $this->mysql->insert('photo_anunt',array('path' =>'/uploads/photo_anunt/'.$timp.$file_ext,'time' =>$this->input->post('time')));}

                    $this->_create_thumbnail('/uploads/photo_anunt/'.$timp.$file_ext);                      
                    $thum_name = '/uploads/photo_anunt/'.$timp. '_thumb'.$file_ext;
                    
                    // ordinea
                     if($this->input->post('anunt_id')!='undefined')  { $max_ord = $this->mysql->get_max('photo_anunt','ord',array('anunt_id'=>$this->input->post('anunt_id')));}
                     else  { $max_ord = $this->mysql->get_max('photo_anunt','ord',array('time'=>$this->input->post('time')));}
                    
                    $this->mysql->update('photo_anunt',array('thumb' =>$thum_name,'ord'=>($max_ord+1)),array('id'=>$insert_id));  
            }                  
                                                     
	}
    }
    
    
     /*
     * Ajax Call
     * return json
     */
    public function ajax_get_categorii()
    {
        is_ajax();
        $id = $this->input->get('id','int',TRUE);
        $get_data = $this->mysql->get_row('rubrica',array('id'=>$id));
        
        if ( empty($get_data) )
            $this->_response->addError('Nu exista asa rublica');
        
        if ( $this->_response->valid() )
        {
            $categoria = $this->account->ajax_get_categorie($id);
            $this->_response->add($categoria,'categoria');
        }
        
       echo $this->_response;        
    }
   
    /*
     * Ajax Call
     * return json
     */
    public function ajax_get_model()
    {
        is_ajax();
        $id = $this->_safe_post('id','int',TRUE);
        $get_data = $this->mysql->get_row('auto',array('id'=>$id));
        
        if ( empty($get_data) )
            $this->_response->addError('Nu are Model');
        
        if ( $this->_response->valid() )
        {
            $model = $this->account->ajax_get_model($id);
            $this->_response->add($model,'model');
        }
        
       echo $this->_response;        
    }  
          
    /*
     * Ajax Call
     * return json
     */
    public function ajax_get_localitatea()
    {
        is_ajax();
        $id = $this->_safe_post('id','int',TRUE);
        $get_data = $this->mysql->get_row('judet',array('id'=>$id));
        
        if ( empty($get_data) )
            $this->_response->addError('Nu are Judet');
        
        if ( $this->_response->valid() )
        {
            $categoria = $this->account->ajax_get_localitatea($id);
            $this->_response->add($categoria,'categoria');
        }
        
       echo $this->_response;        
    }     
    /*
     * Ajax Call
     * return Html
     */
    public function ajax_get_criterii_anunt()
    {
        is_ajax();
        $html                 = array();
        $categoria_id         = $this->_safe_post('id','int',TRUE);
        $data['categoria_id'] = $categoria_id;
        $data['criterii']     = $this->account->get_criterii($categoria_id);
        $html['criterii']     = $this->load->view('account/get_criterii_anunt',$data , TRUE);   
        
        $data['categoria']    = $this->mysql->get_row('categoria',array('id' => $categoria_id));
        $data['optiuni']      = $this->mysql->get_All('categoria_detaliu',array('categoria_id' => $categoria_id));
        $html['optiuni']      = $this->load->view('account/get_optiuni_anunt',$data , TRUE);    
        echo json_encode($html);
    }    
     
 
    
    
}