<?php

 function type_crit($id = 1)
 {
        $type =  array('1'=>'Input','2'=>'Select','3'=>'Auto','4'=>'Zona','5'=>'Truck','6'=>'Moto');                  
        return $type[$id];
 }
 
 function type_detalii($id = 1)
 {
        $type = array('1'=>'Checkbox','2'=>'Checkbox Categorizat');                  
        return $type[$id];
 } 

?>
