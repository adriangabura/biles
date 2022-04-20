<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * @package Codeigniter
 * @subpackage Libraries
 * @category Youtube 
 * @author Ciobanu Ion "http://www.it-nolimit.com"
 * @version 0.1
 */

class Youtube 
{
	
	
	public $id ='';
	private $_url = "http://gdata.youtube.com/feeds/api/videos/";
	private $data;
	public $width = 640;
	public $height = 390;
	public $link;
	public $title;
	
	function __construct($param = array())
	{
		if (count($param) > 0) {
			
			$this->init($param);
			
		}
	}
	
	
	public function init($param = array())
	{
		if (count($param) > 0){

			foreach ($param as $k=>$v){
				
				if (isset($this->$k)) {
					$this->$k = $v;
				}
			}
		}
	}
	
	/**
	 *@return complete url
	 */
	private function set_url($url)
	{
		return $this->_url = $url;
	}
	
	public function set_data($data)
	{
		 $this->data = $data;
	}
	
	public function get_data()
	{
		return $this->data;
	}
	
	
	/**
	 *Extragem datele despre clip
	 *@return array()   
	 */
	
	public function get_youtube_data ()
	{
		
		$this->set_url($this->_url.$this->id);
		$this->set_data(file($this->_url));
		$this->set_data(implode('', $this->get_data()));
		$this->set_data(str_replace(array("\r\n", "\r"), "\n", $this->get_data()));
		preg_match("<yt:duration seconds='(.*?)'/>", $this->get_data(), $duration);
		preg_match('|<title [^>]*>(.*?)</title>|is', $this->get_data(), $title);
		
		//preg_match("|<media:thumbnail url='(.*?)'/>", $this->get_data(), $img);
		//preg_match('|<media :description[^>]*>(.*?)</media:description>|', $this->get_data(), $description);
       	
		$min = floor($duration[1]/60);
       	$sec = $duration[1] %60;
		 
	    $details = array(
	    	'title'=>$title[1],
	    	'duration'=>$min.':'.$sec,
	    	
	    );

	    $this->title = $title[1];
	    
       return $details;
		
	}
	
	/**
	 * Player-ul
	 * @return html
	 */
	
	public function embed_code()
	{
		$outupt = '<object width="'.$this->width.'" height="'.$this->height.'">';
		$outupt .= '<param name="movie" value="http://www.youtube.com/v/'.$this->id.'?version=0&controls=2&rel=0&showinfo=0&feature=player_embedded">';
		$outupt .= '<param name="allowFullScreen" value="true">';
		$outupt .= '<param name="allowScriptAccess" value="always">';
		$outupt .= '<embed src="http://www.youtube.com/v/'.$this->id.'?version=3&controls=1&autoplay=0&rel=0&showinfo=0&feature=player_embedded" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="'.$this->width.'" height="'.$this->height.'"></object>';
		
		return $outupt;
	}
	
	/**
	 * Image
	 *@return html
	 */
	public function embed_image()
	{
		$output = '<img src="http://i.ytimg.com/vi/'.$this->id.'/default.jpg" width="120" height="90" border="0" alt='.$this->title.' title='.$this->title.' />';
		
		return $output;
	}
	
	
	/**
	 * Verifica daca exista asa id
	 * 
	 * @param $link format 
	 * @return id or FALSE
	 */
	public function check_link($link)
	{
		//Verificam daca este link de youtube
		
			
    	   	//Extragem ID-ul
			$x = explode('v=', $link);
			
			if(!empty($x) && isset($x[1])){
			
				$x = explode('&', $x[1]);
				$id = $x[0];
			
			}
			
			$header = get_headers($this->_url.$id);
			
			//Verificam daca exista asta ID
			if (!strpos($header[0], '200')) {
	    		return FALSE;
			} else {
				return $id;
			}
       	
	}
	
	
	
	
	
       
}