<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Statistici extends MY_Controller
{   
    public function __construct()
    {
        parent::__construct();  
        $this->load->model('statistici_model','statistici');
    }
    
    public function index()
    {
        $this->statistici();
    } 
    
    public function statistici()
    {
        $data['erorr'] = $this->session->flashdata('erorr');
        $data['zone']  = $this->mysql->get_All('zona','','','','subzona','asc');
        $this->display->frontend('statistici/statistici',$data);
    }    

    public function preturi_acutale()
    {
        $data['r']        = (isset($_GET['r'])     ? $_GET['r']    : 1);
        $c                = (isset($_GET['c'])     ? $_GET['c']    : '');   
        $o                = (isset($_GET['o'])     ? $_GET['o']    : 'asc');         
        $data['zone']     = $this->statistici->get_preturi_acutale($data['r'],$c,$o);        
        $this->display->frontend('statistici/preturi_acutale',$data);
    } 
    
    public function zona($id = null)
    {        
        if(!is_numeric($id)) redirect('/statistici-imobiliare');        
       
        $data['zona_vinzari']     = $this->statistici->get_preturi_acutale(1,'','',$id); 
        $data['zona_inchirieri']  = $this->statistici->get_preturi_acutale(2,'','',$id); 
        
        if(!$data['zona_vinzari']) redirect('/statistici-imobiliare');
        
        $this->display->frontend('statistici/detalii_statistici',$data);
    }  
    
    
}