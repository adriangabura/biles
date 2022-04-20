<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


class Display { 


 
    public function frontend($name,$data)
    {
      $CI = &get_instance(); 
      $CI->load->model('shop_model');
      $data['vars'] = $CI->mysql->get_All('vars');
      $data['lngs'] = $CI->mysql->get_All('langs',array('status'=>1),'','','ord','asc');
      $data['lng'] = $CI->lng;
        
      $data['uri2'] = $CI->uri->segment(2);
      $data['uri1'] = $CI->uri->segment(1);
      
      $data['menu']=$CI->display->menu($CI->mysql->get_All('pages',array('parent'=>0),'','','ord','asc'),0,0,$CI->lng);  
 
      /* setari generale */  
      $settings = $CI->mysql->get_row('settings',array('id'=>1));
      $data['title_general'] = $settings['title_'.$CI->lng];
      $data['keywords_general'] = $settings['keywords_'.$CI->lng];
      $data['description_general'] = $settings['description_'.$CI->lng];
      
      $user_data = array();
	  
	  
	  
      if ($CI->session->userdata('user_id')) {
          $user_data = $CI->mysql->get_row('users',array('id'=>$CI->session->userdata('user_id')));
          
          $wishlist = $this->mysql->get_all('wishlist_category',array('user_id'=>  $this->session->userdata('user_id')));
          foreach ($wishlist as $key=>$value) {
            $wishlist[$key]['produse'] = $this->mysql->num_rows('user_wishlist',array('wishlist_id'=>$value['id']));
          }
        
          $data['wishlist'] = $wishlist;
      }
      
      $data['user_data'] = $user_data;
      
	  
      
      
      $CI->load->view('frontend/header',$data);      
      $CI->load->view('frontend/'.$name,$data);
      $CI->load->view('frontend/footer',$data);
    }
    
    
 
    
  /**
     * Menu
     * Afiseaza meniul principal
     * @var $array  (array)
     * @var $parent (integer)
     * @var $level  (integer)
     * @var $lng    (string)
     * @var $html   (string)
     * @return string 
     */
    var  $childrens= NULL;
      
       
    function menu($array,$parent,$level = 0,$lng,$html = null ,$type = null,$curent_db='') 
    {
        $CI = &get_instance();
        $CI -> load ->helper('other_helper');   
        
        if ($parent == 0)
        {    
           if($type=='bottom') $html.="<ul class='bottom_menu'>";
           elseif($type=='left_menu') $html.="<ul class='left_menu'>";
           else  $html.="<ul class=menu-list >";
        }    
        else $html.="<ul>";        
       
        
        
       // left menu 
       if($type=='left_menu') $table = 'pages';else $table = 'pages';
        
       //reducere interogari p/u childrens
       if(is_null($this->childrens)) 
       {$childs = $CI->mysql->get_ALL($table,'','','','ord','parent asc');
        $par=0;
        foreach ( $childs as $child)
        {     
            if ($par!=$child['parent']) $par=$child['parent'];
           $this->childrens[$par][]=$child;  
            
        }
       }
       
       //     
       foreach ($array AS $item) 
       {
           if ($level == 1) {
               continue;
           }
            if ($parent == $item['parent']) 
            {
             
             if($type=='left_menu') $link=$this->create_links_left_menu($item,$table,$lng); else $link=$this->create_links($item,$lng);
             
             $curent=$CI->mysql->get_row($table,array('url'=>(str_replace('.html','',$CI->uri->segment(2)))));
             
             if($item['id_'.$table]==1 and $CI->uri->segment(1)=='') $cl ='class=active';
             elseif($curent and ($curent['id_'.$table] == $item['id_'.$table])) $cl ='class=active';
             else $cl = '';
             
             $html.= "<li  $cl ><a $cl alt='" . $item['name_'.$lng] . "'  href='$link'  > " .($item['name_'.$lng]) . "</a>";
           
             $children = $CI->mysql->get_ALL($table,array('parent'=>$item['id_'.$table]),'','','id_'.$table,'asc');           
             if ($children and $item['parent']==0 and $type!='bottom')
                    $html .= $this->menu($children,$item['id_'.$table],$level+1,$lng,'',$type);
                else $html.="</li>";
            }
       }
       $html.="</ul>";        
       return $html;
    }
    
    
  public function create_links($item,$lng)
    {           
        $CI = &get_instance(); 
        $link2 ='';$link ='';
        
        switch ($item['id_pages'])        
        {
            case 1: $link2 = "/";                                                    
                      break;   
                           
            case 10: $link2 = "/contacte/".($item['url']).'.html';                                                
                      break;                
                         
           
                 
                  
            default: $link2 = "/ro/pages/".($item['url']).'.html';              
        } 
        if($link2) $link = $link2;       
        if(empty($item["name_$lng"])) $link='/';
        //else $link = "/pages/".($item['name_'.$lng]);
            
        if(empty($item["name_$lng"])) $link='/';
        return $link;        
    }    
    

    
        
    
    public function backend($name,$data)
    {
      $CI = &get_instance();  
      $data['user_name'] = $CI->session->userdata('user_name'); 
      $settings = $CI->mysql->get_row('settings',array('id'=>1));
      $data['company'] = $settings['company'];
      $data['per_page'] = $settings['per_page_admin']; 
      $data['operator'] = $CI->session->userdata('operator');
	  
      //$data['errors'] = $CI->session->flashdata('error');
      //$data['succes'] = $CI->session->flashdata('succes');
      
      $CI->load->view('header',$data);      
      $CI->load->view(''.$name,$data);
      $CI->load->view('footer',$data);
    }
       
   
    public function crumbs_product_list($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > >".$row['name_ro']."</span>";
       $child = $CI->mysql->get_row('catalog',array('id_catalog'=>$row['parent']));
       if($child) $html .= $this->crumbs_product_list($child,$level+1);
       return $html;
     }
    }        
   
    public function crumbs_portfoliu($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/portfoliu/$row[id_portfoliu]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('portfoliu',array('id_portfoliu'=>$row['parent']));
       if($child) $html .= $this->crumbs_portfoliu($child,$level+1);
       return $html;
     }
    }     
    
   
    public function crumbs_portfolio($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/portfolio/$row[id_portfolio]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('portfolio',array('id_portfolio'=>$row['parent']));
       if($child) $html .= $this->crumbs_portfolio($child,$level+1);
       return $html;
     }
    }         
  
  
       
   
    public function crumbs_catalog_category($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/catalog_category/$row[id]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('catalog_category',array('id'=>$row['parent']));
       if($child) $html .= $this->crumbs_catalog_category($child,$level+1);
       return $html;
     }
    }        
   
   
    public function crumbs_raport($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/raport/$row[id_raport]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('raport',array('id_raport'=>$row['parent']));
       if($child) $html .= $this->crumbs_raport($child,$level+1);
       return $html;
     }
    }        
    
    public function crumbs_category($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/category/$row[id_category]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('category',array('id_category'=>$row['parent']));
       if($child) $html .= $this->crumbs_category($child,$level+1);
       return $html;
     }
    }    
   
    public function crumbs_blog($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/blog/$row[id_blog]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('blog',array('id_blog'=>$row['parent']));
       if($child) $html .= $this->crumbs_blog($child,$level+1);
       return $html;
     }
    }     
    
      
    public function crumbs_events($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/events/$row[id_events]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('events',array('id_events'=>$row['parent']));
       if($child) $html .= $this->crumbs_events($child,$level+1);
       return $html;
     }
    }  
    
    
      
    public function crumbs_events_admin($parent,$html = null)
    {
     if($parent)
     {
       $CI = &get_instance();
       $locatia = $CI->mysql->get_row('scheme',array('id_scheme'=>$parent));
       
       $html .="<span  style='float:right;padding-left:5px;' > :: ".$locatia['name_ro']."</span>";
       
       $categ = $CI->mysql->get_row('category',array('id_category'=>$locatia['id_category']));
       
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/category/$categ[id_category]' >".$categ['name_ro']."</a></span>";
       return $html;
     }
    }  
       
   
    public function crumbs_scheme($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/scheme/$row[id_scheme]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('scheme',array('id_scheme'=>$row['parent']));
      // if($child) $html .= $this->crumbs_scheme($child,$level+1);
       return $html;
     }
    }     
        
   
    public function crumbs_text($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/text/$row[id_text]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('text',array('id_text'=>$row['parent']));
       if($child) $html .= $this->crumbs_text($child,$level+1);
       return $html;
     }
    }       
   
    public function crumbs_proiecte($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/proiecte/$row[id_proiecte]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('proiecte',array('id_proiecte'=>$row['parent']));
       if($child) $html .= $this->crumbs_proiecte($child,$level+1);
       return $html;
     }
    }      
    public function crumbs_gallery($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/gallery/$row[id_gallery]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('gallery',array('id_gallery'=>$row['parent']));
       if($child) $html .= $this->crumbs_gallery($child,$level+1);
       return $html;
     }
    }        
     
   public function crumbs_catalog($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['name_ru']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/catalog/$row[id]' >".$row['name_ru']."</a></span>";
       
       $child = $CI->mysql->get_row('catalog',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_catalog($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    } 
    
     public function crumbs_obiective($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/obiective_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('obiective',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_obiective($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    }  
     
   public function crumbs_auto($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/auto_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('auto',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_auto($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    } 
      
    public function crumbs_truck($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/auto_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('truck',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_truck($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    } 
    
      public function crumbs_moto($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/auto_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('moto',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_moto($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    } 
    
    
   public function crumbs_anunturi($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/anunturi_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('anunturi',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_anunturi($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    } 
        
    
   public function crumbs_zona($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
        if($level==null) $html .="<span  style='float:right;padding-left:5px;' > :: <a href='' >".$row['title_ro']."</a></span>";
        else $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/zona_category/$row[id]' >".$row['title_ro']."</a></span>";
       
       $child = $CI->mysql->get_row('zona',array('id'=>$row['parent']));
       $level++;
     
       if($child) $html .= $this->crumbs_zona($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    }     
    
  public function crumbs_promotii($row,$table,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/promotii/".$row['id_'.$table]." >".$row['name_ro']."</a></span>";
       
       // $child = $CI->mysql->get_row($table,array('id'.$table=>$row['parent']));
       
       $child = $CI->mysql->get_row($table,array('id_'.$table=>$row['category_id']));
       
       if($child) $html .= $this->crumbs_promotii($child,$table,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    }     
    
        
   public function crumbs_produse($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/produse/$row[id]' >".$row['name_scientific']."</a></span>";
       
       $child = $CI->mysql->get_row('produse',array('id'=>$row['parent']));
       
       if($child) $html .= $this->crumbs_produse($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    }  
    
    
        
   public function crumbs_produse_options($row,$level=0,$html = null)
    {
     if($row)
     {
       // cu doua nivele
       $CI = &get_instance();
       
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/produse/$row[id]' >".$row['code']."</a></span>";
       
       $child = $CI->mysql->get_row('produse',array('id'=>$row['produs_id']));
       
       if($child) $html .= $this->crumbs_produse($child,$level);
       
      // echo $level;
       
       
       return $html;         
     }
    }    
    
    public function crumbs_banners($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/banners/$row[id_banners]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('banners',array('id_banners'=>$row['parent']));
       if($child) $html .= $this->crumbs_banners($child,$level+1);
       return $html;
     }
    }      
    
    
     public function crumbs_social($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/social/$row[id_social]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('social',array('id_social'=>$row['parent']));
       if($child) $html .= $this->crumbs_social($child,$level+1);
       return $html;
     }
    }      
    
    
    public function crumbs_baner($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/baner/$row[id]' >".$row['title_ro']."</a></span>";
       $child = $CI->mysql->get_row('baner',array('id'=>$row['parent']));
       if($child) $html .= $this->crumbs_baner($child,$level+1);
       return $html;
     }
    }      
        
        
    
    public function crumbs_slider($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/slider/$row[id_slider]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('slider',array('id_slider'=>$row['parent']));
       if($child) $html .= $this->crumbs_slider($child,$level+1);
       return $html;
     }
    }      
    
    
    public function crumbs_partners($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/partners/$row[id_partners]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('partners',array('id_partners'=>$row['parent']));
       if($child) $html .= $this->crumbs_partners($child,$level+1);
       return $html;
     }
    }      
    
    public function crumbs_faq($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/faq/$row[id_faq]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('faq',array('id_faq'=>$row['parent']));
       if($child) $html .= $this->crumbs_faq($child,$level+1);
       return $html;
     }
    }    
    
    public function crumbs_services($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/services/$row[id_services]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('services',array('id_services'=>$row['parent']));
       if($child) $html .= $this->crumbs_services($child,$level+1);
       return $html;
     }
    }    
    
    public function crumbs_news($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/news/$row[id_news]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('news',array('id_news'=>$row['parent']));
       if($child) $html .= $this->crumbs_news($child,$level+1);
       return $html;
     }
    }
    public function crumbs_offerts($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/offerts/$row[id_offerts]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('offerts',array('id_offerts'=>$row['parent']));
       if($child) $html .= $this->crumbs_offerts($child,$level+1);
       return $html;
     }
    }
 
    public function crumbs_command($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/command/$row[id_command]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('command',array('id_command'=>$row['parent']));
       if($child) $html .= $this->crumbs_command($child,$level+1);
       return $html;
     }
    }
 
    public function left_menu3($table,$lng, $id = null,$html = NULL)
    {
        $CI = &get_instance();
        $left_menu = $CI->mysql->get_ALL($table,array('parent'=>0),'','','id_'.$table,'asc');
        $html.="<ul class='left_menu_list' >";
        foreach($left_menu as $val)
        { 
            if($val['id_products']==$id) {$on = 'on';$style = "";}else {$on = '';$style = "style=display:none";}            
            $html .= '<li class="li_left_menu_list '.$on.'" ><a href="/product_list/'.$val['id_products'].'" class=a_left_menu_list >'.$val['name_'.$lng].'</a>';
            $sub_menu = $CI->mysql->get_All('products',array('parent'=>$val['id_products']));
            if($sub_menu) $html .= $this->menu_one_product($sub_menu,0,0,$lng,'',$style);
            $html .= '</li>
             <div class=clear></div>    
                ';
        }    
        $html.="<ul>";
        return $html;
    }        
    
    public function menu_one_product($array, $parent, $level = 0, $lang, $html = NULL,$style = NULL)
    {
        $ci =& get_instance(); 
        if ($level == 0) {
            $html = "<ul class='menu-left sf-vertical' $style >";
        } else {
            $html = '<ul>';
        }
        
        foreach ($array as $item) { 
            $children = $ci->mysql->get_ALL('products',array('parent'=>$item['id_products']),'','','id_products','asc');
            $html .= '<li class=""><a href="">'.$item['name_'.$lang].'</a>';
                if (!empty($children)) {
                    $html .= $this->menu_one_product($children,$item['id_products'],$level+1,$lang);
                }    
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }
    function left_menu2($array,$parent,$level = 0,$lng,$html = null) 
    {
        /*$CI = &get_instance();
        $html = $CI->load->view('left_menu',array(),TRUE);
        return $html; */
        $CI = &get_instance();
        if ($parent == 0)
            $html.="<ul id=left_menu class='sf-vertical' >";
        else
            $html.="<ul>";
        foreach ($array AS $item) {
            if ($parent == $item['parent']) {
            // link
               switch ($item['id_products'])
                {
                   case 1:
                        $link = '/';
                        break;
                   case 2:
                        $link = "/portfolio/".$item['name_'.$lng];
                        break;
                   case 3:
                        $link = "/about/".$item['name_'.$lng];
                        break;
                   case 4:
                        $link = "/contact/".$item['name_'.$lng];
                        break;
                    default: $link = "/";
                 }
            // end link
            //clasa curenta
          
            $lngs = $CI->mysql->get_All('langs');
            foreach($lngs as $it)
            {    
              $curent_db=$CI->mysql->get_row('products',array('name_'.$it['ext']=>urldecode($CI->uri->segment(2))));
              if($curent_db) break; 
            }
           

//              $curent_db =$CI->mysql->get_row('pages',array('name_'.$lng=>urldecode($CI->uri->segment(2))));

                if($curent_db and $curent_db['parent']>0) $curent = $CI->mysql->get_row('products',array('id_products'=>$curent_db['parent']));
                else $curent = $curent_db;

                if($curent and $curent['parent']>0) $curent = $CI->mysql->get_row('products',array('id_products'=>$curent['parent']));
                else $curent = $curent;

                if($curent and ($curent['id_products'] == $item['id_products'])) $cl ='class=active';
                elseif($CI->uri->segment(2)=='' and $item['id_products']==1)  $cl ='class=active';
                else $cl = '';
                
          //end clasa curenta
                $children = $CI->mysql->get_ALL('products',array('parent'=>$item['id_products'],'id_products !='=>20),'','','id_products','asc');

                //contacte 
                if($item['id_products']== 3) $style='style=""';else $style='';
                
                $html.= "<li><a $cl $style alt='" . $item['name_'.$lng] . "'  href='$link'  >" . $item['name_'.$lng] . "</a>";
                if ($children )
                    $html .= $this->left_menu($children,$item['id_products'],$level+1,$lng);
                else
                    $html.="</li>";
            }
        }
        $html.="</ul>";
        
        return $html; 
        
    }

    
     public function breadcrumbs($row,$lng,$level=0,$html = null)
     {
      if($row)
      {
            // link
               switch ($row['id_pages'])
                {
                   case 1:
                        $link = '/';
                        break;
                   case 2:
                        $link = "/pages_full/".$row['name_'.$lng];
                        break;
                   case 3:
                        $link = "/pages_servicii/".$row['name_'.$lng];
                        break;
                   case 4:
                        $link = "/pages_short/".$row['name_'.$lng];
                        break;
                   case 5:
                        $link = "/pages_detail/".$row['name_'.$lng];
                        break;
                   case 7:
                        $link = "/faq/".$row['name_'.$lng];
                        break;
                   case 8:
                        $link = "/contact/".$row['name_'.$lng];
                        break;
                    default: $link = "/pages_detail/".$row['name_'.$lng];
                 }

               switch ($row['parent'])
                {                   
                    case 2:
                        $link = "/pages_full/".$row['parent'].'#page'.$row['id_pages'];
                        break;
                    case 3:
                        $link = "/pages_servicii_select/".$row['name_'.$lng];
                        break;
                    case 14:
                        $link = "/servicii_detail/".$row['name_'.$lng];
                        break;
                    case 15:
                        $link = "/servicii_detail/".$row['name_'.$lng];
                        break;
                   // default: $link = "/pages_full/".$item['name_'.$lng];
                 }
            // end link
       $CI = &get_instance();
       $html .="<span  style='padding-left:5px;float:right' >&nbsp;>>&nbsp;<a href='$link' >".$row['name_'.$lng]."</a></span>";
       $child = $CI->mysql->get_row('pages',array('id_pages'=>$row['parent']));
       if($child) $html .= $this->breadcrumbs($child,$lng,$level+1);

       return $html;
      }
     }

     function up_menu2($parent,$array,$html = null) {
         $CI = &get_instance();
        if ($parent == 0)
            $html.="<ul id='nav'>";
        else
            $html.="<ul>";
        foreach ($array AS $item) {
            if ($parent == $item['parent']) {
                $hasChld = $CI->mysql->get_ALL('pages',array('parent'=>$item['id_pages']),'','','id_pages','asc');

                $html.= "<li><a class='clickMe' alt='" . $item['id_pages'] . "'  href='javascript:void(0)'>" . $item['name_ro'] . "</a>";
                if ($hasChld)
                    $html .= $this->up_menu($item['id_pages'], $hasChld);
                else
                    $html.="</li>";
            }
        }

        if ($parent == 0)
            $html.="</ul>";
        else
            $html.="<li class='additem'><a href='javascript:void(0)' class='newitem' alt='" . $parent . "'>
                            <img src='images/add_item.png'/><span>Adaugare element</span>
                            </a></li></ul>";

        return $html;
    }

    

    
  public function crumbs_sfaturi($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/pages/$row[id_sfaturi]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('sfaturi',array('id_sfaturi'=>$row['parent']));
       if($child) $html .= $this->crumbs_pages($child,$level+1);

       return $html;
     }
    }
        
    
  public function crumbs_price($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/pages/$row[id_price]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('price',array('id_price'=>$row['parent']));
       if($child) $html .= $this->crumbs_pages($child,$level+1);

       return $html;
     }
    }
    
    
  public function crumbs_video($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/pages/$row[id_video]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('video',array('id_video'=>$row['parent']));
       if($child) $html .= $this->crumbs_pages($child,$level+1);

       return $html;
     }
    }

        
    
  public function crumbs_rev($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/pages/$row[id_rev]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('rev',array('id_rev'=>$row['parent']));
       if($child) $html .= $this->crumbs_pages($child,$level+1);

       return $html;
     }
    }

    

  public function crumbs_products($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/products/$row[id_products]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('products',array('id_products'=>$row['parent']));
       if($child) $html .= $this->crumbs_products($child,$level+1);

       return $html;
     }
    }



  public function crumbs_doc_edit($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/doc_edit/$row[id_doc_edit]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('doc_edit',array('id_doc_edit'=>$row['parent']));
       if($child) $html .= $this->crumbs_doc_edit($child,$level+1);

       return $html;
     }
    }

    
  public function crumbs_doc_add($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/doc_add/$row[id_doc_add]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('doc_add',array('id_doc_add'=>$row['parent']));
       if($child) $html .= $this->crumbs_doc_add($child,$level+1);

       return $html;
     }
    }

  public function crumbs_pages($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/pages/$row[id_pages]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('pages',array('id_pages'=>$row['parent']));
       if($child) $html .= $this->crumbs_pages($child,$level+1);

       return $html;
     }
    }

  public function crumbs_categoria_detaliu($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/categoria_detaliu/$row[id]' >".$row['title_ro']."</a></span>";
       $child = '';
       if($child) $html .= $this->crumbs_categoria_detaliu($child,$level+1);

       return $html;
     }
    }
    
    public function crumbs_projects($row,$level=0,$html = null)
    {
     if($row)
     {
       $CI = &get_instance();
       $html .="<span  style='float:right;padding-left:5px;' > :: <a href='/admin/projects/$row[id_projects]' >".$row['name_ro']."</a></span>";
       $child = $CI->mysql->get_row('projects',array('id_projects'=>$row['parent']));
       if($child) $html .= $this->crumbs_projects($child,$level+1);

       return $html;
     }
    }

