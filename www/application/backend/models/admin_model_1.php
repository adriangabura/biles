<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{   
   
    /************** category ***********/
    
    public function add_category($cat,$parent)
    {
        $cat_id = $this->mysql->insert('category',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
             
             if($this->mysql->update('category',$item,array('id_category'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('category',array('id_category'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('category',array('url'=>$url,'id_category !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('category',array('url'=>$url,'ord'=>$cat_id),array('id_category'=>$cat_id));
        // end url         
    }

    public function edit_category($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('category',array('id_category'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('category',array('photo'=>'','thumb'=>''),array('id_category'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
             if($this->mysql->update('category',$item,array('id_category'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('category',array('id_category'=>$id));
        if($ord['ord']==0) $this->mysql->update('category',array('ord'=>$id),array('id_category'=>$id));
        
        // url
        $news = $this->mysql->get_row('category',array('id_category'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('category',array('url'=>$url,'id_category !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('category',array('url'=>$url),array('id_category'=>$id));
        // end url           
        
       // if()
    }

    public function del_category($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('category',array('id_category'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('category',array('id_category'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('category',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('category',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end category ***********/        

    
   /******************** *************/
       
    public function reorder($table,$id,$ac,$asc_desc = 'asc')
    {
          $old_item=$this->mysql->get_row($table,array('id_'.$table=>$id));
          if($old_item['ord']==0) $this->mysql->update($table,array('ord'=>$id),array('id_'.$table=>$id)); 
          
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

                $parent_item=$this->mysql->get_row($table,array('id_'.$table=>$id));
                $parent=$parent_item['parent'];

                $q= $this->db->query("SELECT `ord`,`id_$table` FROM `$table` 
                        WHERE `ord`<'$old' and `parent`='$parent' order by `ord` $ord_up limit 0,1;");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id_'.$table];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id_'.$table=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id_'.$table=>$new_id));
                 }
                } 
            }
           //daca e urmator
            if($ac=='down')
            {    
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id_'.$table=>$id));
                $parent=$parent_item['parent'];
                
                $q= $this->db->query("select `ord`,`id_$table` from `$table` where `ord`>'$old' and `parent`='$parent' order by `ord` $ord_down limit 0,1");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id_'.$table];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id_'.$table=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id_'.$table=>$new_id));
                 }
                }
                
            }   
            
        
    }    
    
    /************** services ***********/
    public function add_services($cat,$parent)
    {
        $cat_id = $this->mysql->insert('services',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('services',$item,array('id_services'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_services($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('services',array('id_services'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            $this->mysql->update('services',array('photo'=>''),array('id_services'=>$id));
        }
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('services',array('id_services'=>$id));
            if($photo['photo2'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo2']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo2']);
            $this->mysql->update('services',array('photo2'=>''),array('id_services'=>$id));
        }
      
        
 
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('services',$item,array('id_services'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_services($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('services',array('id_services'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('services',array('id_services'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('services',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('services',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end services ***********/  
     /************** partners ***********/

    public function add_partners($cat,$parent)
    {
        $cat_id = $this->mysql->insert('partners',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('partners',$item,array('id_partners'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        $this->mysql->update('partners',array('ord'=>$cat_id),array('id_partners'=>$cat_id));
    }

    public function edit_partners($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('partners',array('id_partners'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('partners',$item,array('id_partners'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
       // ord
        $ord = $this->mysql->get_row('partners',array('id_partners'=>$id));
        if($ord['ord']==0) $this->mysql->update('partners',array('ord'=>$id),array('id_partners'=>$id));
        
    }

    public function del_partners($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('partners',array('id_partners'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('partners',array('id_partners'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('partners',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('partners',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end partners ***********/         
   /************** slider ***********/

    public function add_slider($cat,$parent)
    {
        $cat_id = $this->mysql->insert('slider',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('slider',$item,array('id_slider'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        $this->mysql->update('slider',array('ord'=>$cat_id),array('id_slider'=>$cat_id));
    }

    public function edit_slider($cat,$id,$del_photo = null,$del_photo2 = null)
    {

        if($del_photo)
        {
            $photo = $this->mysql->get_row('slider',array('id_slider'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);            
        }
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('slider',array('id_slider'=>$id));
            if($photo['text_ro'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['text_ro']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['text_ro']);
         }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('slider',$item,array('id_slider'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('slider',array('id_slider'=>$id));
        if($ord['ord']==0) $this->mysql->update('slider',array('ord'=>$id),array('id_slider'=>$id));        
    }

    public function del_slider($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('slider',array('id_slider'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('slider',array('id_slider'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('slider',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('slider',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end slider ***********/
  /************** gallery ***********/

    public function add_gallery($cat,$parent)
    {
        $cat_id = $this->mysql->insert('gallery',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('gallery',$item,array('id_gallery'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        $this->mysql->update('gallery',array('ord'=>$cat_id),array('id_gallery'=>$cat_id));
    }

    public function edit_gallery($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('gallery',array('id_gallery'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('gallery',$item,array('id_gallery'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('gallery',array('id_gallery'=>$id));
        if($ord['ord']==0) $this->mysql->update('gallery',array('ord'=>$id),array('id_gallery'=>$id));        
    }

    public function del_gallery($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('gallery',array('id_gallery'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('gallery',array('id_gallery'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('gallery',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('gallery',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end gallery ***********/         

     /************** faq ***********/

    public function add_faq($cat,$parent)
    {
        $cat_id = $this->mysql->insert('faq',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('faq',$item,array('id_faq'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_faq($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('faq',array('id_faq'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('faq',$item,array('id_faq'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_faq($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('faq',array('id_faq'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('faq',array('id_faq'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('faq',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('faq',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end faq ***********/      

     /************** text ***********/

    public function add_text($cat,$parent)
    {
        $cat_id = $this->mysql->insert('text',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('text',$item,array('id_text'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_text($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('text',array('id_text'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('text',$item,array('id_text'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_text($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('text',array('id_text'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('text',array('id_text'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('text',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('text',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end text ***********/         
     /************** catalog ***********/

    
    public function get_sub_items($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        if($table=='catalog')  $catalog = $this->mysql->get_All($table,array('category_id'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        else  $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
               if($table=='catalog_category')  $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='catalog')  $sub_items = $this->mysql->get_All($table,array('category_id'=>$val['id_'.$table]));
               
               else   $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id_'.$table]));
                $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }        
     
    public function get_sub_items_pro($table,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,'',$limit1,$limit2,$ord_by,$ord_type);
        return $catalog;
      
        
    }        
    
    public function get_sub_items_new($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
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
    
    public function get_sub_items_new2($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        $catalog = $this->mysql->get_All($table,array('produs_id'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {
                $sub_items = $this->mysql->get_All($table,array('produs_id'=>$val['id']));
                $catalog[$key]['sub_items'] = count($sub_items);
            }
            return $catalog;
        }
        
    }   
  
    
    public function edit_catalog_category($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
        $photo = $this->mysql->get_row('catalog_category',array('id'=>$id));
        if($del_photo)
        {
            $photo = $this->mysql->get_row('catalog_category',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('catalog_category',array('photo'=>'','thumb'=>''),array('id'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
            if($this->mysql->update('catalog_category',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
          }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        /*
        $ord = $this->mysql->get_row('catalog_category',array('id'=>$id));
        if($ord and $ord['ord']==0) $this->mysql->update('catalog_category',array('ord'=>$id),array('id'=>$id));
        */
        // url
        $news = $this->mysql->get_row('catalog_category',array('id'=>$id));
        if($news)
        {    
            $url = url_title($news['name_ro'],'-',TRUE);
            $url_exists = $this->mysql->get_row('catalog_category',array('url'=>$url,'id !='=>$id));
            if($url_exists) $url = $url.'_'.$id;        
            $this->mysql->update('catalog_category',array('url'=>$url),array('id'=>$id));
        }
        // end url           
        
       // if()
    }
    
    public function add_catalog_category($cat,$parent)
    {
        $cat_id = $this->mysql->insert('catalog_category',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
             
           
             
            if($this->mysql->update('catalog_category',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('catalog_category',array('id'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('catalog_category',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('catalog_category',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url         
    }
   
    public function del_catalog_category($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('catalog_category',array('id'=>$id)))
             {
               
             
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }    
        
    
    public function add_catalog($cat,$parent)
    {
        $cat_id = $this->mysql->insert('catalog',array('category_id'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
             
           
             
            if($this->mysql->update('catalog',$item,array('id_catalog'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('catalog',array('id_catalog'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id_catalog !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('catalog',array('url'=>$url,'ord'=>$cat_id),array('id_catalog'=>$cat_id));
        // end url         
    }

    
    public function add_catalog2($cat,$parent)
    {
        $cat_id = $this->mysql->insert('catalog',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
             
            //check box
             $i++;
            if($i==1)
            {    
            
                if(isset($item['news']) and $item['news']=='on')
                {    
                    $this->mysql->update('catalog',array('news'=>'1'),array('id_catalog'=>$cat_id));
                    unset($item['news']);
                } else { $this->mysql->update('catalog',array('news'=>'0'),array('id_catalog'=>$cat_id));}
            
                if(isset($item['promotii']) and $item['promotii']=='on')
                {    
                    $this->mysql->update('catalog',array('promotii'=>'1'),array('id_catalog'=>$cat_id));
                    unset($item['promotii']);
                } else { $this->mysql->update('catalog',array('promotii'=>'0'),array('id_catalog'=>$cat_id));}
            
                if(isset($item['gratuit']) and $item['gratuit']=='on')
                {    
                    $this->mysql->update('catalog',array('gratuit'=>'1'),array('id_catalog'=>$cat_id));
                    unset($item['gratuit']);
                } else { $this->mysql->update('catalog',array('gratuit'=>'0'),array('id_catalog'=>$cat_id));}
                
                
            }
            // end checkbox
             
            if($this->mysql->update('catalog',$item,array('id_catalog'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('catalog',array('id_catalog'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id_catalog !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('catalog',array('url'=>$url,'ord'=>$cat_id),array('id_catalog'=>$cat_id));
        // end url         
    }

    
    
    public function edit_catalog($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
        $photo = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        if($del_photo)
        {
            $photo = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('catalog',array('photo'=>'','thumb'=>''),array('id_catalog'=>$id));
        }

        if($del_1)
        {            
            if($photo['pdf1'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['pdf1']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['pdf1']);
            $this->mysql->update('catalog',array('pdf1'=>''),array('id_catalog'=>$id));
        }
        
        if($del_2)
        {            
            if($photo['pdf2'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['pdf2']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['pdf2']);
            $this->mysql->update('catalog',array('pdf2'=>''),array('id_catalog'=>$id));
        }
        

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;
            if($i==1)
            {    
            
                if(isset($item['news']) and $item['news']=='on')
                {    
                    $this->mysql->update('catalog',array('news'=>'1'),array('id_catalog'=>$id));
                    unset($item['news']);
                } else { $this->mysql->update('catalog',array('news'=>'0'),array('id_catalog'=>$id));}
            
                if(isset($item['promotii']) and $item['promotii']=='on')
                {    
                    $this->mysql->update('catalog',array('promotii'=>'1'),array('id_catalog'=>$id));
                    unset($item['promotii']);
                } else { $this->mysql->update('catalog',array('promotii'=>'0'),array('id_catalog'=>$id));}
            
                if(isset($item['gratuit']) and $item['gratuit']=='on')
                {    
                    $this->mysql->update('catalog',array('gratuit'=>'1'),array('id_catalog'=>$id));
                    unset($item['gratuit']);
                } else { $this->mysql->update('catalog',array('gratuit'=>'0'),array('id_catalog'=>$id));}
                
                
            }
            // end checkbox
            if($this->mysql->update('catalog',$item,array('id_catalog'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        if($ord['ord']==0) $this->mysql->update('catalog',array('ord'=>$id),array('id_catalog'=>$id));
        
        // url
        $news = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id_catalog !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('catalog',array('url'=>$url),array('id_catalog'=>$id));
        // end url           
        
       // if()
    }

   public function edit_catalog2($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
        $photo = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        if($del_photo)
        {
            $photo = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('catalog',array('photo'=>'','thumb'=>''),array('id_catalog'=>$id));
        }

        if($del_1)
        {            
            if($photo['doc_1'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['doc_1']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['doc_1']);
            $this->mysql->update('catalog',array('doc_1'=>''),array('id_catalog'=>$id));
        }

        if($del_2)
        {            
            if($photo['doc_2'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['doc_2']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['doc_2']);
            $this->mysql->update('catalog',array('doc_2'=>''),array('id_catalog'=>$id));
        }

        if($del_3)
        {            
            if($photo['doc_3'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['doc_3']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['doc_3']);
            $this->mysql->update('catalog',array('doc_3'=>''),array('id_catalog'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;
            if($i==1)
            {    
            
                if(isset($item['news']) and $item['news']=='on')
                {    
                    $this->mysql->update('catalog',array('news'=>'1'),array('id_catalog'=>$id));
                    unset($item['news']);
                } else { $this->mysql->update('catalog',array('news'=>'0'),array('id_catalog'=>$id));}
            
                if(isset($item['promotii']) and $item['promotii']=='on')
                {    
                    $this->mysql->update('catalog',array('promotii'=>'1'),array('id_catalog'=>$id));
                    unset($item['promotii']);
                } else { $this->mysql->update('catalog',array('promotii'=>'0'),array('id_catalog'=>$id));}
            
                if(isset($item['gratuit']) and $item['gratuit']=='on')
                {    
                    $this->mysql->update('catalog',array('gratuit'=>'1'),array('id_catalog'=>$id));
                    unset($item['gratuit']);
                } else { $this->mysql->update('catalog',array('gratuit'=>'0'),array('id_catalog'=>$id));}
                
                
            }
            // end checkbox
            if($this->mysql->update('catalog',$item,array('id_catalog'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        if($ord['ord']==0) $this->mysql->update('catalog',array('ord'=>$id),array('id_catalog'=>$id));
        
        // url
        $news = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id_catalog !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('catalog',array('url'=>$url),array('id_catalog'=>$id));
        // end url           
        
       // if()
    }
    
   
    public function del_catalog($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('catalog',array('id_catalog'=>$id)))
             {
               
             
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }    
    
    public function del_catalog2($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('catalog',array('id_catalog'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('catalog',array('id_catalog'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('catalog',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('catalog',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end catalog ***********/  
    
    public function add_produse($cat,$parent)
    {
        $cat_id = $this->mysql->insert('produse',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {   
            if($this->mysql->update('produse',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
             
    }

    public function edit_produse($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('produse',array('id'=>$id));
            if($photo['path_img'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_img']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_img']);
            if($photo['path_thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']);				 
            $this->mysql->update('produse',array('path_img'=>'','path_thumb'=>''),array('id'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;
            if($i==1)
            {    
                if(isset($item['home']) and $item['home']=='on')
                {    
                    $this->mysql->update('produse',array('home'=>'1'),array('id'=>$id));
                    unset($item['home']);
                } else { $this->mysql->update('produse',array('home'=>'0'),array('id'=>$id));}
                
               
                
            }
            // end checkbox
           
            
            if($this->mysql->update('produse',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
         
        
       // if()
    }

    public function del_produse($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('produse',array('id'=>$id));
             if($photo['path_thumb']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']);
             if($photo['path_img']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_img']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_img']);
             if($this->mysql->delete('produse',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('produse',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('produse',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end produse ***********/        
    
    public function add_produse_options($cat,$parent)
    {
        $cat_id = $this->mysql->insert('produse_options',array('produs_id'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {   
            if($this->mysql->update('produse_options',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
             
    }

    public function edit_produse_options($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('produse_options',array('id'=>$id));
            if($photo['path_img'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_img']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_img']);
            if($photo['path_thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path_thumb']);				 
            $this->mysql->update('produse_options',array('path_img'=>'','path_thumb'=>''),array('id'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;/*
            if($i==1)
            {    
                if(isset($item['discount']) and $item['discount']=='on')
                {    
                    $this->mysql->update('produse_options',array('discount'=>'1'),array('id'=>$id));
                    unset($item['discount']);
                } else { $this->mysql->update('produse_options',array('discount'=>'0'),array('id'=>$id));}
                
                if(isset($item['recomand']) and $item['recomand']=='on')
                {    
                    $this->mysql->update('produse_options',array('recomand'=>'1'),array('id'=>$id));
                    unset($item['recomand']);
                } else { $this->mysql->update('produse_options',array('recomand'=>'0'),array('id'=>$id));}
                
                if(isset($item['new']) and $item['new']=='on')
                {    
                    $this->mysql->update('produse_options',array('new'=>'1'),array('id'=>$id));
                    unset($item['new']);
                } else { $this->mysql->update('produse_options',array('new'=>'0'),array('id'=>$id));}
                
                if(isset($item['top']) and $item['top']=='on')
                {    
                    $this->mysql->update('produse_options',array('top'=>'1'),array('id'=>$id));
                    unset($item['top']);
                } else { $this->mysql->update('produse_options',array('top'=>'0'),array('id'=>$id));}
                
                if(isset($item['on_sale']) and $item['on_sale']=='on')
                {    
                    $this->mysql->update('produse_options',array('on_sale'=>'1'),array('id'=>$id));
                    unset($item['on_sale']);
                } else { $this->mysql->update('produse_options',array('on_sale'=>'0'),array('id'=>$id));}
                
            }
            // end checkbox
           */
            
            if($this->mysql->update('produse_options',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
         
        
       // if()
    }

    public function del_produse_options($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('produse_options',array('id'=>$id)))
             {
              
               
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end produse_options ***********/        
    
    
     /************** portfolio ***********/

    
    public function add_portfolio($cat,$parent)
    {
        $cat_id = $this->mysql->insert('portfolio',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('portfolio',$item,array('id_portfolio'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('portfolio',array('id_portfolio'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('portfolio',array('url'=>$url,'id_portfolio !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('portfolio',array('url'=>$url,'ord'=>$cat_id),array('id_portfolio'=>$cat_id));
        // end url         
    }

    public function edit_portfolio($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('portfolio',array('id_portfolio'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('portfolio',array('photo'=>'','thumb'=>''),array('id_portfolio'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;
            if($i==1)
            {    
                if(isset($item['discount']) and $item['discount']=='on')
                {    
                    $this->mysql->update('portfolio',array('discount'=>'1'),array('id_portfolio'=>$id));
                    unset($item['discount']);
                } else { $this->mysql->update('portfolio',array('discount'=>'0'),array('id_portfolio'=>$id));}
                
                if(isset($item['new']) and $item['new']=='on')
                {    
                    $this->mysql->update('portfolio',array('new'=>'1'),array('id_portfolio'=>$id));
                    unset($item['new']);
                } else { $this->mysql->update('portfolio',array('new'=>'0'),array('id_portfolio'=>$id));}
                
                if(isset($item['top']) and $item['top']=='on')
                {    
                    $this->mysql->update('portfolio',array('top'=>'1'),array('id_portfolio'=>$id));
                    unset($item['top']);
                } else { $this->mysql->update('portfolio',array('top'=>'0'),array('id_portfolio'=>$id));}
                
            }
            // end checkbox
            if($this->mysql->update('portfolio',$item,array('id_portfolio'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('portfolio',array('id_portfolio'=>$id));
        if($ord['ord']==0) $this->mysql->update('portfolio',array('ord'=>$id),array('id_portfolio'=>$id));
        
        // url
        $news = $this->mysql->get_row('portfolio',array('id_portfolio'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('portfolio',array('url'=>$url,'id_portfolio !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('portfolio',array('url'=>$url),array('id_portfolio'=>$id));
        // end url           
        
       // if()
    }

    public function del_portfolio($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('portfolio',array('id_portfolio'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('portfolio',array('id_portfolio'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('portfolio',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('portfolio',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end portfolio ***********/        
   /************** proiecte ***********/

    
    public function add_proiecte($cat,$parent)
    {
        $cat_id = $this->mysql->insert('proiecte',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('proiecte',$item,array('id_proiecte'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        // url
        $news = $this->mysql->get_row('proiecte',array('id_proiecte'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('proiecte',array('url'=>$url,'id_proiecte !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('proiecte',array('url'=>$url,'ord'=>$cat_id),array('id_proiecte'=>$cat_id));
        // end url         
    }

    public function edit_proiecte($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('proiecte',array('id_proiecte'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);				 
            $this->mysql->update('proiecte',array('photo'=>'','thumb'=>''),array('id_proiecte'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
            //check box
             $i++;
            if($i==1)
            {    
                if(isset($item['last']) and $item['last']=='on')
                {    
                    $this->mysql->update('proiecte',array('last'=>'1'),array('id_proiecte'=>$id));
                    unset($item['last']);
                } else { $this->mysql->update('proiecte',array('last'=>'0'),array('id_proiecte'=>$id));}
                
            }
            // end checkbox
            if($this->mysql->update('proiecte',$item,array('id_proiecte'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('proiecte',array('id_proiecte'=>$id));
        if($ord['ord']==0) $this->mysql->update('proiecte',array('ord'=>$id),array('id_proiecte'=>$id));
        
        // url
        $news = $this->mysql->get_row('proiecte',array('id_proiecte'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('proiecte',array('url'=>$url,'id_proiecte !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('proiecte',array('url'=>$url),array('id_proiecte'=>$id));
        // end url           
        
       // if()
    }

    public function del_proiecte($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('proiecte',array('id_proiecte'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('proiecte',array('id_proiecte'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('proiecte',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('proiecte',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end proiecte ***********/     


      /************** news ***********/

    public function add_news($cat,$parent)
    {
        $cat_id = $this->mysql->insert('news',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
             
            $this->load->model('data_model');
            if(isset($item['data']))
            {    
                $this->mysql->update('news',array('data'=>$this->data_model->Convert_data($item['data'])),array('id_news'=>$cat_id));
                unset($item['data']);
            }                  
             
            if($this->mysql->update('news',$item,array('id_news'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));

        // url
        $news = $this->mysql->get_row('news',array('id_news'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('news',array('url'=>$url,'id_news !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('news',array('url'=>$url,'ord'=>$cat_id),array('id_news'=>$cat_id));
        // end url         
    }

    public function edit_news($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('news',array('id_news'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);
            $this->mysql->update('news',array('photo'=>'','thumb'=>''),array('id_news'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
             
            $this->load->model('data_model');
            if(isset($item['data']))
            {    
                $this->mysql->update('news',array('data'=>$this->data_model->Convert_data($item['data'])),array('id_news'=>$id));
                unset($item['data']);
            }              
            if($this->mysql->update('news',$item,array('id_news'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('news',array('id_news'=>$id));
        if($ord['ord']==0) $this->mysql->update('news',array('ord'=>$id),array('id_news'=>$id));        
        // url
        $news = $this->mysql->get_row('news',array('id_news'=>$id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('news',array('url'=>$url,'id_news !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('news',array('url'=>$url),array('id_news'=>$id));
        // end url          
    }

    public function del_news($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('news',array('id_news'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('news',array('id_news'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('news',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('news',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end news ***********/   


      /************** blog ***********/

    public function add_blog($cat,$parent)
    {
        $cat_id = $this->mysql->insert('blog',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('blog',$item,array('id_blog'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));

        // url
        $blog = $this->mysql->get_row('blog',array('id_blog'=>$cat_id));
        $url = url_title($blog['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('blog',array('url'=>$url,'id_blog !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('blog',array('url'=>$url,'ord'=>$cat_id),array('id_blog'=>$cat_id));
        // end url         
    }

    public function edit_blog($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('blog',array('id_blog'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            if($photo['thumb'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['thumb']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['thumb']);
            $this->mysql->update('blog',array('photo'=>'','thumb'=>''),array('id_blog'=>$id));
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('blog',$item,array('id_blog'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('blog',array('id_blog'=>$id));
        if($ord['ord']==0) $this->mysql->update('blog',array('ord'=>$id),array('id_blog'=>$id));        
        // url
        $blog = $this->mysql->get_row('blog',array('id_blog'=>$id));
        $url = url_title($blog['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('blog',array('url'=>$url,'id_blog !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('blog',array('url'=>$url),array('id_blog'=>$id));
        // end url          
    }

    public function del_blog($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('blog',array('id_blog'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('blog',array('id_blog'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('blog',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('blog',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end blog ***********/   

    /************** offerts ***********/

    public function add_offerts($cat,$parent)
    {
        $cat_id = $this->mysql->insert('offerts',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
         
            $this->load->model('data_model');
            
            if(isset($item['data']))
            {    
                $this->mysql->update('offerts',array('data'=>$this->data_model->Convert_data($item['data'])),array('id_offerts'=>$cat_id));
                unset($item['data']);
            }                  
             
             
            if($this->mysql->update('offerts',$item,array('id_offerts'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_offerts($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('offerts',array('id_offerts'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
         
            $this->load->model('data_model');
            
            if(isset($item['data']))
            {    
                $this->mysql->update('offerts',array('data'=>$this->data_model->Convert_data($item['data'])),array('id_offerts'=>$id));
                unset($item['data']);
            }                  
             
             
            if($this->mysql->update('offerts',$item,array('id_offerts'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_files_products($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('files_products',array('idfp'=>$id));
             if($photo['path']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['path']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['path']);
             if($this->mysql->delete('files_products',array('idfp'=>$id)))
             {     
               $this->session->set_flashdata('succes',lang('del_succes'));
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
    
    
    public function del_comments($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('comments',array('id_comments'=>$id)))
             {
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }    
    
    public function del_offerts($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('offerts',array('id_offerts'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('offerts',array('id_offerts'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('offerts',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('offerts',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end offerts ***********/

    /************** products ***********/

    public function add_products($cat,$parent)
    {
        $cat_id = $this->mysql->insert('products',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('products',$item,array('id_products'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_products($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('products',array('id_products'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('products',$item,array('id_products'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_products($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('products',array('id_products'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('products',array('id_products'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('products',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('products',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end products ***********/    
/************** pages ***********/ 
 
    public function add_pages($cat,$parent)
    {
        $cat_id = $this->mysql->insert('pages',array('parent'=>$parent,'data_add'=>date("Y-m-d")));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('pages',$item,array('id_pages'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));      
        // url
        $news = $this->mysql->get_row('pages',array('id_pages'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('pages',array('url'=>$url,'id_pages !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('pages',array('url'=>$url,'ord'=>$cat_id),array('id_pages'=>$cat_id));
        // end url        
    }
   public function edit_pages($cat,$id,$del_photo = null,$del_photo2 = null)
    {
    
        /*
        if($del_photo)
        {
            $photo = $this->mysql->get_row('pages',array('id_pages'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }
 */
        
        if($del_photo)
        {
            
            $photo = $this->mysql->get_row('pages',array('id_pages'=>$id));
            if($photo['photo_ro'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_ro']);
            $this->mysql->update('pages',array('photo_ro'=>''),array('id_pages'=>$id));
        }         
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('pages',array('id_pages'=>$id));
            if($photo['photo_en'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo_en']);
            $this->mysql->update('pages',array('photo_en'=>''),array('id_pages'=>$id));
        }   
        
        
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('pages',$item,array('id_pages'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('pages',array('id_pages'=>$id));
        if($ord['ord']==0) $this->mysql->update('pages',array('ord'=>$id),array('id_pages'=>$id));
        
        // url
        //$news = $this->mysql->get_row('pages',array('id_pages'=>$id));
        //$url = url_title($news['name_ro'],'_',TRUE);
        //$url_exists = $this->mysql->get_row('pages',array('url'=>$url,'id_pages !='=>$id));
        //if($url_exists) $url = $url.'_'.$id;        
        //$this->mysql->update('pages',array('url'=>$url),array('id_pages'=>$id));
        // end url           
    }
    
    public function del_pages($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {
             $photo = $this->mysql->get_row('pages',array('id_pages'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('pages',array('id_pages'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('pages',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($child['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$child['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('pages',array('parent'=>$id));  
               $this->session->set_flashdata('succes',lang('del_succes'));      
             
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end pages ***********/     
 /************** doc_add ***********/ 
 
    public function add_doc_add($cat,$parent)
    {
        $cat_id = $this->mysql->insert('doc_add',array('parent'=>$parent,'uid'=>$this->session->userdata('uid'),'data_add'=>date("Y-m-d")));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('doc_add',$item,array('id_doc_add'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));      
        // url
        $news = $this->mysql->get_row('doc_add',array('id_doc_add'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('doc_add',array('url'=>$url,'id_doc_add !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('doc_add',array('url'=>$url,'ord'=>$cat_id),array('id_doc_add'=>$cat_id));
        // end url        
    }
    
    public function edit_doc_add($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
            if($photo['file'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['file']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['file']);
          //  echo "da";
        }
 
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
            if($photo['photo2'] !=''and file_exists($photo['photo2']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo2']);
        }
 
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('doc_add',$item,array('id_doc_add'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        if(!$del_photo)
        {
            // ord
            $ord = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
            if($ord['ord']==0) $this->mysql->update('doc_add',array('ord'=>$id),array('id_doc_add'=>$id));

            // url
            $news = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
            $url = url_title($news['name_ro'],'-',TRUE);
            $url_exists = $this->mysql->get_row('doc_add',array('url'=>$url,'id_doc_add !='=>$id));
            if($url_exists) $url = $url.'_'.$id;        
            $this->mysql->update('doc_add',array('url'=>$url),array('id_doc_add'=>$id));
            // end url  
        }
    }
    
    
    public function del_doc_add($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {
             $photo = $this->mysql->get_row('doc_add',array('id_doc_add'=>$id));
             if($photo['file']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['file']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['file']);
             if($this->mysql->delete('doc_add',array('id_doc_add'=>$id)))
             {              
               $this->mysql->delete('doc_add',array('parent'=>$id));  
               $this->session->set_flashdata('succes',lang('del_succes'));                   
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end doc_add ***********/   
/************** doc_edit ***********/ 
 
    public function add_doc_edit($cat,$parent)
    {
        $cat_id = $this->mysql->insert('doc_edit',array('parent'=>$parent,'uid'=>$this->session->userdata('uid'),'data_add'=>date("Y-m-d")));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {                
            if($this->mysql->update('doc_edit',$item,array('id_doc_edit'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));      
        // url
        $news = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$cat_id));
        $url = url_title($news['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('doc_edit',array('url'=>$url,'id_doc_edit !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('doc_edit',array('url'=>$url,'ord'=>$cat_id),array('id_doc_edit'=>$cat_id));
        // end url        
    }
    
    public function edit_doc_edit($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
            if($photo['file'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['file']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['file']);
          //  echo "da";
        }
 
        if($del_photo2)
        {
            $photo = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
            if($photo['photo2'] !=''and file_exists($photo['photo2']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo2']);
        }
 
        if($id)
        {  $i = 0;  
         foreach($cat as $item)
         {                
            if($this->mysql->update('doc_edit',$item,array('id_doc_edit'=>$id)))
            { 
              
               $this->session->set_flashdata('succes',lang('edit_succes'));             
            }        
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        if(!$del_photo)
        {
            // ord
            $ord = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
            if($ord['ord']==0) $this->mysql->update('doc_edit',array('ord'=>$id),array('id_doc_edit'=>$id));

            // url
            $news = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
            $url = url_title($news['name_ro'],'-',TRUE);
            $url_exists = $this->mysql->get_row('doc_edit',array('url'=>$url,'id_doc_edit !='=>$id));
            if($url_exists) $url = $url.'_'.$id;        
            $this->mysql->update('doc_edit',array('url'=>$url),array('id_doc_edit'=>$id));
            // end url  
        }
    }
    
    
    public function del_doc_edit($selected)
    { 
        foreach($selected as $id) 
        {
           if($id)
           {
             $photo = $this->mysql->get_row('doc_edit',array('id_doc_edit'=>$id));
             if($photo['file']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['file']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['file']);
             if($this->mysql->delete('doc_edit',array('id_doc_edit'=>$id)))
             {              
               $this->mysql->delete('doc_edit',array('parent'=>$id));  
               $this->session->set_flashdata('succes',lang('del_succes'));                   
             }
             else $this->session->set_flashdata('error',lang('del_erorr'));    
           }    
           else $this->session->set_flashdata('error',lang('id_error'));                 
        } 
        
    }    
    
/************** end doc_edit ***********/   
/************** projects ***********/

    public function add_projects($cat,$parent)
    {
        $cat_id = $this->mysql->insert('projects',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('projects',$item,array('id_projects'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_projects($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('projects',array('id_projects'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
        }
 
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('projects',$item,array('id_projects'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_projects($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('projects',array('id_projects'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('projects',array('id_projects'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('projects',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('projects',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end projects ***********/
    /************** command ***********/

    public function add_command($cat,$parent)
    {
        $cat_id = $this->mysql->insert('command',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('command',$item,array('id_command'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_command($cat,$id)
    {
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('command',$item,array('id_command'=>$id)))
            {

               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_command($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('command',array('id_command'=>$id)))
             {
               $this->mysql->delete('command',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end command ***********/
    
/************** rev ***********/

    public function add_rev($cat,$parent)
    {
        $cat_id = $this->mysql->insert('rev',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('rev',$item,array('id_rev'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_rev($cat,$id)
    {
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('rev',$item,array('id_rev'=>$id)))
            {

               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_rev($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('rev',array('id_rev'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('rev',array('id_rev'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('rev',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('rev',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end rev ***********/
        
/************** sfaturi ***********/

    public function add_sfaturi($cat,$parent)
    {
        $cat_id = $this->mysql->insert('sfaturi',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('sfaturi',$item,array('id_sfaturi'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_sfaturi($cat,$id)
    {
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('sfaturi',$item,array('id_sfaturi'=>$id)))
            {

               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_sfaturi($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('sfaturi',array('id_sfaturi'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('sfaturi',array('id_sfaturi'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('sfaturi',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('sfaturi',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end sfaturi ***********/
    /************** video ***********/

    public function add_video($cat,$parent)
    {
        $cat_id = $this->mysql->insert('video',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('video',$item,array('id_video'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_video($cat,$id)
    {
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('video',$item,array('id_video'=>$id)))
            {

               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_video($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('video',array('id_video'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('video',array('id_video'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('video',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('video',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end video ***********/
 /************** price ***********/

    public function add_price($cat,$parent)
    {
        $cat_id = $this->mysql->insert('price',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('price',$item,array('id_price'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
    }

    public function edit_price($cat,$id)
    {
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('price',$item,array('id_price'=>$id)))
            {

               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
    }

    public function del_price($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('price',array('id_price'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('price',array('id_price'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('price',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('price',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end price ***********/
    
    
    
}
?>