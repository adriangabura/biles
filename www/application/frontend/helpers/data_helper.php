<?php

 function Days_diff($start_data,$end_data){
      $d1= explode('/',$start_data);
      $date1 = $d1[2].'-'.$d1[1].'-'.$d1['0'];
      $d2= explode('/',$end_data);
      $date2 = $d2[2].'-'.$d2[1].'-'.$d2['0'];

      $diff = abs(strtotime($date2) - strtotime($date1));
      $days = floor($diff/(60*60*24));
      return $days+1;

   }
   
 function photo($path,$class = null,$no_image=NULL)
 {  $img='<img src="/images/def_photo.jpg" width="86" height="60">';
 
    if(isset($path) and $path and file_exists($_SERVER['DOCUMENT_ROOT'].$path))  $img="<img src='$path' class='$class' />";
       else if(!is_null($no_image)) $img="<img src='$no_image' class='$class' />";
    
    return $img;
    
 }   
   
 function read_more($content, $limit) {

        //$content = strip_tags($content);
        $content = explode(' ', $content, $limit);
        array_pop($content);
        //array_push($content, '...');
        $content = implode(' ', $content);
		$content = $content.'  [...]</p>';

        return $content;
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
function in_multiarray($elem, $array)
    {
        if (is_array($array)) {
            foreach ($array as $key =>$val) {
                foreach ($val as $it) {
                    if ($it == $elem) {
                        return $key;
                    } 
                }
            }
        } 
    }

?>
