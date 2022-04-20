<?php

 function Days_diff($start_data,$end_data)
 {
      $d1= explode('/',$start_data);
      $date1 = $d1[2].'-'.$d1[1].'-'.$d1['0'];
      $d2= explode('/',$end_data);
      $date2 = $d2[2].'-'.$d2[1].'-'.$d2['0'];

      $diff = abs(strtotime($date2) - strtotime($date1));
      $days = floor($diff/(60*60*24));
      return $days+1;

   }
   
 function photo($path,$class = null,$no_image=NULL)
 {  $img='';
 
    if(isset($path) and $path and file_exists($_SERVER['DOCUMENT_ROOT'].$path))  $img="<img src='$path' class='$class' />";
       else if(!is_null($no_image)) $img="<img src='$no_image' class='$class' />";
    
    return $img;
    
 } 

function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = array(
	'delimiter' => '-',
	'limit' => null,
	'lowercase' => true,
	'replacements' => array(),
	'transliterate' => true,
	);
	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
	// Latin
	'À' => 'A', 'Ă' => 'A', 'Â' => 'A', 'Â' => 'A', 'ÿ' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
	'ÿ' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
	'ÿ' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'ſ' => 'O',
	'ÿ' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
	'Ţ' => 'T', 'ţ' => 't', 'ț'=> 't',
	'ß' => 'ss',
	'à' => 'a', 'ă' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
	'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
	'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
	'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
	'ÿ' => 'y',
	 
	// Latin symbols
	'©' => '(c)',
	 
	// Greek
	'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'ο' => '8',
	'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
	'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
	'Ά' => 'A', 'ο' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
	'Ϋ' => 'Y',
	'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
	'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
	'Ͽ' => 'r', 'Ͽ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'Ͽ' => 'ps', 'ω' => 'w',
	'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
	'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ο' => 'i',
	 
	// Turkish
	'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
	'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
	 
	// Russian
	'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё?' => 'Yo', 'Ж' => 'Zh',
	'З' => 'Z', 'И?' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
	'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
	'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
	'Я' => 'Ya',
	'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
	'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
	'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
	'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
	'я' => 'ya',
	 
	// Ukrainian
	'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'ҿ' => 'G',
	'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
	 
	// Czech
	'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'ſ' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
	'Ž' => 'Z',
	'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ſ' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
	'ž' => 'z',
	 
	// Polish
	'Ą' => 'A', 'Ć' => 'C', 'Ŀ' => 'e', 'ſ' => 'L', 'ſ' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
	'Ż' => 'Z',
	'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
	'ż' => 'z',
	 
	// Latvian
	'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
	'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
	'Ŀ' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
	'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
	$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	} 
   
 function read_more($content, $limit) {

        $content = strip_tags($content);
        $content = explode(' ', $content, $limit);
        array_pop($content);
        //array_push($content, '...');
        $content = implode(' ', $content);


        return $content;
    }
/**
 * RANDOM RGB COLOR**/
/*define( COL_MIN_AVG, 64 );
define( COL_MAX_AVG, 192 );
define( COL_STEP, 16 );*/
// (192 - 64) / 16 = 8
// 8 ^ 3 = 512 colors
function random_color($type,$transparent=null) {
        $range = COL_MAX_AVG - COL_MIN_AVG;
        $factor = $range / 256;
        $offset = COL_MIN_AVG;

        $base_hash = substr(md5(rand()), 0, 6);
        $b_R = hexdec(substr($base_hash,0,2));
        $b_G = hexdec(substr($base_hash,2,2));
        $b_B = hexdec(substr($base_hash,4,2));

        $f_R = floor((floor($b_R * $factor) + $offset) / COL_STEP) * COL_STEP;
        $f_G = floor((floor($b_G * $factor) + $offset) / COL_STEP) * COL_STEP;
        $f_B = floor((floor($b_B * $factor) + $offset) / COL_STEP) * COL_STEP;
        if($type == 'hex') {
         return sprintf('#%02x%02x%02x', $f_R, $f_G, $f_B);   
        } elseif ($type == 'rgb') {
            if ($transparent == null) { 
              return "rgb({$f_R}, {$f_G}, {$f_B})";    
            }
            else {
             return "rgba({$f_R}, {$f_G}, {$f_B},{$transparent})";     
            } 
         
        }
        
           
     
}
function ore_minute($ore){
          $total =$ore*60*60;
    	  $minute=floor($total/60);
	  $hours=floor($minute/60)-1;
	  $rest=$minute - $hours*60;
          if($hours<0){ if($minute==1) $total="$minute ".lang('minuta');else $total="$minute ".lang('minute');}
	  elseif ($hours==0 and $minute=60) $total="1 ".lang('ora');
          elseif ($hours==0) $total="$minute ".lang('minute');
          elseif ($hours>0 and $minute=60) {$total=$hours+1;$total="$total ".lang('ore_mic');}
	  else
          {    
            if($hours ==1)  $total="$hours ".lang('ora')." ".lang('si')." $rest ".lang('minute');
            else  $total="$hours ".lang('ore_mic')." ".lang('is')." $rest ".lang('minute');
          }    
          return $total;
   }
   
function data_rom()
{
      $ziua = array('Duminica', 'Luni', 'Marti', 'Miercuri', 'Joi', 'Vineri', 'Sambata');
      $luna = array(NULL, 'Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulile', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie');
      return $ziua[date('w')].date(', j ').$luna[date('n')].date(' Y');    
} 
function lunile()
{    
     // $luna = array('Ian', 'Feb', 'Mart', 'April', 'Mai', 'Iun', 'Iul', 'Aug', 'Sept', 'Oct', 'Noiemb', 'Dec');
      $lunile = array("00"=>"-----------","01"=>"Ianuarie","02"=>"Februarie","03"=>"Martie","04"=>"Aprilie","05"=>"Mai","06"=>"Iunie","07"=>"Iulie","08"=>"August","09"=>"Septembrie","10"=>"Octombrie","11"=>"Noiembrie","12"=>"Decembrie");
      return $lunile;    
} 
function data_db($data,$delimeter = null)
{
   if($data!='0000-00-00')
   {    
    if($delimeter) return date('d'.$delimeter.'m'.$delimeter.'Y',strtotime($data));
    else return date('d/m/Y',strtotime($data));
   }  
}

function data_md($data)
{
   return date('d/m/Y',strtotime($data));
}

function minute($last)
 {
    $now = date('Y-m-d H:i:s');

    $diff = abs(strtotime($now) - strtotime($last));

    $years   = floor($diff / (365*60*60*24));
    $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));

    $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

    $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));
   
    return $minuts;

 }
 
 function is_multidimesional($a) {
    foreach ($a as $v) {
        if (is_array($v)) return true;
    }
    return false;
}


?>
