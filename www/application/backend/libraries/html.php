<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Html {

   function get_cat($id)
   {
        $CI = &get_instance();
        $arr = $CI->mysql->get_All('categoria',array('rubrica_id'=>$id)); 
        $content = '<select class="select_normal" id="categoria" name="categoria">
                     <option value="">-- selecteaza --</option>
                   ';
        foreach($arr as $row)
        {
             $content .="<option value=".$row['id']." ";
             if(isset($id) and $id==$row['id']) $content .="selected=selected ";
             $content .=" >".$row['title_ro'];
             $content .=" </option>";
         }
         $content .= '</select>';
         return $content;
   }
   function option_tag($arr)
   {
        $CI = &get_instance();
        $content = '';
       // $content ="<select name=page[1][type]  >";
        foreach($arr as $row)
           {
             $content .="<option value=".$row['id']." >";
             $content .="$row[title_ro]</option>";
           }
       //  $content .="</select>";
         return $content;
   }  
        
    
}

?>
