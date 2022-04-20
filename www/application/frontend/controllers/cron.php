<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Cron extends MY_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron_model','cron');
    }
    
//    public function get_curs()
//    {
//        $data_now      = date('Y-m-d');        
//        $curs_db       = $this->mysql->get_row('curs',array('data'=>$data_now));        
//        if(!$curs_db) 
//        {
//            $this->load->library('cursBnrXML');          
//            $curs              = new cursBnrXML();            
//            $data              = $curs->date;
//            $usd               = $curs->getCurs("USD");                        
//            $eur               = $curs->getCurs("EUR"); 
//            if($usd == 0) $usd = '3,2513';
//            if($eur == 0) $eur = '4,4514';
//            $this->mysql->insert('curs',array('data'=>''.$data_now,'usd_punct'=>$usd,'eur_punct'=>$eur,'usd'=>str_replace('.',',',$usd),'eur'=>str_replace('.',',',$eur)));
//        }    
//    } 
    public function get_alerte()
    {
        $to_data     = date('Y-m-d '.'12:00:00');        
        $from_data   = date('Y-m-d H:i:s',strtotime('-1 day', strtotime($to_data))); 
       //echo "from=".$from_data;
       // echo "<br />to-data=".$to_data;
        $users_alerte  = $this->cron->get_users_alerte();
        $setting = $this->mysql->get_row('settings', array('id' => 1));
        foreach($users_alerte as $item): 
            //luam fiecare alerta in parte pentru fiecare utlizator in parte            
            $alerte = $this->cron->get_alerte_by_user($item['user_id']);
            if($alerte):
                foreach($alerte as $val):
                    $anunturi = $this->cron->cauta_anunturi($val['cuvint'],$val['rubrica_id'],$val['categoria_id'],$val['pret_min'],$val['pret_max'],$val['moneda_url'],$from_data,$to_data,100);
                    //pr($anunturi);
                    if($anunturi['result']):
                            $subject = "Anunturi noi pentru $val[cuvint] la $val[title_rubrica] / $val[title_categoria]";
                            $msg  = "Buna Ziua <br />";
                            $msg .= "Am gasit <b>$anunturi[count] anunturi noi</b> care sa corespunda criteriilor cautarii Dvs. <br />";
                            $msg .= " Anunturi noi pentru <b>'$val[cuvint]'</b> la <b>$val[title_rubrica] / $val[title_categoria]</b><br /><br />";
                            $msg .="<table>";
                                foreach($anunturi['result'] as $anunt):
                                   $msg .= "<tr><td>"; 
                                   $msg .= "$anunt[title],".read_more($anunt['text'],10)."...</td><td>$anunt[pret] $anunt[moneda]</td>";
                                   $msg .= "<td></tr>";    
                                endforeach;
                            $msg .="</table>";
                            //echo $msg;
                            $this->send_mail($setting['mail'], $subject, $msg);                        
                    endif;
                endforeach;
            endif;
        endforeach;
    }
}