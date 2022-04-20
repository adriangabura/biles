<?php

function crumbs ($crumbs, $type = null,$param = NULL)
{
    $html = '<div class="crumbs clearfix">';
    $html .='<a href="/">Acasa</a> >';
    switch ($type) {
        case 'account':
            $html .=' <a href="/">Contul Meu </a> >';
            break;
        case 'shop':
            break;
        default:
            break;
    }
   
    $count = count($crumbs);
    
   
    if ($count == 1) {
        $html .=' <a href="'.$crumbs[0]['url'].'"  >   '.$crumbs[0]['title'].'</a> ';
    } else {
        foreach ($crumbs as $key => $value) {
            $html .=' <a href="'.$value['url'].'"  >'.$value['title'].'</a> ';
            if ($key + 1 != $count) {
                $html.='>';
            }
        }
    }
    $html.='</div>';
    return $html;
           
    
}

function crumbs_shop_cat($parent,$id)
{
    $ci =& get_instance();
    
    switch ($parent) 
    {
        case 2:
            
            $url = '/ebooks/';
            break;
        
        case 3:
             $url = '/news/';
            break;
        
        case 4:
            $url = '/promotions/';
            break;
        
        default:
            break;
    }
    
    $page_parent             = $ci->mysql->get_row('pages',array('id_pages'=>$parent));
    $page_detail             = $ci->mysql->get_row('catalog_category',array('id'=>$id));
    
    $crumbs              = array(
           0 => array(
              'title'       =>  $page_parent['name_ro'],
              'url'         =>  $url
          ),
          1 => array(
              'title'       =>  $page_detail['name_ro'],
              'url'         =>  $url.$page_detail['id'].'-'.url_title($page_detail['name_ro'],'_',TRUE)
          )
       );
           
       if ($page_detail['parent'] != 0)
       {

          $crumbs_parent = $ci->mysql->get_row('catalog_category',array('id'=>$page_detail['parent']));

            $crumbs[1] =  array(
                  'title'       =>  $crumbs_parent['name_ro'],
                  'url'         =>  $url.$crumbs_parent['id'].'-'.url_title($crumbs_parent['name_ro'],'_',TRUE)
              );
            $crumbs[2] =  array(
                  'title'       =>  $page_detail['name_ro'],
                  'url'         =>  $url.$page_detail['id'].'-'.url_title($page_detail['name_ro'],'_',TRUE)
              );

       }
       return $crumbs;
}

function crumbs_shop_bookdetail($parent,$id)
{
    $ci =& get_instance();
    
    switch ($parent) 
    {
        case 2:
            
            $url = '/ebooks/';
            break;
        
        case 3:
             $url = '/news/';
            break;
        
        case 4:
            $url = '/promotions/';
            break;
         case 5:
            $url = '/free/';
            break;
        
        default:
            break;
    }
    
    $page_parent             = $ci->mysql->get_row('pages',array('id_pages'=>$parent));
    $page_detail             = $ci->mysql->get_row('catalog',array('id_catalog'=>$id));
    
    $cat_detail             = $ci->mysql->get_row('catalog_category',array('id'=>$page_detail['category_id']));
    
    $crumbs              = array(
          0 => array(
              'title'       =>  $page_parent['name_ro'],
              'url'         =>  $url
          )
          
       );
           
       if ($cat_detail['parent'] != 0)
       {

          $crumbs_parent = $ci->mysql->get_row('catalog_category',array('id'=>$cat_detail['parent']));

            $crumbs[1] =  array(
                  'title'       =>  $crumbs_parent['name_ro'],
                  'url'         =>  $url.$crumbs_parent['id'].'-'.url_title($crumbs_parent['name_ro'],'_',TRUE)
              );
            $crumbs[2] =  array(
                  'title'       =>  $cat_detail['name_ro'],
                  'url'         =>  $url.$cat_detail['id'].'-'.url_title($cat_detail['name_ro'],'_',TRUE)
              );
             $crumbs[3] = array(
              'title'       =>  $page_detail['name_ro'],
              'url'         =>  $url.$page_detail['id_catalog'].'-'.url_title($page_detail['name_ro'],'_',TRUE)
          );
       } 
       else
       {
            $crumbs[1] =  array(
                  'title'       =>  $cat_detail['name_ro'],
                  'url'         =>  $url.$cat_detail['id'].'-'.url_title($cat_detail['name_ro'],'_',TRUE)
              );
            $crumbs[2] = array(
              'title'       =>  $page_detail['name_ro'],
              'url'         =>  $url.$page_detail['id_catalog'].'-'.url_title($page_detail['name_ro'],'_',TRUE)
          );
       }
      
       
       return $crumbs;
}