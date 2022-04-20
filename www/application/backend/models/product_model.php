<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model
{
    
/************** categoria_detaliu ***********/ 
 
    public function add_categoria_detaliu($cat,$parent,$parent_parent)
    {
        $cat_id = $this->mysql->insert('categoria_detaliu',array('categoria_id'=>$parent,'parent'=>$parent_parent));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('categoria_detaliu',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));      
    }
    
    public function edit_categoria_detaliu($cat,$id)
    {
        
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('categoria_detaliu',$item,array('id'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('categoria_detaliu',array('id'=>$id));
        if($ord and $ord['ord']==0) $this->mysql->update('categoria_detaliu',array('ord'=>$id),array('id'=>$id));
   
    }
    
    public function del_categoria_detaliu($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {
             if($this->mysql->delete('categoria_detaliu',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->delete('categoria_detaliu',array('parent'=>$id));
               
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end categoria_detaliu ***********/          

/************** rubrica ***********/ 
 
    public function add_rubrica($cat,$parent)
    {
        $cat_id = $this->mysql->insert('rubrica',array('title_ro'=>'first'));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('rubrica',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr')); 
        
        // url
        $news = $this->mysql->get_row('rubrica',array('id'=>$cat_id));
        $url = url_title($news['title_ro'],'-',TRUE);
      //  $url_exists = $this->mysql->get_row('rubrica',array('url'=>$url,'id !='=>$cat_id));
      //  if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('rubrica',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }
   public function edit_rubrica($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        
        if($del_photo)
        {
            
            $photo = $this->mysql->get_row('rubrica',array('id'=>$id));
            if($photo['photo_ro'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']);
            $this->mysql->update('rubrica',array('photo_ro'=>''),array('id'=>$id));
        }         
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('rubrica',array('id'=>$id));
            if($photo['photo_en'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']);
            $this->mysql->update('rubrica',array('photo_en'=>''),array('id'=>$id));
        }   
        
        
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('rubrica',$item,array('id'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('rubrica',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('rubrica',array('ord'=>$id),array('id'=>$id));
        
        // url
        $news = $this->mysql->get_row('rubrica',array('id'=>$id));
        $url = url_title($news['title_ro'],'-',TRUE);
        $this->mysql->update('rubrica',array('url'=>$url,'ord'=>$id),array('id'=>$id));
        // end url           
        
       
    }
    
    public function del_rubrica($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {             
             
             if($this->mysql->delete('rubrica',array('id'=>$id)))
             {
             
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end rubrica ***********/     
/************** categoria ***********/ 
 
    public function add_categoria($cat,$parent)
    {
        $cat_id = $this->mysql->insert('categoria',array('rubrica_id'=>$parent));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('categoria',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr')); 
        
        // url
        $news = $this->mysql->get_row('categoria',array('id'=>$cat_id));
        $url = url_title($news['title_ro'],'-',TRUE);
        //$url_exists = $this->mysql->get_row('categoria',array('url'=>$url,'id !='=>$cat_id));
        //if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('categoria',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }
   public function edit_categoria($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        
        if($del_photo)
        {
            
            $photo = $this->mysql->get_row('categoria',array('id'=>$id));
            if($photo['photo_ro'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']);
            $this->mysql->update('categoria',array('photo_ro'=>''),array('id'=>$id));
        }         
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('categoria',array('id'=>$id));
            if($photo['photo_en'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']);
            $this->mysql->update('categoria',array('photo_en'=>''),array('id'=>$id));
        }   
        
        
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('categoria',$item,array('id'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('categoria',array('id'=>$id));
        if($ord and $ord['ord']==0) $this->mysql->update('categoria',array('ord'=>$id),array('id'=>$id));
        
        // url
        $cat_id = $id;
        $news = $this->mysql->get_row('categoria',array('id'=>$id));
        $url = url_title($news['title_ro'],'-',TRUE);
        //$url_exists = $this->mysql->get_row('categoria',array('url'=>$url,'id !='=>$cat_id));
        //if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('categoria',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url           
        
       
    }
    
    public function del_categoria($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {             
             
             if($this->mysql->delete('categoria',array('id'=>$id)))
             {
             
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end categoria ***********/    
/************** criteriu ***********/ 
 
    public function add_criteriu($cat,$parent)
    {
        $cat_id = $this->mysql->insert('criteriu',array('criteriu_cat_id'=>$parent));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('criteriu',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr')); 
        
        // url
        $news = $this->mysql->get_row('criteriu',array('id'=>$cat_id));
        $url = url_title($news['title_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('criteriu',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('criteriu',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }
    
   public function edit_criteriu_val($cat,$id)
   {        
        if($id)
        {  $i = 0;  
            foreach($cat as $k=>$v)
            {   
              
              $this->mysql->update('criteriu_val',$v,array('id'=>$k));
            }
            $this->session->set_flashdata('succes',lang('edit_succes'));          
        }
       
       
    }    
    
   public function edit_criteriu($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        
        if($del_photo)
        {
            
            $photo = $this->mysql->get_row('criteriu',array('id'=>$id));
            if($photo['photo_ro'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']);
            $this->mysql->update('criteriu',array('photo_ro'=>''),array('id'=>$id));
        }         
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('criteriu',array('id'=>$id));
            if($photo['photo_en'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']);
            $this->mysql->update('criteriu',array('photo_en'=>''),array('id'=>$id));
        }   
        
        
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('criteriu',$item,array('id'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('criteriu',array('id'=>$id));
        if($ord and $ord['ord']==0) $this->mysql->update('criteriu',array('ord'=>$id),array('id'=>$id));
        
       
    }
    
    public function del_criteriu($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {             
             
             if($this->mysql->delete('criteriu',array('id'=>$id)))
             {
             
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end criteriu ***********/         
/************** criteriu_cat ***********/ 
 
    public function add_criteriu_cat($cat,$parent)
    {
        $cat_id = $this->mysql->insert('criteriu_cat',array('title_ro'=>''));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('criteriu_cat',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
             
           $this->mysql->update('criteriu_cat',array('ord'=>$cat_id),array('id'=>$cat_id));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr')); 
          
    
    }
 
    
   public function edit_criteriu_cat($cat,$id,$del_photo = null,$del_photo2 = null)
    {
 
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('criteriu_cat',$item,array('id'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('criteriu_cat',array('id'=>$id));
        if($ord and $ord['ord']==0) $this->mysql->update('criteriu_cat',array('ord'=>$id),array('id'=>$id));
        
       
    }
    
    public function del_criteriu_cat($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {             
             
             if($this->mysql->delete('criteriu_cat',array('id'=>$id)))
             {
             
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end criteriu_cat ***********/    
/**************  anunturi ***********/      
    public function add_anunturi($cat,$parent)
    {
        $cat_id = $this->mysql->insert('anunturi',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
              
            if($this->mysql->update('anunturi',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_anunturi($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('anunturi',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('anunturi',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('anunturi',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_anunturi($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('anunt',array('id'=>$id)))
             {              
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
    
/******************** *************/
       
    public function reorder_child($table,$id,$ac,$asc_desc = 'asc')
    {
          $old_item=$this->mysql->get_row($table,array('id'=>$id));
          
          if($table == 'app_criteriu') $parent_row ='criteriu_cat_id';
          else  $parent_row ='parent';
          
          if($old_item['ord']==0) $this->mysql->update($table,array('ord'=>$id),array('id'=>$id)); 
          
          //asc desc 
          if($asc_desc=='desc')
          {    
              $ord_up = 'asc';  
              $ord_down = 'desc';
          }    
          else
          {    
              $ord_up = 'desc'; 
              $ord_down = 'asc';
          }
         //daca e precendet
            if($ac=='up')
            {   
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id'=>$id));
                $parent=$parent_item[$parent_row];

                $q= $this->db->query("SELECT `ord`,`id` FROM `$table` 
                        WHERE `ord`<'$old' and `$parent_row`='$parent' order by `ord` $ord_up limit 0,1;");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                 }
                } 
            }
           //daca e urmator
            if($ac=='down')
            {    
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id'=>$id));
                $parent=$parent_item[$parent_row];
                
                $q= $this->db->query("select `ord`,`id` from `$table` where `ord`>'$old' and `$parent_row`='$parent' order by `ord` $ord_down limit 0,1");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                 }
                }
                
            }   
            
        
    }        
    public function reorder($table,$id,$ac,$asc_desc = 'asc')
    {
          $old_item=$this->mysql->get_row($table,array('id'=>$id));
          if($old_item['ord']==0) $this->mysql->update($table,array('ord'=>$id),array('id'=>$id)); 
          
          //asc desc 
          if($asc_desc=='desc')
          {    
              $ord_up = 'asc';  
              $ord_down = 'desc';
          }    
          else
          {    
              $ord_up = 'desc'; 
              $ord_down = 'asc';
          }
         //daca e precendet
            if($ac=='up')
            {   
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id'=>$id));               

                $q= $this->db->query("SELECT `ord`,`id` FROM `$table` 
                        WHERE `ord`<'$old' order by `ord` $ord_up limit 0,1;");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                 }
                } 
            }
           //daca e urmator
            if($ac=='down')
            {    
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id'=>$id));
                
                $q= $this->db->query("select `ord`,`id` from `$table` where `ord`>'$old' order by `ord` $ord_down limit 0,1");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                 }
                }
                
            }   
            
        
    }    
    
    public function get_sub_items_rubrica($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,'',$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All('categoria',array('rubrica_id'=>$val['id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }            
    
    
    public function get_sub_items_categoria($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,array('rubrica_id'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All('criteriu',array('criteriu_cat_id'=>$val['criteriu_cat_id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
               $detalii1 = $this->mysql->get_All('categoria_detaliu',array('categoria_id'=>$val['id'],'parent >'=>0));               
               $detalii2 = $this->mysql->get_All('categoria_detaliu',array('categoria_id'=>$val['id'],'parent'=>0)); 
               if(count($detalii1)==count($detalii2)) $detalii= count($detalii1);
               else $detalii= count($detalii1)-count($detalii2);
               $catalog[$key]['detalii'] = abs($detalii);
               
            }
            return $catalog;
        }
        
    }     
    
   
    
    public function get_sub_items_criteriu($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,array('rubrica_id'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All('criteriu',array('rubrica_id'=>$val['id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }    
    public function get_sub_items($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        
        switch ($table) {
            case 'criteriu':
                $id_table = 'criteriu_cat_id';               
                break;
            case 'criteriu_cat':
                $id_table = 'id';               
                break;   
            case 'anunt':
                $id_table = 'id';               
                break;             
            case 'categoria_detaliu':
                $id_table = 'categoria_id';               
                break;
            default:
                $id_table = 'rubrica_id';               
                break;
        }        
        $catalog = $this->mysql->get_All($table,array($id_table=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All('criteriu',array($id_table=>$val['id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }    
    public function get_sub_criteriu_cat($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
     
        $catalog = $this->mysql->get_All($table,'',$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All('criteriu',array('criteriu_cat_id'=>$val['id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }    
    public function get_sub_items_detaliu($table,$id_table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        
                            
        $catalog = $this->mysql->get_All($table,array('categoria_id' =>$id_table ,'parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));               
               $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }     
}
?>