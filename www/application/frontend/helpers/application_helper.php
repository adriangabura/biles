<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Load view with given name and data.
 * 
 * @param string $view
 * @param array $data
 */
function user_id()
{
    $ci = get_instance(); 
    return $ci->session->userdata('user_id');
}

function get_cur_lng(){  
    $val = file_get_contents("application/frontend/config/lang_cur.php");
    return $val;
}

function write_cur_lang($lang){
    $myfile = fopen("application/frontend/config/lang_cur.php", "w");
    fwrite($myfile, $lang);
    return TRUE;
}

function convert_valuta($val,$from,$to,$curs)
{
    $eur = $curs['eur_punct'];
    $usd = $curs['usd_punct'];
    $val_final = '';
    if($from == $to)
    {
        $val_final = $val;
    }
    else
    {    
        switch ($from) {
            case 'EUR':
                if     ($to == 'EUR') $val_final = $val;
                elseif ($to == 'USD') $val_final = number_format((($val * $eur)/$usd),0,'.','.');
                else                  $val_final = number_format($val*$eur,0,'.','.');
                break;
            case 'USD':
                if     ($to == 'USD') $val_final = $val;
                elseif ($to == 'EUR') $val_final = number_format((($val * $usd)/$eur),0,'.','.');
                else                  $val_final = number_format($val*$usd,0,'.','.');
                break;
            case 'RON':
                if     ($to == 'RON') $val_final = $val;
                elseif ($to == 'USD') $val_final = number_format($val/$usd,0,'.','.');
                else                  $val_final = number_format($val/$eur,0,'.','.');
                break;

        }
    }
    return $val_final;
}

function multi_to_one_arr($multi_array,$col = 'id')
{
    $new_array = array();
    if(isset($multi_array)):
        foreach ($multi_array AS $row) {
            $new_array[] = $row[$col];
        } 
    endif;
    return $new_array;
}
function translit($text,$lower = NULL)
{
    $cyr  = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','�?','т','�?', 
                    'ф','х','ц','ч','�?','щ','ъ', 'ы','ь', 'э', 'ю','я','�?','Б','В','Г','Д','Е','Ж','З','�?','Й','К','Л','М','Н','О','П','Р','С','Т','У',
                    'Ф','Х','Ц','Ч','Ш','Щ','Ъ', 'Ы','Ь', 'Э', 'Ю','Я' 
            );
     $lat = array( 'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
            'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a', 'i', 'y', 'e' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
            'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
            'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' 
    );
     if ($lower == 1) {
          $text_translit = strtolower(str_replace($cyr, $lat, $text));
     } else {
          $text_translit = str_replace($cyr, $lat, $text);
     } 
    return $text_translit;
}
function checked_anunt($arr,$id)
{
    if(in_array($id, $arr)) return "checked=checked";
    else return '';
}

function ac_uri2($html = NULL, $cl_name = 'active')
{
   $ci = get_instance(); 
   if($html == $ci->uri->segment(2)) return $cl_name;
}
function ac_uri1($html = NULL, $cl_name = 'active')
{
   $ci = get_instance(); 
   if($html == $ci->uri->segment(1)) return $cl_name;
}

function ac_sort($title = null)
{
    $html = '';
    if(isset($_GET['by']))
    {   
        if($_GET['by']== $title) $html = 'class=active';
    }
    $html = 'class=active';
    return $html;
 }
 
function uri_foto($by = null)
{ 
    $ci = get_instance();
    if($ci->uri->segment(1)=='cauta') $root = 'cauta/';else $root = $ci->uri->uri_string();
    if(isset($_GET['foto']))
    {   
        unset($_GET['foto']);
        $link = $root.get_cond($_GET);
    }
    else
    {       
        $link = $root.get_cond($_GET).'&foto=1';       
    }    
    return '/'.$link; 
}

function uri_ord($by = null,$ord = null,$foto = null,$judet = null)
{
    $ci = get_instance();
    if($ci->uri->segment(1)=='cauta') $root = 'cauta/';else $root = $ci->uri->uri_string();
    unset($_GET['ord']);  
    unset($_GET['judet']); 
    if($ord=='ASC')  $ord = '&ord=DESC';       
    else $ord = '&ord=ASC';       
    if(!isset($_GET['by'])) $link = $root.get_cond($_GET).$ord.'&by='.$by;       
    else
    {
        unset($_GET['by']);
        $link = $root.get_cond($_GET).$ord.'&by='.$by;
    }
    if($judet) $link = $root.get_cond($_GET).'&by='.$by.'&judet='.$judet; 
    if($foto)  $link .='&foto=1';
    return '/'.$link; 
}

function moneda($html)
{    
    switch ($html) {
        case 'EUR':           
            $r = '&euro;';
             break;
        case 'USD':           
            $r = '&#36;';
            break;
        case 'RON':            
            $r = 'RON';            
             break;
        default:
            $r = '&euro;';
            break;
    }
    return $r;
}

function mark_word($text,$word)
{
     $new_word  = '<span style="background-color:#ee463b;color:white">'.($word).'</span>';
     $html      = str_replace($word,$new_word,$text);
     return $html;
}

function get_cond($GET = NULL)
{
    $r = '?';
    if(is_array($GET))
    {
        $nr = count($GET);
        $i =0 ;
        foreach($GET as $k=>$v)
        {
            $i++;
            $r .=$k.'='.$v;
            if($i!=$nr) $r .='&';                    
        }    
    }
    
    return $r;
}

function load_view($view, $data = NULL, $return = false)
{
    $ci = get_instance();
    return $ci->load->view($view, $data, $return);
}

