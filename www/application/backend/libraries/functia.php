<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Functia {

   function send_mail($title,$to,$subject,$msg,$file = null)
   {
        $CI = &get_instance();
        $settings = $CI->mysql->get_row('settings',array('id'=>1));
        $CI->load->library('email');
        
        $config['mailtype'] = 'html';
        
        $CI->email->initialize($config); 
        
        $CI->email->from($settings['mail'],$title);
        $CI->email->to($to);
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $CI->email->subject($subject);
        $CI->email->message($msg);
        if($file) $CI->email->attach($file);
        $CI->email->send();
   }
   public function read_more($content, $limit) {

        $content = strip_tags($content);
        $content = explode(' ', $content, $limit);
        array_pop($content);
        array_push($content, '...');
        $content = implode(' ', $content);


        return $content;
    }

     /**
     * preluare curs de pe bnm.md
     *
     * @param	void
     * @return	array
     */

     /**
     * preluare curs de pe bnm.md
     *
     * @param	void
     * @return	array
     */
    function curs() {


        $today = date("d.m.Y");
        $cache = "cache/rates.xml";
        $old_cache = 'cache/rates_old.xml';
        $now = time();
        //diferenta o zi
        if(!file_exists($cache) OR  (0 == filesize($cache))) 
        {        
          $bnm = 'http://bnm.md/md/official_exchange_rates?get_xml=1&date='.date("d.m.Y");
          if(@file_get_contents($bnm))
          {
            $xml = file_get_contents($bnm, true);         
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/rates.xml', $xml);                            
           } 
        }
        else 
        {
          if((($now - filemtime($cache)) > 3600 * 10))    
          { 
           $bnm = 'http://bnm.md/md/official_exchange_rates?get_xml=1&date='.date("d.m.Y");
           $xml = file_get_contents($bnm);
           file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/rates.xml', $xml);                    
          }                
        }
        //diferenta 2 zile
        if(!file_exists($old_cache) OR  (0 == filesize($old_cache))) 
        { 
          $ieri = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
          $bnm = 'http://bnm.md/md/official_exchange_rates?get_xml=1&date='.date("d.m.Y", $ieri);;
         if(@file_get_contents($bnm, true))       
         {
          $xml = file_get_contents($bnm);
          if(isset($xml)) file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/rates_old.xml', $xml);                    
         }
        }
        else
        {
           if((($now - filemtime($old_cache)) > 7200 * 10)) 
            { 
              $ieri = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
              $bnm = 'http://bnm.md/md/official_exchange_rates?get_xml=1&date='.date("d.m.Y", $ieri);;
              $xml = file_get_contents($bnm);
              file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/rates_old.xml', $xml);                    
            }
        }        
        // end diferenta 2 zile
        
        
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/cache/rates.xml';
        if(0 == filesize( $file_path )) $file_path = $_SERVER['DOCUMENT_ROOT'].'/cache/rates_old.xml';       
        if(0 == filesize( $file_path )) $file_path = $_SERVER['DOCUMENT_ROOT'].'/cache/old.xml';         
            
        $xmlObject = simplexml_load_file($file_path);
     
          
          
        foreach ($xmlObject->children() as $node) 
        {
            $arr = $node->attributes();   // returns an array
            if ($arr["ID"] == 44)
                $usd = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 47)
                $eur = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 36)
                $rur = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 35)
                $ron = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 43)
                $uah = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 17)
                $gbp = $node->Value;     // get the value of this attribute
        }
        
        
        if(isset($usd))
        {    
         $usd = number_format((double) $usd, 4, '.', '');
         $usd1 = number_format((double) $usd, 2, '.', '');

         $lei = 100 * $usd;
        }
        else $usd = 0;
      
        if(isset($gbp))
        {    
         $gbp = number_format((double) $gbp, 4, '.', '');
         $gbp1 = number_format((double) $gbp, 2, '.', '');
         $gbp_start = round($lei / $gbp, 2);       
        }
        else $gbp = 0;

  
        if(isset($eur))
        {
          $eur = number_format((double) $eur, 4, '.', '');
          $eur1 = number_format((double) $eur, 2, '.', '');
          $eur_start = round($lei / $eur, 2);
        }  
        else $eur = 0;

        if(isset($rur))
        { 
         $rur = number_format((double) $rur, 4, '.', '');
         $rur1 = number_format((double) $rur, 2, '.', '');
         $rur_start = round($lei / $rur, 2);
        } 
        else $rur = 0;
        
        if(isset($ron))
        {  
         $ron = number_format((double) $ron, 4, '.', '');
         $ron1 = number_format((double) $ron, 2, '.', '');
         $ron_start = round($lei / $ron, 2);
        } 
        else $ron = 0;
       
        if(isset($uah))
        {
         $uah = number_format((double) $uah, 4, '.', '');
         $uah1 = number_format((double) $uah, 2, '.', '');
         $uah_start = round($lei / $uah, 2);
        } 
        else $uah = 0;

        
        $data['usd'] = $usd;
        $data['eur'] = $eur;
        $data['rur'] = $rur;
        $data['ron'] = $ron;
        $data['uah'] = $uah;
        $data['gbp'] = $gbp;

        //calculam diferenta 
        
        $file_path = $_SERVER['DOCUMENT_ROOT'].'/cache/rates_old.xml';
        if(0 == filesize( $file_path )) $file_path = $_SERVER['DOCUMENT_ROOT'].'/cache/old.xml';    
        
        $xmlObject = simplexml_load_file($file_path,null,1); 
        foreach ($xmlObject->children() as $node) 
        {
            $arr = $node->attributes();   // returns an array
            if ($arr["ID"] == 44)
                $usd_old = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 47)
                $eur_old = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 36)
                $rur_old = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 35)
                $ron_old = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 43)
                $uah_old = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 17)
                $gbp_old = $node->Value;     // get the value of this attribute
 
        }
        $usd_old = number_format((double) $usd_old, 4, '.', '');
        $usd1_old = number_format((double) $usd_old, 2, '.', '');

        $lei = 100 * $usd_old;

        $gbp_old = number_format((double) $gbp_old, 4, '.', '');
        $gbp1_old = number_format((double) $gbp_old, 2, '.', '');
        $gbp_start_old = round($lei / $gbp_old, 2);

        $eur_old = number_format((double) $eur_old, 4, '.', '');
        $eur1_old = number_format((double) $eur_old, 2, '.', '');
        $eur_start_old = round($lei / $eur_old, 2);

        $rur_old = number_format((double) $rur_old, 4, '.', '');
        $rur1_old = number_format((double) $rur_old, 2, '.', '');
        $rur_start_old = round($lei / $rur_old, 2);

        $ron_old = number_format((double) $ron_old, 4, '.', '');
        $ron1_old = number_format((double) $ron_old, 2, '.', '');
        $ron_start_old = round($lei / $ron_old, 2);

        $uah_old = number_format((double) $uah_old, 4, '.', '');
        $uah1_old = number_format((double) $uah_old, 2, '.', '');
        $uah_start_old = round($lei / $uah_old, 2);

        $data['diff_usd'] = number_format((double)($usd-$usd_old), 4, '.', '');
        $data['diff_gbp'] = number_format((double)($gbp-$gbp_old), 4, '.', '');
        $data['diff_eur'] = number_format((double)($eur-$eur_old), 4, '.', ''); 
        $data['diff_rur'] = number_format((double)($rur-$rur_old), 4, '.', '');
        $data['diff_ron'] = number_format((double)($ron-$ron_old), 4, '.', '');
        $data['diff_uah'] = number_format((double)($uah-$uah_old), 4, '.', '');
        return $data;
            
    }   
