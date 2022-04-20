<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Breadcrumbs {

    public function main_panel()
    {
      $breadcrumbs = array();  
      $breadcrumbs[]  = array(
        'href'      => '/admin/main',
        'text'      => lang('home'),
        'separator' => FALSE
        );      
      return $breadcrumbs;
    }
    
   public function zona()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/zona',
        'text'      => lang('zona'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
     
   public function anunturi()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/anunturi',
        'text'      => lang('anunturi'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
            
   public function auto()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/auto',
        'text'      => lang('auto'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
    
    public function truck()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/truck',
        'text'      => lang('truck'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
    
     public function moto()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/moto',
        'text'      => 'Staff',
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
    
   public function obiective()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/obiective',
        'text'      => lang('obiective'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }  
        
    
    public function catalog()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/catalog',
        'text'      => 'Catalog',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    public function criteriu_cat()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/criteriu_cat',
        'text'      => lang('criteriu_cat'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
    public function rubrica()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/rubrica',
        'text'      => lang('rubrici'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
       
    public function categoria($id = null)
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/categoria/'.$id,
        'text'      => lang('categorii'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
       
       
    public function categoria_detaliu($id = null)
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '',
        'text'      => lang('det_sup'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
    
    public function criteriu($id = null)
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/criteriu_cat',
        'text'      => lang('criterii'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function raport()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/raport',
        'text'      => lang('raport'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }   
 
    public function catalog_category()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/catalog_category',
        'text'      => lang('catalog_category'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
  
    public function produse()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/produse',
        'text'      => lang('produse'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    

      
    public function promotii()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/promotii',
        'text'      => lang('promotii'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
  
    
    public function produse_options()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/produse_options',
        'text'      => lang('produse'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }   
    
    public function scheme()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/category',
        'text'      => lang('scheme'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
      
    
    public function events()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/events',
        'text'      => lang('events'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
      
    
    public function blog()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/blog',
        'text'      => lang('blog'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
     
  
    public function text()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/text',
        'text'      => 'Info',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }   
     public function offers()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/offers',
        'text'      => 'Testimonials',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }   
    public function proiecte()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/proiecte',
        'text'      => lang('proiecte'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }  
    public function portfolio()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/portfolio',
        'text'      => 'Portofoliu',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
    public function gallery()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/gallery',
        'text'      => lang('gallery'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
    
    public function slider()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/slider',
        'text'      => 'Slider',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
 
    public function banners()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/banners',
        'text'      => 'Parteneri',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
    
     public function social()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/social',
        'text'      => 'Retele Sociale',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
    
     public function news()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/news',
        'text'      => lang('news'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
 
    public function baner()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/baner',
        'text'      => lang('baner'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }     
    
    public function partners()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/partners',
        'text'      => 'Parteneri',
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
                
   
            
    public function faq()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/faq',
        'text'      => 'FAQ',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }   
    
    
    public function services()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/services',
        'text'      => 'Servicii Header',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
    
    public function acces_denied()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/acces_denied',
        'text'      => lang('acces_denied'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function email_text()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/email_text',
        'text'      => lang('email_text'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }

    

    public function sfaturi()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/sfaturi',
        'text'      => 'Sfaturi',
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 
    public function command()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/command',
        'text'      => 'Comenzi',
        'separator' => ':: '
        );
      return $breadcrumbs;
    } 

    public function video()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/video',
        'text'      => 'Video Home',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    
    
    public function price()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/price',
        'text'      => 'Preturi',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    public function langs()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/langs',
        'text'      => lang('langs'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }

    public function projects()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/projects',
        'text'      => 'Portfoliu',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }

    
    public function comments()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/comments',
        'text'      => 'Comentarii',
        'separator' => ':: '
        );
      return $breadcrumbs;
    }

    public function rev()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/rev',
        'text'      => lang('rev'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function offerts()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/offerts',
        'text'      => lang('offerts'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function orders()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/orders',
        'text'      => lang('orders'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function orders_detail()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/orders',
        'text'      => lang('orders'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function logs()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/logs',
        'text'      => lang('logs'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
   public function vars()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/vars',
        'text'      => lang('vars'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function snippets()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/snippets',
        'text'      => "Snippets",
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function settings()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/settings',
        'text'      => lang('settings'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    
    public function messages()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/messages',
        'text'      => lang('messages'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    
    public function category()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/category',
        'text'      => lang('categories'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function doc_edit()
    { 
      $breadcrumbs = $this->main_panel();  
      $breadcrumbs[]  = array(
        'href'      => '/admin/doc_edit',
        'text'      => lang('doc_edit'),
        'separator' => ':: ' 
        );
      return $breadcrumbs;
    }    
    
    public function doc_add()
    { 
      $breadcrumbs = $this->main_panel();  
      $breadcrumbs[]  = array(
        'href'      => '/admin/doc_add',
        'text'      => lang('doc_add'),
        'separator' => ':: ' 
        );
      return $breadcrumbs;
    }
        
    public function pages()
    { 
      $breadcrumbs = $this->main_panel();  
      $breadcrumbs[]  = array(
        'href'      => '/admin/pages',
        'text'      => lang('menu'),
        'separator' => ':: ' 
        );
      return $breadcrumbs;
    }
    
    public function products()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/products',
        'text'      => lang('products'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function terms($id)
    {
      $breadcrumbs = $this->category();
      $breadcrumbs[]  = array(
        'href'      => '/admin/terms/'.$id,
        'text'      => lang('terms'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    public function users()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/users',
        'text'      => lang('users'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
    
    
    public function users_site()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/users_site',
        'text'      => lang('users_site'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }    

    public function abonati()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/abonati',
        'text'      => lang('abonati'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }

    public function users_group()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/users_group',
        'text'      => lang('users_group'),
        'separator' => ':: '
        );
      return $breadcrumbs;
    }
}
?>
