<?php

function crumbs ($crumbs, $type,$param = NULL)
{
    $html = '<div class="crumbs clearfix">';
    $html .='<a href="/">Home</a> >';
    switch ($type) {
        case 'account':
            $html .=' <a href="/">Contul Meu </a> >';
            break;
        case 'shop':
            break;
        default:
            break;
    }
   
    $count = count($crumbs);
    
   
    if ($count == 1) {
        $html .=' <a href="'.$crumbs[0]['url'].'"  >   '.$crumbs[0]['title'].'</a> ';
    } else {
        foreach ($crumbs as $key => $value) {
            $html .=' <a href="'.$value['url'].'"  >'.$value['title'].'</a> ';
            if ($key + 1 != $count) {
                $html.='>';
            }
        }
    }
    $html.='</div>';
    return $html;
           
    
}