/**
     * XML parsing
     *
     * @param	XML object
     * @return	array
     */
    private function parseXML($f) {

        $xmlObject = new SimpleXMLElement($f);


        $date = $xmlObject->attributes();
        $date = (string) $date[0]; //get exchange date

        foreach ($xmlObject->children() as $node) {
            $arr = $node->attributes();   // returns an array
            if ($arr["ID"] == 44)
                $usd = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 47)
                $eur = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 36)
                $rur = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 35)
                $ron = $node->Value;     // get the value of this attribute
            if ($arr["ID"] == 43)
                $uah = $node->Value;     // get the value of this attribute
        }

        $usd = number_format((double) $usd, 4, '.', '');
        $usd1 = number_format((double) $usd, 2, '.', '');

        $lei = 100 * $usd;

        $eur = number_format((double) $eur, 4, '.', '');
        $eur1 = number_format((double) $eur, 2, '.', '');
        $eur_start = round($lei / $eur, 2);

        $rur = number_format((double) $rur, 4, '.', '');
        $rur1 = number_format((double) $rur, 2, '.', '');
        $rur_start = round($lei / $rur, 2);

        $ron = number_format((double) $ron, 4, '.', '');
        $ron1 = number_format((double) $ron, 2, '.', '');
        $ron_start = round($lei / $ron, 2);

        $uah = number_format((double) $uah, 4, '.', '');
        $uah1 = number_format((double) $uah, 2, '.', '');
        $uah_start = round($lei / $uah, 2);

        $data['date'] = $date;
        $data['usd'] = $usd;
        $data['eur'] = $eur;
        $data['rur'] = $rur;
        $data['ron'] = $ron;
        $data['uah'] = $uah;

        return $data;
    }


    /* old */
   function send_mail_db($to,$id_text,$lng,$file = null,$text = null)   
   {
        $CI = &get_instance();
        $CI->load->model('mysql');
        $settings = $CI->mysql->get_row('settings',array('id'=>1));
        
        
        $email_text = $CI->mysql->get_row('email_text',array('id'=>$id_text));        
        $msg = $email_text['msg_'.$lng];
        if($text) $msg .= $text;
        $CI->load->library('email');
        $config['mailtype'] = 'html';
        
        $CI->email->initialize($config);            
        $CI->email->from($settings['mail'],$email_text['title_'.$lng]);
        $CI->email->to($to);
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $CI->email->subject($email_text['subject_'.$lng]);
        $CI->email->message($msg);
        if($file) $CI->email->attach($file);
        $CI->email->send();
   }
       
    
   function generate_pwd($number)
   {
    $arr = array('a','b','c','d','e','f',
                 'g','h','i','j','k','l',
                 'm','n','o','p','r','s',
                 't','u','v','x','y','z',
                 'A','B','C','D','E','F',
                 'G','H','I','J','K','L',
                 'M','N','O','P','R','S',
                 'T','U','V','X','Y','Z',
                 '1','2','3','4','5','6',
                 '7','8','9','0','.',',',
                 '(',')','[',']','!','?',
                 '&','^','%','@','*','$',
                 '<','>','/','|','+','-',
                 '{','}','`','~');
     // Generam parola
     $pass = "";
     for($i = 0; $i < $number; $i++)
     { 
      // random
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
     }
     return $pass;
    }
    
    public function convert_data($data)
    {
          if(preg_match('`^\d{1,2}/\d{1,2}/\d{4}$`',$data)) {$data=$data;}
          else $data = date('d/m/Y'); 
          
          $d1= explode('/',$data);
          $data_result = $d1['2'].'-'.$d1['1'].'-'.$d1['0'].' '.date('H:i:s');      
          return $data_result;
    }  

    public function short_text($text,$cond ="500")
    { 
      $text=substr($text,0,$cond);
      echo $text."&nbsp;..";
    }

    public function log($text)
    { 
        $CI = &get_instance();
        $CI->load->model('mysql');
        $CI->load->library('session');
        $user_id =$CI->session->userdata('user_id');
        $CI->mysql_model->insert('logs',array('text'=>$text,'id_users'=>$user_id,'ip_logs'=>$_SERVER['REMOTE_ADDR']));       
    }
    
    public function create_pdf_oferte($title,$desc,$products)
    {
        $CI = &get_instance();        
        
        $CI->load->helper('tfpdf');
        $CI->load->helper('data_helper');
        $CI->load->model('mysql'); 
        $CI->load->library('session');
        
        $pdf = new tFPDF('P','mm','A4'); 
        $pdf -> AddPage();  
        
        //titlu oferta
        // Add a Unicode font (uses UTF-8)
        $pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $pdf->SetFont('DejaVu','',14);
        
        //$pdf->AddFont('TimesNewRomanPSMT','','times.php');
        //$pdf->SetFont('TimesNewRomanPSMT','');
       // $pdf->AddFont('TimesNewRomanPSMT','','times.php');
       // $pdf->SetFont('TimesNewRomanPSMT','',12); 
       // $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,0,$title); 
        $pdf->Ln(5); // rind nou
        
        //descrire oferta        
        $pdf->MultiCell(0,5,$desc); 
        $pdf->Ln(5);
        $i=0;
        foreach($products as $item)
        {    
            $i++;
            //info suprafata
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(0,0,'Info Suprafata publicitara Nr'.$i); 
            $pdf->Ln(8); // rind nou       

            //adresa link
            $pdf->SetTextColor(0,136,204);     
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(0,0,$item['adresa'],'','','',false,'http://www.marplo.net/jocuri'); 
            $pdf->Ln(5); // rind nou

            //numar de inventar
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(0,0,0);     
            $pdf->Cell(0,0,'Numarul de inventar: #'.$item['inv']); 
            $pdf->Ln(8); // rind nou

           //pret
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,0,'Pret: '.$item['price'].' euro'); 
            $pdf->Ln(10); // rind nou       

           //dimensiunea
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,0,'Dimensiuni: '.$item['format']); 
            $pdf->Ln(5); // rind nou       

           //foto   
            if($item['image'] and file_exists($_SERVER['DOCUMENT_ROOT'].$item['image']))
            {        
             $pdf->Cell(0,0,'Foto'); 
             $pdf->Ln(2); // rind nou  
             $pdf->Image('.'.$item['image']);   
             $pdf->Ln(20); // rind nou 
            }else  $pdf->Ln(20); // rind nou 
            
        
        }
       //footer
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(0,0,'Trendseter Copyright 2011','','','C'); 
        $pdf->Ln(5); // rind nou       
        
        // Tilte pdf 
        $page_title = 'Oferta_'.date('d_m_Y_H:i:s');
        $pdf -> output ('./uploads/offerts/'.$page_title.'.pdf','F');
        $CI->mysql->insert('offerts_pdf',array('uid'=>$CI->session->userdata('uid'),'pdf'=>'./uploads/offerts/'.$page_title.'.pdf'));
        
        return './uploads/offerts/'.$page_title.'.pdf';
    }    
  
   
    public function create_pdf_oferte_old($title,$desc,$products)
    {
        $CI = &get_instance();        
        $CI->load->helper('ufpdf');
        $CI->load->helper('data_helper');
        $CI->load->model('mysql'); 
        $CI->load->library('session');
        
        $pdf = new UFPDF('P','mm','A4'); 
        $pdf -> AddPage();  
        
        //titlu oferta
        $pdf->AddFont('TimesNewRomanPSMT','','times.php');
        $pdf->SetFont('TimesNewRomanPSMT','',16);
       // $pdf->AddFont('TimesNewRomanPSMT','','times.php');
       // $pdf->SetFont('TimesNewRomanPSMT','',12); 
       // $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,0,$title); 
        $pdf->Ln(5); // rind nou
        
        //descrire oferta
        $pdf->SetFont('TimesNewRomanPSMT','',10);
        $pdf->MultiCell(0,5,$desc); 
        $pdf->Ln(5);
        $i=0;
        foreach($products as $item)
        {    
            $i++;
            //info suprafata
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(0,0,'Info Suprafata publicitara Nr'.$i); 
            $pdf->Ln(8); // rind nou       

            //adresa link
            $pdf->SetTextColor(0,136,204);     
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(0,0,$item['adresa'],'','','',false,'http://www.marplo.net/jocuri'); 
            $pdf->Ln(5); // rind nou

            //numar de inventar
            $pdf->SetFont('Arial','',10);
            $pdf->SetTextColor(0,0,0);     
            $pdf->Cell(0,0,'Numarul de inventar: #'.$item['inv']); 
            $pdf->Ln(8); // rind nou

           //pret
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,0,'Pret: '.$item['price'].' euro'); 
            $pdf->Ln(10); // rind nou       

           //dimensiunea
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(0,0,'Dimensiuni: '.$item['format']); 
            $pdf->Ln(5); // rind nou       

           //foto   
            if($item['image'] and file_exists($_SERVER['DOCUMENT_ROOT'].$item['image']))
            {        
             $pdf->Cell(0,0,'Foto'); 
             $pdf->Ln(2); // rind nou  
             $pdf->Image('.'.$item['image']);   
             $pdf->Ln(20); // rind nou 
            }else  $pdf->Ln(20); // rind nou 
            
        
        }
       //footer
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(0,0,'Trendseter Copyright 2011','','','C'); 
        $pdf->Ln(5); // rind nou       
        
        // Tilte pdf 
        $page_title = 'Oferta_'.date('d_m_Y_H:i:s');
        $pdf -> output ('./uploads/offerts/'.$page_title.'.pdf','F');
        $CI->mysql->insert('offerts_pdf',array('uid'=>$CI->session->userdata('uid'),'pdf'=>'./uploads/offerts/'.$page_title.'.pdf'));
        
        return './uploads/offerts/'.$page_title.'.pdf';
    }    
  
        
    
}

?>
