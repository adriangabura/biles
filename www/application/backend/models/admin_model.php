<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model
{   

/************** pages ***********/ 
 
    public function add_pages($cat,$parent)
    {
        $cat_id = $this->mysql->insert('pages',array('parent'=>$parent,'data_add'=>date("Y-m-d")));
        
        if($cat_id)
        {    
         foreach($cat as $item)
         {              
             
             if(isset($item['recomandat']) and $item['recomandat']=='on'){    
                    $this->mysql->update('pages',array('recomandat'=>'1'),array('id_pages'=>$cat_id));
                    unset($item['recomandat']);
                } else { $this->mysql->update('pages',array('recomandat'=>'0'),array('id_pages'=>$cat_id));}
             
            if($this->mysql->update('pages',$item,array('id_pages'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }   
        } $this->session->set_flashdata('erorr',lang('add_erorr'));      
        // url
        $news = $this->mysql->get_row('pages',array('id_pages'=>$cat_id));
        $url = url_slug($news['name_ru']);
        $url_exists = $this->mysql->get_row('pages',array('url'=>$url,'id_pages !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('pages',array('url'=>$url,'ord'=>$cat_id),array('id_pages'=>$cat_id));
        // end url        
    }
   public function edit_pages($cat,$id,$del_photo = null,$del_photo2 = null)
    {
        
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
             
              if(isset($item['recomandat']) and $item['recomandat']=='on'){    
                    $this->mysql->update('pages',array('recomandat'=>'1'),array('id_pages'=>$id));
                    unset($item['recomandat']);
                } else { $this->mysql->update('pages',array('recomandat'=>'0'),array('id_pages'=>$id));}
             
             
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
        $news = $this->mysql->get_row('pages',array('id_pages'=>$id));
        $url = url_slug($news['name_ru']);
        $url_exists = $this->mysql->get_row('pages',array('url'=>$url,'id_pages !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('pages',array('url'=>$url),array('id_pages'=>$id));
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
   
     /************** banners ***********/

    public function add_banners($cat,$parent)
    {
        $cat_id = $this->mysql->insert('banners',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('banners',$item,array('id_banners'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('banners',array('ord'=>$cat_id),array('id_banners'=>$cat_id));
    }

    public function edit_banners($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('banners',array('id_banners'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('banners',$item,array('id_banners'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('banners',array('id_banners'=>$id));
        if($ord['ord']==0) $this->mysql->update('banners',array('ord'=>$id),array('id_banners'=>$id));        
    }

    public function del_banners($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('banners',array('id_banners'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('banners',array('id_banners'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('banners',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('banners',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end banners ***********/   
    
     /************** social ***********/

    public function add_social($cat,$parent)
    {
        $cat_id = $this->mysql->insert('social',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('social',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('social',array('ord'=>$cat_id),array('id'=>$cat_id));
    }

    public function edit_social($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('social',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('social',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('social',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('social',array('ord'=>$id),array('id'=>$id));        
    }

    public function del_social($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('social',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('social',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('social',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('social',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end social ***********/   
    

      /************** news ***********/

    public function add_news($cat,$parent)
    {
        $cat_id = $this->mysql->insert('news',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('news',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('news',array('ord'=>$cat_id),array('id'=>$cat_id));
        
         // url
        $news = $this->mysql->get_row('news',array('id'=>$cat_id));
        $url = url_slug($news['name_ru']);
        $url_exists = $this->mysql->get_row('news',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('news',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }

    public function edit_news($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('news',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('news',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('news',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('news',array('ord'=>$id),array('id'=>$id));      
        
        
         // url
        $news = $this->mysql->get_row('news',array('id'=>$id));
        $url =  url_slug($news['name_ru']);
        $url_exists = $this->mysql->get_row('news',array('url'=>$url,'id !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('news',array('url'=>$url),array('id'=>$id));
        // end url    
    }

    public function del_news($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('news',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('news',array('id'=>$id)))
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
    
    
    
     /************** offers ***********/

    public function add_offers($cat,$parent)
    {
        $cat_id = $this->mysql->insert('offers',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('offers',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('offers',array('ord'=>$cat_id),array('id'=>$cat_id));
        
         // url
        $offers = $this->mysql->get_row('offers',array('id'=>$cat_id));
        $url = url_title($offers['name_ro'],'-',TRUE);
        $url_exists = $this->mysql->get_row('offers',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('offers',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }

    public function edit_offers($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('offers',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('offers',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('offers',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('offers',array('ord'=>$id),array('id'=>$id));      
        
        
         // url
        $offers = $this->mysql->get_row('offers',array('id'=>$id));
        $url = url_title($offers['name_ro'],'_',TRUE);
        $url_exists = $this->mysql->get_row('offers',array('url'=>$url,'id !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('offers',array('url'=>$url),array('id'=>$id));
        // end url    
    }

    public function del_offers($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('offers',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('offers',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('offers',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('offers',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end offers ***********/    
    
    
      /************** text ***********/

    public function add_text($cat,$parent)
    {
        
        $cat_id = $this->mysql->insert('text',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('text',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('text',array('ord'=>$cat_id),array('id'=>$cat_id));
        
         // url
        $text = $this->mysql->get_row('text',array('id'=>$cat_id));
        $url = url_slug($text['name_ru']);
        $url_exists = $this->mysql->get_row('text',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'_'.$cat_id;        
        $this->mysql->update('text',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }

    public function edit_text($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('text',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('text',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('text',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('text',array('ord'=>$id),array('id'=>$id));      
        
        
         // url
        $text = $this->mysql->get_row('text',array('id'=>$id));
        $url = url_slug($text['name_ru']);
        $url_exists = $this->mysql->get_row('text',array('url'=>$url,'id !='=>$id));
        if($url_exists) $url = $url.'_'.$id;        
        $this->mysql->update('text',array('url'=>$url),array('id'=>$id));
        // end url    
    }

    public function del_text($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('text',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('text',array('id'=>$id)))
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
    
   
     /************** slider ***********/

    public function add_slider($cat,$parent)
    {
        $cat_id = $this->mysql->insert('slider',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('slider',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('slider',array('ord'=>$cat_id),array('id'=>$cat_id));
        
         // url
//        $slider = $this->mysql->get_row('slider',array('id'=>$cat_id));
//        $url = url_title($slider['name_ro'],'-',TRUE);
//        $url_exists = $this->mysql->get_row('slider',array('url'=>$url,'id !='=>$cat_id));
//        if($url_exists) $url = $url.'_'.$cat_id;        
//        $this->mysql->update('slider',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url        
    }

    public function edit_slider($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('slider',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('slider',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('slider',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('slider',array('ord'=>$id),array('id'=>$id));      
        
        
         // url
//        $slider = $this->mysql->get_row('slider',array('id'=>$id));
//        $url = url_title($slider['name_ro'],'_',TRUE);
//        $url_exists = $this->mysql->get_row('slider',array('url'=>$url,'id !='=>$id));
//        if($url_exists) $url = $url.'_'.$id;        
//        $this->mysql->update('slider',array('url'=>$url),array('id'=>$id));
        // end url    
    }

    public function del_slider($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('slider',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('slider',array('id'=>$id)))
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
    
    
     /************** baner ***********/

    public function add_baner($cat,$parent)
    {
        $cat_id = $this->mysql->insert('baner',array('parent'=>$parent));
        if($cat_id)
        {
         foreach($cat as $item)
         {
            if($this->mysql->update('baner',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        $this->mysql->update('baner',array('ord'=>$cat_id),array('id'=>$cat_id));
    }

    public function edit_baner($cat,$id,$del_photo = null)
    {
        if($del_photo)
        {
            $photo = $this->mysql->get_row('baner',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].'/admin/'.$photo['photo']);
        }

        if($id)
        {  $i = 0;
         foreach($cat as $item)
         {
            if($this->mysql->update('baner',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        $ord = $this->mysql->get_row('baner',array('id'=>$id));
        if(isset($ord['ord']) and $ord['ord']==0) $this->mysql->update('baner',array('ord'=>$id),array('id'=>$id));        
    }

    public function del_baner($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             $photo = $this->mysql->get_row('baner',array('id'=>$id));
             if($photo['photo']!='' and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
             if($this->mysql->delete('baner',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('baner',array('parent'=>$id));
               if($children)
               {
                foreach($children as $child)
                {
                 if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$child['photo']);
                }
               }
               $this->mysql->delete('baner',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end baner ***********/           
/**************  auto ***********/      
    public function add_auto($cat,$parent)
    {
        $cat_id = $this->mysql->insert('auto',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
              
            if($this->mysql->update('auto',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_auto($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('auto',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('auto',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('auto',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_auto($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('auto',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('auto',array('parent'=>$id));
               $this->mysql->delete('auto',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
/**************end auto*********/
    
    /**************  catalog ***********/      
    public function add_catalog($cat,$parent,$preturi=null)
    {
        $cat_id = $this->mysql->insert('catalog',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {

              
            if($this->mysql->update('catalog',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
         
          
         
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
        
        
          // url
        $catalog = $this->mysql->get_row('catalog',array('id'=>$cat_id));
        $url = url_slug($catalog['name_ru'], array('transliterate'=>true,'delimiter'=>'-','lowercase'=>true));
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id !='=>$cat_id));
        if($url_exists) $url = $url.'-'.$cat_id;        
        $this->mysql->update('catalog',array('url'=>$url,'ord'=>$cat_id),array('id'=>$cat_id));
        // end url       
    }

    public function edit_catalog($cat,$id,$del_photo = null,$preturi=null,$preturi_red=null)
    {
           if($del_photo)
        {
            
            $photo = $this->mysql->get_row('catalog',array('id'=>$id));
            if($photo['photo'] !=''and file_exists($_SERVER['DOCUMENT_ROOT'].$photo['photo']))  unlink($_SERVER['DOCUMENT_ROOT'].$photo['photo']);
            $this->mysql->update('catalog',array('photo_ro'=>''),array('id'=>$id));
        }         
      
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             $i++;
            if($this->mysql->update('catalog',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
         /*echo '<pre>';
         print_r($_POST);*/
//         foreach ($preturi as $key => $pret) {
//            $ex_key = explode('_', $key);
//            $pret_id = $this->mysql->update('preturi',array('pret'=>$pret),  array('product_id'=>$id,'model_id'=>$ex_key[1])); 
//            if ($this->mysql->get_row('preturi',array('model_id'=>$ex_key[1],'product_id'=>$id))==false) {
//            $this->mysql->insert('preturi',array('pret'=>$pret,'product_id'=>$id,'model_id'=>$ex_key[1],'lungime_id'=>$ex_key[0]));   
//            } 
//        }
//        
//        foreach ($preturi_red as $key => $pret) {
//            $ex_key = explode('_', $key);
//            $pret_id = $this->mysql->update('preturi',array('pret_red'=>$pret),  array('product_id'=>$id,'model_id'=>$ex_key[1])); 
//            if ($this->mysql->get_row('preturi',array('model_id'=>$ex_key[1],'product_id'=>$id))==false) {
//            $this->mysql->insert('preturi',array('pret_red'=>$pret,'product_id'=>$id,'model_id'=>$ex_key[1],'lungime_id'=>$ex_key[0]));   
//            } 
//        }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        
        
        
        
        // ord
        $ord = $this->mysql->get_row('catalog',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('catalog',array('ord'=>$id),array('id'=>$id));
         
        // url
        $catalog = $this->mysql->get_row('catalog',array('id'=>$id));
        //$url = url_title($catalog['name_ro'],'-',TRUE);
		$url = url_slug($catalog['name_ru'], array('transliterate'=>true,'delimiter'=>'-','lowercase'=>true));
        $url_exists = $this->mysql->get_row('catalog',array('url'=>$url,'id !='=>$id));
        if($url_exists) $url = $url.'-'.$id;        
        $this->mysql->update('catalog',array('url'=>$url),array('id'=>$id));
        // end url  
        
        
    
     
    }

    public function del_catalog($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('catalog',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('catalog',array('parent'=>$id));
               $this->mysql->delete('catalog',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
/**************end catalog*********/
    
    
    /**************  truck ***********/      
    public function add_truck($cat,$parent)
    {
        $cat_id = $this->mysql->insert('truck',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
              
            if($this->mysql->update('truck',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_truck($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('truck',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('truck',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('truck',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_truck($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('truck',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('truck',array('parent'=>$id));
               $this->mysql->delete('truck',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
    /**********end truck*****************/
    
    
     /**************  moto ***********/      
    public function add_moto($cat,$parent)
    {
        $cat_id = $this->mysql->insert('moto',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
              
            if($this->mysql->update('moto',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_moto($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('moto',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('moto',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('moto',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_moto($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('moto',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('moto',array('parent'=>$id));
               $this->mysql->delete('moto',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
    /**********end moto*****************/
    
    
/**************  zona ***********/      
    public function add_zona($cat,$parent)
    {
        $cat_id = $this->mysql->insert('zona',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
         foreach($cat as $item)
         {
              
            if($this->mysql->update('zona',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_zona($cat,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('zona',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('zona',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('zona',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_zona($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('zona',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('zona',array('parent'=>$id));
               $this->mysql->delete('zona',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }
          
/**************  obiective ***********/      
    public function add_obiective($cat,$post,$parent)
    {
        $cat_id = $this->mysql->insert('obiective',array('parent'=>$parent));
        if($cat_id)
        {
            $i = 0;
            $this->mysql->update('obiective',array('map_x'=>$post['lat'],'map_y'=>$post['lng']),array('id'=>$cat_id));
         foreach($cat as $item)
         {
              
            if($this->mysql->update('obiective',$item,array('id'=>$cat_id)))
               $this->session->set_flashdata('succes',lang('add_succes'));
            else  $this->session->set_flashdata('erorr',lang('add_erorr'));
         }
        } $this->session->set_flashdata('erorr',lang('add_erorr'));
        
          
    }

    public function edit_obiective($cat,$post,$id,$del_photo = null,$del_1 = null,$del_2 = null,$del_3 = null)
    {
       
        if($id)
        {  $i = 0;
        
         $this->mysql->update('obiective',array('map_x'=>$post['lat'],'map_y'=>$post['lng']),array('id'=>$id));
                 
         foreach($cat as $item)
         { 
             
          
            if($this->mysql->update('obiective',$item,array('id'=>$id)))
            {
               $this->session->set_flashdata('succes',lang('edit_succes'));
            }
            else  $this->session->set_flashdata('erorr',lang('edit_erorr'));
         }
        }
        else $this->session->set_flashdata('erorr',lang('id_erorr'));
        
        // ord
        $ord = $this->mysql->get_row('obiective',array('id'=>$id));
        if($ord['ord']==0) $this->mysql->update('obiective',array('ord'=>$id),array('id'=>$id));
         
        
       // if()
    }

    public function del_obiective($selected)
    {
        foreach($selected as $id)
        {
           if($id)
           {
             if($this->mysql->delete('obiective',array('id'=>$id)))
             {
               //sergerm si copii
               $children = $this->mysql->get_All('obiective',array('parent'=>$id));
               $this->mysql->delete('obiective',array('parent'=>$id));
               $this->session->set_flashdata('succes',lang('del_succes'));

             }
             else $this->session->set_flashdata('error',lang('del_erorr'));
           }
           else $this->session->set_flashdata('error',lang('id_error'));
        }

    }


/************** end obiective ***********/       

/************** end auto ***********/   
    
    public function get_sub_items($table,$parent,$ord_by = null,$ord_type = null,$limit1 = null,$limit2 = null)
    {
        if($table=='catalog')        $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        elseif($table=='auto')       $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);  
        elseif($table=='truck')       $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type); 
        elseif($table=='moto')       $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);      
        elseif($table=='obiective')  $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);      
        else                         $catalog = $this->mysql->get_All($table,array('parent'=>$parent),$limit1,$limit2,$ord_by,$ord_type);
        
        if($catalog)
        {    
            foreach($catalog as $key=>$val)
            {  
               if($table=='catalog')  $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='auto')          $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='zona')          $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='obiective')     $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='catalog')       $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='user')       $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='truck')       $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               elseif($table=='moto')       $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id']));
               
               else                            $sub_items = $this->mysql->get_All($table,array('parent'=>$val['id_'.$table]));               
               $catalog[$key]['sub_items']                = count($sub_items);
            }
            return $catalog;
        }
        
    }     
    
   
    
 public function reorder($table,$id,$ac,$asc_desc = 'asc')
    {$table1 = $table;
         $table = 'app_'.$table;
         
          $old_item=$this->mysql->get_row($table,array('id_'.$table1=>$id));
          if($old_item['ord']==0) $this->mysql->update($table,array('ord'=>$id),array('id_'.$table1=>$id)); 
          
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

                $parent_item=$this->mysql->get_row($table,array('id_'.$table1=>$id));               

                $q= $this->db->query("SELECT `ord`,`id_$table1` FROM `$table` 
                        WHERE `ord`<'$old' order by `ord` $ord_up limit 0,1;");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id_'.$table1];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id_'.$table1=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id_'.$table1=>$new_id));
                 }
                } 
            }
           //daca e urmator
            if($ac=='down')
            {    
                $old=$old_item['ord'];

                $parent_item=$this->mysql->get_row($table,array('id_'.$table1=>$id));
                
                $q= $this->db->query("select `ord`,`id_$table1` from `$table` where `ord`>'$old' order by `ord` $ord_down limit 0,1");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id_'.$table1];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id_'.$table1=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id_'.$table1=>$new_id));
                 }
                }
                
            }   
            
        
    }    
    
    
     public function reorder2($table,$id,$ac,$asc_desc = 'asc')
    {
         $table = 'app_'.$table;
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
    
}
?>