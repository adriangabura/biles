<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function recursive_array_search($needle,$haystack) 
{
    foreach($haystack as $key=>$value) 
    {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) 
        {
            return $current_key;
        }
    }
    return false;
}

function search_array_by_key($array, $key, $value,$ret='key') 
{
    $return = array();   
    foreach ($array as $k=>$subarray)
    {  
        if (isset($subarray[$key]) && $subarray[$key] == $value) 
        {
          $return[$k] = $subarray;
          if ( $ret == 'key' )
          {
              return $k;
          }
          else
          {
              return $return;
          }
          
        } 
    }
   
}

function search_in_array_r($needle, $array) {
    $found = array();
    foreach ($array as $key => $val) {
        if ($key == $needle) {
            array_push($found, $key);
        }
    }
    if (count($found) != 0)
        return $found;
    else
        return false;
}