function is_logged()
{
    $ci = get_instance();
    if (!$ci->session->userdata('user_id')) redirect ('/main/login');
}
if( ! function_exists('read_more') ) 
{
    function read_more ($content,$limit)
    {
        $content = strip_tags($content);
        $content = explode(' ',$content,$limit);
        array_pop($content);
        array_push($content,'...');
        $content = implode(' ',$content);
        return $content;
    }
}
if( ! function_exists('trunc_text') ) 
{
    function trunc_text($text, $limit, $ellipsis = '...') 
    {
        if( strlen($text) > $limit ) 
            $text = trim(substr($text, 0, $limit)) . $ellipsis; 
        return $text;
    }
}
if( ! function_exists('rating') ) 
{
    function rating($text, $limit, $ellipsis = '...') 
    {
        
    }
}


function show_img($src_file)
{
    $placeholder_img = '/images/placeholder.png';
    if (empty($src_file)) 
    {
        return $placeholder_img;
    }
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$src_file)) 
    {
        return $src_file;
    } 
    else 
    {
        return $placeholder_img;
    }
}


   
function lower_curatire($x) {
	return strtolower(curatire($x));
}

function curatire($_hi) {
    
	$_hi = str_replace("<cr>", "[cr]", $_hi);  // temporar pana scapam de toti <cr> ramasi prin bazele de date sau continut fisiera
  $_hi = str_replace("[1]", "", $_hi);
  $_hi = str_replace("[2]", "", $_hi);
  $_hi = str_replace("[3]", "", $_hi);
  $_hi = str_replace("[/1]", "", $_hi);
  $_hi = str_replace("[/2]", "", $_hi);
  $_hi = str_replace("[/3]", "", $_hi);
  $_hi = str_replace("[lf]", "", $_hi);
  $_hi = str_replace("[cr]", " ", $_hi);
  $_hi = str_replace("[b]", " ", $_hi);
  $_hi = str_replace("[/b]", " ", $_hi);
  $_hi = str_replace("[i]", " ", $_hi);
  $_hi = str_replace("[/i]", " ", $_hi);
  $_hi = str_replace("[p]", " ", $_hi);
  $_hi = str_replace("[/p]", " ", $_hi);
  $_hi = str_replace("[g]", " ", $_hi);
  $_hi = str_replace("[/g]", " ", $_hi);
  $_hi = str_replace("[/]", " ", $_hi);
  $_hi = str_replace("[mare]", " ", $_hi);
  $_hi = str_replace("[/mare]", " ", $_hi);
  $_hi = str_replace("[mediu]", " ", $_hi);
  $_hi = str_replace("[/mediu]", " ", $_hi);
  $_hi = str_replace("[mic]", " ", $_hi);
  $_hi = str_replace("[/mic]", " ", $_hi);
  $_hi = str_replace("[copy]", " ", $_hi);
  $_hi = str_replace("[/copy]", " ", $_hi);
  $_hi = str_replace("[err]", " ", $_hi);
  $_hi = str_replace("[/err]", " ", $_hi);
  $_hi = str_replace("[asterix]", " ", $_hi);
  $_hi = str_replace("[/asterix]", " ", $_hi);
  $_hi = str_replace("[sup]", " ", $_hi);
  $_hi = str_replace("[/sup]", " ", $_hi);
  $_hi = str_replace("[sub]", " ", $_hi);
  $_hi = str_replace("[/sub]", " ", $_hi);
  $_hi = str_replace("[mic]", " ", $_hi);
  $_hi = str_replace("[/mic]", " ", $_hi);
  $_hi = str_replace("[bul]", " ", $_hi);
  $_hi = str_replace("[pct]", " ", $_hi);
  $_hi = str_replace("[star]", " ", $_hi);
  $_hi = str_replace("[tab]", " ", $_hi);
  $_hi = str_replace("[balloon]", " ", $_hi);
  $_hi = str_replace("[sticky]", " ", $_hi);
  $_hi = str_replace("[menu]", " ", $_hi);
  $_hi = str_replace("[zoom]", " ", $_hi);
  $_hi = str_replace("&#160;", " ", $_hi);
  $_hi = str_replace("&amp;#039;", "'", $_hi);
  $_hi = str_replace("   ", " ", $_hi);
  $_hi = str_replace("  ", " ", $_hi);
  $_hi = trim($_hi);
  $_hi = str_replace("||", "", $_hi);
  return strip_tags($_hi);
}

function pr ($array)
{
    echo '<pre>';
        print_r($array);
    echo '</pre>';
}

function notification()
{
    $_ci =& get_instance();
    $array = $_ci->session->flashdata('notification');
    $out = '';
    if (is_array($array))
    {
        $type = $array['type'];
        $content = $array['content'];
        $out.= "<div class=\"alert $type\">$content</div>";
        
        return  $out;
    }
} 

function send_mail($to,$from,$subject,$msg)
{
    $ci =& get_instance();
    $ci->load->library('email');
    $config['mailtype'] = 'html';
    $ci->email->initialize($config);

    if(is_array($from)) {
        $ci->email->from($from['email'], $from['title']);
    }
    
    $ci->email->to($to);
    
    $ci->email->subject($subject);
    $ci->email->message($msg);
    $ci->email->send();
}

function discount($data)
{
    return $data['price'] - ($data['price'] / 100 * $data['discount']);
}
function book_url($data)
{
    return '/carti/'.$data['id_catalog'].'/'.url_title($data['name_ro'],'_',TRUE).'html';
}

function custom_date($data)
{
    $months = lang('month');
    return date('j',  strtotime($data)).' '.$months[date('m',strtotime($data))].' '.date('Y',  strtotime($data));
}

function is_ajax()
{
    $ci =& get_instance();
    
    if ( !$ci->input->is_ajax_request() ) {
        die('Die');
    }
    
}
