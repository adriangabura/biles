<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mysql extends CI_Model
{
    public function __construct() 
    {
           parent::__construct();           
    }
    public function get_limbi() {
        $all = $this->get_All('langs');
        $default = $this->get_row('langs',array('default'=>1));
        $return = array();
        $return['default'] = $default['ext'];
        foreach ($all as $key=>$value) {
            $return[$value['ext']] = array('id'=>$value['id_langs'],'name'=>$value['name']); 
        }
        return $return;
    }
    
    public function get_All($table,$cond_arr = null,$limit1 = null,$limit2 = null,$by_title = null ,$order = null)
    {  
        if(empty($limit2)) $limit2 = 0;
        if($limit1) $this->db->limit($limit1,$limit2);
        //$this->db->limit(5,5);
        
        if($cond_arr) $query = $this->db->where($cond_arr);        
        
        if($order and $by_title) $this->db->order_by($by_title, $order);//else $this->db->order_by('id_'.$table, 'desc');
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function get_All_like($table,$cond_arr = null,$limit1 = null,$limit2 = null,$by_title = null ,$order = null)
    {
        
        if($limit1) $this->db->limit($limit1,$limit2);
        //$this->db->limit(5,5);
        
        if($cond_arr) $query = $this->db->where($cond_arr);        
        
        if($order and $by_title) $this->db->order_by($by_title, $order);//else $this->db->order_by('id_'.$table, 'desc');
        $query = $this->db->get($table);
        return $query->result_array();
    }
    
    public function get_rows($table,$row = 'id',$cond_arr = null,$limit1 = null,$limit2 = null,$by_title = null ,$order = null)
    {  
        $this->db->select("$row");
        $this->db->from($table);
        $this->db->where($cond_arr);
        
        if(empty($limit2)) $limit2 = 0;
        if($limit1) $this->db->limit($limit1,$limit2);        
        
        if($order and $by_title) $this->db->order_by($by_title, $order);//else $this->db->order_by('id_'.$table, 'desc');
        
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_item($table,$id)
    {
        $this->db->where('id_'.$table,$id);
        $query = $this->db->get($table);
        return $query->row_array(); 
    }

    public function left_join($table1,$table2,$cond,$cond2 = null)
    {   
        $this->db->select('*');
        $this->db->from($table1);
        $this->db->join($table2, $cond ,'left');
        if($cond2) $this->db->where($cond2);
       // $this->db->where(array('id_users !=' => 1));
        $query = $this->db->get();

        return $query->result_array();
    }
    public function get_max($table,$item,$cond=null)
    {
        $query = $this->db->select_max($item);
        if($cond) $this->db->where($cond);
        $query = $this->db->get($table);
        $max = $query->row_array();
        return $max["$item"];
    }
    
    /**
     * Get Row Like
     * Extrage din baza asemnatoare cu word
     * @var $arr_like (string|array) 
     * @var $cond_arr (array)
     * return uni array()          
    */   
    public function get_like($table,$arr_like,$arr_like2 = null,$cond = null)
    {
        $this->db->like($arr_like); 
        if($arr_like2) $this->db->or_like($arr_like2);
        if($cond) $this->db->where($cond);        
        $query = $this->db->get($table);
        return $query->result_array();
    }     
    
    public function get_min($table,$item,$cond=null)
    {
        $query = $this->db->select_min($item);
        if($cond) $this->db->where($cond);
        $query = $this->db->get($table);
        $max = $query->row_array();
        return $max["$item"];
    }
    
    public function get_row($table,$arr = null)
    {
        if($arr) $this->db->where($arr);
        $query = $this->db->get($table);
        
        return $query->row_array();
    }
    
    
    public function get_count($table,$arr = null,$row = 'id')
    {
        $this->db->select($row);
        if($arr) $this->db->where($arr);
       // $query = $this->db->get($table);
        $total = $this->db->count_all_results($table);
        return $total;
    }    

    public function insert($table,$data)
    {
        $query = $this->db->insert($table,$data);
        //$this->db->where_in('id', $array_row);
        if($query) return $this->db->insert_id(); 
        else return false;
    }

    public function update($table,$data,$id = null)
    {
        if($id) 
        {    
          if(is_numeric($id)) $this->db->where('id_'.$table,$id);
          else $this->db->where($id);
        }    
        $result =  $this->db->update($table,$data);
        //$count = $this->db->affected_rows();
        return $result; 
    }
        
    public function delete($table,$data)
    {
        $query = $this->db->delete($table,$data);
        if($query) return true;
        else return false;
    }
     public function delete_user($id)
    {
        $query = $this->db->query("DELETE FROM app_user WHERE id = $id");
        if($query) return true;
        else return false;
    }
   
/******** end standart ****/   
       

    
    public function get_nivele($row,$level=0,$table)
    {
       
     if($row)
     {       
       $level = $level + 1;       
       $child = $this->get_row($table,array('id_'.$table=>$row['parent']));       
       if($child)
       {           
           $level = $this->get_nivele($child,$level,$table);            
       }
       return $level;         
     }
    }
     
    public function get_nivele3($row,$level=0,$table)
    {
       
     if($row)
     {       
       $level = $level + 1;       
       $child = $this->get_row($table,array('id'=>$row['produs_id']));       
       if($child)
       {           
           $level = $this->get_nivele3($child,$level,$table);            
       }
       return $level;         
     }
    }
     
    
     
    public function get_nivele2($row,$level=0,$table)
    {
       
     if($row)
     {       
       $level = $level + 1;       
       $child = $this->get_row($table,array('id'=>$row['parent']));       
       if($child)
       {           
           $level = $this->get_nivele2($child,$level,$table);            
       }
       return $level;         
     }
    }    
    
    public function get_text($id)
    {
        $tx = array();
        $tx = $this->mysql->get_row("text",array("id_text"=>$id));
        return $tx;
    }
    
    
    /* front end */
       

	

   
    public function update_where($table,$data,$where)
	{
		if (is_array($where))
		{
			foreach ($where as $key=>$value)
			{
				$this->db->where($key,$value);
			}
		}
		
		$result =$this->db->update($table,$data);
		
		return $result;
	}
   
        
    public function num_rows($table,$where)
    {
        if (is_array($where))
		{
			foreach ($where as $key=>$value)
			{
				$this->db->where($key,$value);
			}
		}
		
		$result =$this->db->get($table);
		
		return $result->num_rows();
    }
    
    /* adaugator */
   
     function getNewsrec()
		{
			$query = $this->db->query("SELECT * FROM app_news ORDER BY data DESC LIMIT 2");
			return $query->result_array();
		}
    function search($word,$lng)
		{
			$query = $this->db->query("SELECT * FROM app_catalog WHERE `name_$lng` LIKE '%$word%' ");
			return $query->result_array();
		}
    
    function search_products($word,$lng) {
        $query = $this->db->query("SELECT * FROM app_catalog WHERE `name_$lng` LIKE '%$word%' AND `parent` > 0 ");
        return $query->result_array();
    }
    
    function search_news($word,$lng) {
        $query = $this->db->query("SELECT * FROM app_news WHERE `name_$lng` LIKE '%$word%' ");
        return $query->result_array();
    }
}
?>