/*** old **/
    public function cosul_sess($gol = null)
    {
        $CI = &get_instance(); 
        if($CI->session->userdata('product_select'))
        {  
             $product_select = json_decode($CI->session->userdata('product_select'));
             $suma = $CI->mysql->get_suma_products_cart($product_select);
             $nr = count($product_select) ;
             $html='Suprafete selectate: '.$nr.'
                    <p>Suma:<strong> '.$suma.' &euro;</strong></p>
                    <a href="/cart/" class="featurelink">Treci in cos</a>
                    <p></p>
                    &nbsp;                 
             ';            
        } else $html = $gol;
        
        return $html;
    }
    
    public function cosul_db($gol = null)
    {
        $CI = &get_instance(); 
        $product_select = $CI->mysql->get_cart_user($CI->session->userdata('uid'));
        if($product_select)
        {  
             $product_select = $CI->mysql->get_cart_user($CI->session->userdata('uid'));
             $suma = $CI->mysql->get_suma_cart_user($CI->session->userdata('uid'));
             $nr = count(json_decode($product_select)) ;
             $html='Suprafete selectate: '.$nr.'
                    <p>Suma:<strong> '.$suma.' &euro;</strong></p>
                    <a href="/cart/" class="featurelink">Treci in cos</a>
                    <p></p>
                    &nbsp;                 
             ';            
        } else $html = $gol;
        
        return $html;
    }
    
    public function welcom_user($welcom,$exit)
    {
        $CI = &get_instance(); 
        if($CI->session->userdata('uid'))
        {   
         $html='<div id="welcome" class="fix">
                <div class="welcometext">
                    <h3 class="greeting">'.$CI->session->userdata('user_name').'</h3>
                    <div class="welcomemessage">'.$welcom.'<br/>
                        <a href="/users/logout" class="featurelink">'.$exit.'</a>
                    </div>
                </div>
            </div>
         ';
         return $html;
        } 
    }
    
    public function users_list()
    {
      $CI = &get_instance();
      $CI->load->model('mysql_model');
      $content ="<select id=responsabil style='height:30px;width:150px' >";
      $user_type = $CI->mysql_model->get_All('users');
      foreach($user_type as $row)
         {
           $content .="<option value=".$row['id']." >".$row['nume']."</option>";
         }               
      $content .="</select>";
      return $content;      
    }

    public function weight($nr=null)
    {      
      $content ="<select name=weight  >";      
      for($i=-5;$i<=5;$i++)
         {
           $content .="<option value=".$i." ";
           if($nr==$i) $content .="selected=selected ";
           $content .=" >".$i."</option>";
         }
      $content .="</select>";
      return $content;
    }


    public function status_opt($status=null)
    {
      $content ="<select name=status >";
       
       $content .="<option value=1 ";       
       if($status=='1' or empty($status)) $content .="selected=selected ";
       $content .=" >".lang('enable')."</option>";
     
       $content .="<option value=0 ";       
       if($status=='0') $content .="selected=selected ";
       $content .=" >".lang('disable')."</option>";
       
      $content .="</select>";
      return $content;
    }

    public function statut_comanda(){
      $CI = &get_instance();
      $CI->load->model('mysql_model');
      $content ="<select id=responsabil style='height:30px;width:150px' >";
      $user_type = $CI->mysql_model->get_All('users');
      foreach($user_type as $row)
         {
           $content .="<option value=".$row['id']." >".$row['nume']."</option>";
         }               
      $content .="</select>";
      return $content;      
    }
    
    public function option_tag($table,$id = null)
    {
      $CI = &get_instance();
      $CI->load->model('mysql_model');
      $content ="<select name=id_$table  id=id_$table  class=select1 style='width:200px' >";
      
      if($table=='art_cheltuieli') $arr = $CI->mysql_model->get_All($table,array('id_tip_cheltuieli'=>1));
      else $arr = $CI->mysql_model->get_All($table);
      foreach($arr as $row)
         {
           $content .="<option value=".$row['id_'.$table]." ";
           
           if(isset($id) and $id==$row['id_'.$table]) $content .="selected=selected ";
           $content .=" >".$row['nume_'.$table];
           
           if ($table=='users') $content .=" ".$row['prenume'];
           
           
           $content .=" </option>";
         }
      $content .="</select>";
      return $content;
    }

    public function terms_parent($result,$parent,$level,$parent_term,$html = null)
    {
       if($parent == 0)  $html .="<select name=term[1][parent_id] class=parinte onchange=\"auto_change_parent($(this).val())\"
           id=parent ><option value=0>---------------</option>";
       $CI = &get_instance();  
       foreach ($result as $item) 
       {
         if($parent == $item['parent_id'])  
         {    
           $html .="<option value=$item[tid] ";
           if($parent_term == $item['tid']) $html .=" selected=selected ";
           $html .=" >".str_repeat('&nbsp; |- &nbsp;',$level).$item['name_ro']."</option>";
           $child = $CI->mysql->get_ALL('taxonomy_term_data',array('parent_id'=>$item['tid']),'','','weight','asc'); 
           if($child) $html .= $this->terms_parent($child,$item['tid'],$level+1,$parent_term);
         }  
       }
       if($parent == 0)  $html .='</select>';
       return $html; 
    }    

    public function terms_parent2($result,$parent,$level,$parent_term,$html = null)
    {
       if($parent == 0)  $html .="<select name=filtru[format] class=parinte onchange=\"auto_change_parent($(this).val())\"
           id=parent ><option value=0>---------------</option>";
       $CI = &get_instance();  
       foreach ($result as $item) 
       {
         if($parent == $item['parent_id'])  
         {    
           $html .="<option value=$item[tid] ";
           if($parent_term == $item['tid']) $html .=" selected=selected ";
           $html .=" >".str_repeat('&nbsp; |- &nbsp;',$level).$item['name_ro']."</option>";
           $child = $CI->mysql->get_ALL('taxonomy_term_data',array('parent_id'=>$item['tid']),'','','weight','asc'); 
           if($child) $html .= $this->terms_parent($child,$item['tid'],$level+1,$parent_term);
         }  
       }
       if($parent == 0)  $html .='</select>';
       return $html; 
    }    
    
    public function product_terms($category,$pid)
    {
       $html = ''; 
       $CI = &get_instance();  
       foreach ($category as $item) 
       {
       $html.="<tr>
                 <td><span class=required>*</span> $item[name_ro]:</td>
                 <td>";  
       
       $terms = $CI->mysql->get_ALL('taxonomy_term_data',array('vid_id'=>$item['vid']),'','','weight','asc'); 
       
       $html .=$this->terms_parent_product($terms,0,0,$pid);
       
       
       $html.="  </td>
               </tr>  ";  

       }       
       return $html; 
    }    

    public function terms_parent_product($result,$parent,$level,$pid,$html = null)
    {
       $CI = &get_instance();   
       $array_product_term = $CI->catalog_model->product_category($pid); 
       
       if($parent == 0)  $html .="<select name=term_product[] style='width:500px' >";
       
       foreach ($result as $item) 
       {
         if($parent == $item['parent_id'])  
         {    
           $html .="<option value=$item[tid] ";
           
           if(in_array($item['tid'],$array_product_term)) $html .=" selected=selected ";           
           
           $html .=" >".str_repeat('&nbsp; |- &nbsp;',$level).$item['name_ro']."</option>";           
           
           $child = $CI->mysql->get_ALL('taxonomy_term_data',array('parent_id'=>$item['tid']),'','','weight','asc'); 
           
           if($child) $html .= $this->terms_parent_product($child,$item['tid'],$level+1,$pid);
         }  
       }
       if($parent == 0)  $html .='</select>';
       
       return $html; 
    }    
    

    public function crumbs_pages2($row)
    {  
       $result = explode('::',$this->crumbs_pages_invers($row));        
       $array_invers = array_reverse($result);
       $nr = count($array_invers);       
       //$nr--;
       $result = '';
       foreach($array_invers as $key=>$value)
       {
          //if($key == 1) $result .= str_replace ('::','zz',$value);
          $result .='::'. $value;
         // else $result .= $value;
         // $result .='-'.$key;
       }   
       return $result; 
    }    

  
    
    public function terms($terms,$parent,$level,$base_lng,$html=null)
    {
      if ($terms)
      { 
       $CI = &get_instance();     
       foreach ($terms as $item) 
       {
         if($parent == $item['parent_id'])  
         {      
             $html .= "<tr>
                <td style='text-align: center;'>         
                  <input type=checkbox name='selected[]' value=$item[tid]  />
                </td>
                <td class=left ><a href='/admin/terms_form/$item[tid]/$item[vid_id]' class=none >";
             
               $html .=str_repeat('&nbsp; |- &nbsp;',$level).$item['name_'.$base_lng]."</a></td>           
                <td class=center >
                    [<a href='/admin/terms_form/$item[tid]/$item[vid_id]' >".lang('edit')."</a>]
                </td>
              </tr>";             
             
             $child = $CI->mysql->get_ALL('taxonomy_term_data',array('parent_id'=>$item['tid']),'','','weight','asc'); 
             if($child) $html .= $this->terms($child,$item['tid'],$level+1,$base_lng);
          }         
       }   
      }
      else $html .="<tr><td class=center colspan=4 >".lang('no_categories')."</td></tr>";   
      return $html; 
     }
     
     
     
}     
?>
