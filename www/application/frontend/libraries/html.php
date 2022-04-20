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
   function option_tag($id)
   {
        $CI = &get_instance();
        $settings = $CI->mysql->get_row('settings',array('id'=>1));
         $arr = $CI->mysql_model->get_All($table);
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
        
    
}

?>
