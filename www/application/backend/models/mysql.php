<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mysql extends CI_Model
{
    public function __construct() 
    {
           parent::__construct();           
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
    
    public function get_rows($table,$row ,$cond_arr)
    {  
        $this->db->select($row);
        $this->db->from($table);
        $this->db->where($cond_arr);
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
        
    public function update_color($table,$data,$id = null)
    {
        if($id) 
        {    
          if(is_numeric($id)) $this->db->where('id',$id);
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
    
    public function logs($event,$text,$loc = NULL)
    { 
        $CI = &get_instance();               
        $user_id =$CI->session->userdata('uid');
        $this->insert('logs',array('event'=>$event,'location'=>$loc,'msg'=>$text,'uid'=>$user_id,'ip_logs'=>$_SERVER['REMOTE_ADDR']));       
    }    
   
/******** end standart ****/   
       
    public function banners()
    {
      
        $q= $this->db->query("SELECT * FROM (`pages`) LEFT JOIN `photo_pages` ON `pages`.`id_pages`=`photo_pages`.`pid` WHERE `pages`.`parent` = 1
		ORDER BY `ord` ASC		
		");        
        
        if($q) return  $q->result_array();         
    }        
    
    
   
    
    
    public function get_portfolio()
    {                 
        $q= $this->db->query("SELECT * FROM `portfolio`");
        $result = $q->result_array();        
        foreach($result as $key=>$value)
        {
          // partea vid = 5 
          $result[$key]['photos'] = $this->get_All('photo_portfolio',array('pid'=>$value['id_portfolio'])); 
        }   
        return $result;        
    } 
    
    public function get_nivele($row,$level=0,$table)
    {
       
     if($row)
     {       
       $level = $level + 1;       
       if($table=='catalog') $child = $this->get_row($table,array('id'=>$row['parent']));             
       elseif($table=='auto') $child = $this->get_row($table,array('id'=>$row['parent']));
       elseif($table=='moto') $child = $this->get_row($table,array('id'=>$row['parent']));  
       elseif($table=='truck') $child = $this->get_row($table,array('id'=>$row['parent']));  
       elseif($table=='obiective') $child = $this->get_row($table,array('id'=>$row['parent']));     
                             else  $child = $this->get_row($table,array('id_'.$table=>$row['parent']));       
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
       
    public function get_events_by_categ($url_categ = null,$limba= 'ro',$data = null)
    {
        if($url_categ) $cond_categ =" WHERE `url`= '$url_categ'";else $cond_categ ='';
        if($data) $cond_data =  "AND `events`.`data` = '$data'";else $cond_data = '';   
        $q= $this->db->query("SELECT `events`.*,`scheme`.`name_$limba` as `local_$limba` 
                 FROM `events` LEFT JOIN `scheme` ON(`events`.`id_scheme`=`scheme`.`id_scheme`)
                   WHERE `events`.`id_scheme` IN (SELECT `id_scheme` FROM `scheme` 
                   WHERE  `id_category` IN (SELECT `id_category` FROM `category` $cond_categ) )	
                       $cond_data
                       ORDER BY   `events`.`data` DESC
		");        
        
        if($q) return  $q->result_array();         
    }        

       
    public function get_event($url = null,$limba= 'ro',$id = null)
    {
        if($id) $cond = "`events`.`id_events` = '$id' ";
        else $cond ="`events`.`url` = '$url' ";
        $q= $this->db->query("SELECT `events_options`.*,`events`.*,`scheme`.`name_$limba` as `local_$limba` 
                 FROM `events` LEFT JOIN `events_options` ON(`events`.`id_events`=`events_options`.`id_events`) LEFT JOIN `scheme` ON(`events`.`id_scheme`=`scheme`.`id_scheme`)
                   WHERE $cond
		"); 
		
		$q2= $this->db->query("SELECT * FROM `events` LEFT JOIN `events_options` ON(`events`.`id_events`=`events_options`.`id_events`) WHERE $cond");       
        
        if($q) return  $q->row_array();
		if($q2) return  $q2->row_array();         
    } 
    
	public function get_event_options($url = null,$limba= 'ro',$id = null)
    {
        if($id) $cond = "`events`.`id_events` = '$id' ";
        else $cond ="`events`.`url` = '$url' ";
		
		$q2= $this->db->query("SELECT * FROM `events_options` LEFT JOIN `events` ON(`events`.`id_events`=`events_options`.`id_events`) WHERE $cond");       
            
		if($q2) return  $q2->result_array();         
    }
	
	public function get_cart()
    {		
		$k2= $this->db->query("SELECT * FROM `cos`");       
            
		if($k2) return  $k2->result_array();         
    }
	
	       
    public function get_booking_detail_user($limba= 'ro',$id_events)
    {
        $uid =  $this->session->userdata('uid');   
        $q= $this->db->query("SELECT *,`scheme`.`name_$limba` as `local_$limba`,`events`.`name_$limba` as `nume_event`,`events`.`url` as `url_event`,`events`.`photo` as `photo_event` FROM `booking` 
                      LEFT JOIN `scheme_structure` ON(`booking`.`id_scheme_structure`=`scheme_structure`.`id_scheme_structure`)
                        LEFT JOIN `events` ON(`events`.`id_events`=`booking`.`id_events`)
                          LEFT JOIN `scheme` ON(`events`.`id_scheme`=`scheme`.`id_scheme`)
                            WHERE `events`.`id_events` = '$id_events'
		"); 
        if($q) return  $q->row_array();         
    }   
       
    public function get_booking_detail($id_booking)
    {
            
        $q= $this->db->query("SELECT * FROM `booking` LEFT JOIN `scheme_structure`
            ON(`booking`.`id_scheme_structure`=`scheme_structure`.`id_scheme_structure`)
            where `booking`.`id_booking` = '$id_booking' 
		");        
        
        if($q) return  $q->row_array();         
    } 
    
         
    public function get_booket_bilet($type_booking = 0,$id_events)
    {
        // 0 libere
        // 1 bronate
        // 2 vindute
        
        $q= $this->db->query("SELECT  COUNT(*) as nr FROM `booking`
            where `booking`.`id_events` = '$id_events'  and `booking`.`booked` = '$type_booking' 
		");  
        $rezult = $q->row_array();         
        return $rezult['nr'];
    }      
    public function get_booking_detail_schema($id_events,$booked = null)
    {
        if($booked)  $cond = " and `booking`.`booked`< '$booked' ";
        else $cond = '';
        $q= $this->db->query("SELECT * FROM `booking` LEFT JOIN `scheme_structure`
            ON(`booking`.`id_scheme_structure`=`scheme_structure`.`id_scheme_structure`)
            where `booking`.`id_events` = '$id_events' 
                $cond
		");        
        
        if($q) return  $q->result_array();         
    } 
    
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
    public function orders()
    {
      
        $q= $this->db->query("SELECT *,`orders`.`id` as orders_id FROM (`orders`) LEFT JOIN `produse_options` 
                    ON `orders`.`produs_id`=`produse_options`.`id` LEFT JOIN `produse` 
                       ON `produse`.`id`=`produse_options`.`produs_id`  LEFT JOIN `users` 
                          ON `orders`.`user_id`=`users`.`id` 
                        ORDER BY `orders`.`id` ASC		
		");        
        
        if($q) return  $q->result_array();         
    }     
  
    public function get_raport($limit  = null,$uri = null)
    {
        if($uri and $limit) $cond = "LIMIT $uri, $limit";
        else $cond = "";
        $q= $this->db->query("SELECT catalog.`id_catalog`,`name_ro`,catalog.`autor_id`,catalog.`editor_id`
                                ,catalog.`redactor_id`,orders_book.final_price as price,COUNT(orders_book.id) AS qt FROM `catalog`
                                 LEFT JOIN `orders_book` ON(catalog.id_catalog=orders_book.id_catalog) 
                                  LEFT JOIN `auth_user`  ON(catalog.autor_id=auth_user.id)  
                                  WHERE orders_book.final_price>0
                                   GROUP BY orders_book.final_price
                                        ORDER BY catalog.id_catalog DESC $cond;		
		");        
        
        if($q) return  $q->result_array();         
    } 
    
      public function reorder_photo($table,$id,$ac,$row = null,$row_val = null,$asc_desc = 'asc')
    {
         $table = 'app_'.$table;
          $old_item=$this->mysql->get_row($table,array('id'=>$id));
          if($old_item['ord']==0) $this->mysql->update($table,array('ord'=>$id),array('id'=>$id)); 
    
          //asc desc 
          if($asc_desc=='desc')
          {    
              $ord_up = 'asc';  
              $ord_down = 'desc';
}
          else
          {    
              $ord_up = 'desc'; 
              $ord_down = 'asc';
          }
         //daca e precendet
            if($ac=='up')
            {   
                $old=$old_item['ord'];

                //$parent_item=$this->mysql->get_row($table,array('id'=>$id));
                $parent=$row_val;

                $q= $this->db->query("SELECT `ord`,`id` FROM `$table` 
                        WHERE `ord`<'$old' and `$row`='$parent' order by `ord` $ord_up limit 0,1;");
                $rez = $q->row_array();
                
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                    return 1;
                 }
                } 
            }
           //daca e urmator
            if($ac=='down')
            {    
                $old=$old_item['ord'];

                //$parent_item=$this->mysql->get_row($table,array('id'=>$id));
                $parent = $row_val;
                
                $q= $this->db->query("select `ord`,`id` from `$table` where `ord`>'$old' and `$row`='$parent' order by `ord` $ord_down limit 0,1");
                $rez = $q->row_array();
                if($rez)
                {    
                 $new=$rez['ord'];
                 $new_id=$rez['id'];
                 if ($new>0)
                 {
                    $this->mysql->update($table,array('ord'=>$new),array('id'=>$id));
                    $this->mysql->update($table,array('ord'=>$old),array('id'=>$new_id));
                    return 1;
                 }
                }
                
            }    
            
        
    }  
    
}
?>
