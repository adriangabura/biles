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
  
    public function catalog()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/catalog',
        'text'      => 'Catalog Produse',
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
        'text'      => lang('text'),
        'separator' => ':: '
        );
     
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
        'text'      => 'Banere',
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
                
    public function news()
    {
      $breadcrumbs = $this->main_panel();
      $breadcrumbs[]  = array(
        'href'      => '/admin/news',
        'text'      => 'Noutati',
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
    
    public function doc()
    { 
      $breadcrumbs = $this->main_panel();  
      $breadcrumbs[]  = array(
        'href'      => '/admin/doc',
        'text'      => lang('doc'),
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
