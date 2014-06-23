<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_m extends MY_Model
{
        
	function get_events($time, $is_thumb = false, $is_repeat = false, $thismonth = true, $repeat_emersion = array()){
		
		$today = date("Y/n/j", time());
		$current_month = date("n", $time);
		$curmonth_today = intval(date("n", time()));
		$curmonth_active = intval($current_month);

		$current_year = date("Y", $time);
		$current_month_text = date("F Y", $time);
		$total_days_of_current_month = date("t", $time);
		
		$events = array();
		
		$content = "";
		if(!$is_thumb){
			$content = '`event_content`,';
		}
		
		
        $this->db->select(" `id_eventcal`, `user_id`, ".$content." `event_title`, `event_date_begin`, `event_date_end`, `event_repeat`, `event_repeat_prm` ");
        $this->db->select(" DATE_FORMAT(`event_date_begin`,'%d') AS day", FALSE);
        $this->db->where('event_repeat', 0);
        $str_where = 'event_date_begin BETWEEN \''.$current_year.'-'.$current_month.'-01\'  AND \''.$current_year.'-'.$current_month.'-'.$total_days_of_current_month.'\' ';
		$this->db->where($str_where);
		
		//$this->db->where(" event_date_begin >=",  $current_year."/".$current_month."/01");
		//$this->db->where(" event_date_begin <",   $current_year."/".$current_month."/".$total_days_of_current_month);
		//$strquery1 = $this->db->get_compiled_select($this->db->dbprefix('eventcal'));
		$query = $this->db->get($this->db->dbprefix('eventcal'));
        
        if($is_repeat){
            $this->db->select(" `id_eventcal`, `user_id`, ".$content." `event_title`, `event_date_begin`, `event_date_end`, `event_repeat`, `event_repeat_prm` ");
            $this->db->select(" DATE_FORMAT(`event_date_begin`,'%d') AS day", FALSE);
            $this->db->where('event_repeat', 1);
            
            $query2 = $this->db->get($this->db->dbprefix('eventcal'));
            $rquery1 = $query->result();
            $rquery2 = $query2->result();
            
            $rquery = (object)array_merge((array)$rquery1, (array)$rquery2);
        }else{
            $rquery = $query->result();
        }
        
		$curtime = time();
		
		foreach ($rquery as $row_event)
		{
			if($row_event->event_repeat == 1){
				$prm = @json_decode($row_event->event_repeat_prm);
				if(isset($prm->type) and $prm->type == 0){
					
					if(!empty($repeat_emersion) and !empty($repeat_emersion['daily'])){
						$crep = $repeat_emersion['daily'];
						$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
						$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
						if($count_d_p > 0){
							for($cd = 0; $cd < $count_d_p; $cd++){
								$xday = $count_d_p - $cd;
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 -'.$xday.' day'));
								$evemonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $evemonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
						if($count_d_n > 0){
							for($cd = 0; $cd < $count_d_n; $cd++){
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 +'.$cd.' day'));
								$evemonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $evemonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
					}else{
						$event_time = strtotime(date('Y-m-d').' '.$prm->time.':00:00');
						if($curtime > $event_time){
							$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 +1 day'));
						}else{
							$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
						}
						$curday = date('d', strtotime($row_event->event_date_begin));
						$events[intval($curday)][] = $row_event;
					}
					
					
				}elseif(isset($prm->type) and $prm->type == 1){
					for($k = 0; $k < 7; $k++){
						$looptime = strtotime(date('Y-m-d').' +'.$k.' day');
						
						if(date('w', $looptime) == $prm->day){
							$event_time = strtotime(date('Y-m-d').' '.$prm->time.':00:00'.' +'.$k.' day');
							if($curtime <= $event_time){
								$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
								break;
							}else{
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00'.' +'.($k+7).' day'));
								break;
							}
						}
					}
					
					if(!empty($repeat_emersion) and !empty($repeat_emersion['weekly'])){
						$crep = $repeat_emersion['weekly'];
						$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
						$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
						$curdate = substr($row_event->event_date_begin, 0, 10);
						if($count_d_p > 0){
							for($cd = 0; $cd < $count_d_p; $cd++){
								$xday = ($count_d_p - $cd) * 7;
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 -'.$xday.' day'));
								$curmonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $curmonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
						if($count_d_n > 0){
							for($cd = 0; $cd < $count_d_n; $cd++){
								$xday = $cd * 7;
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 +'.$xday.' day'));
								$curmonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $curmonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
					}else{
						$curday = date('d', strtotime($row_event->event_date_begin));
						$events[intval($curday)][] = $row_event;
					}
					
				}elseif(isset($prm->type) and $prm->type == 2){
					$event_time = strtotime(date('Y-m-').$prm->date.' '.$prm->time.':00:00');
					$lasmonthtime = strtotime(date('Y-m-').'01 23:00:00 +1 month -1 day');
					if($event_time <= $lasmonthtime){
						$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
					}elseif(!$thismonth){
						$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-').$prm->date.' '.$prm->time.':00:00 +1 month'));
					}
				
					if(!empty($repeat_emersion) and !empty($repeat_emersion['monthly']) and !$thismonth){
						$crep = $repeat_emersion['monthly'];
						$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
						$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
						$curdate = substr($row_event->event_date_begin, 0, 10);
						if($count_d_p > 0){
							for($cd = 0; $cd < $count_d_p; $cd++){
								$xday = $count_d_p - $cd;
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 -'.$xday.' month'));
								$curmonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $curmonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
						if($count_d_n > 0){
							for($cd = 0; $cd < $count_d_n; $cd++){
								$xday = $cd;
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 +'.$xday.' month'));
								$curmonth = intval(date('n', strtotime($row_event->event_date_begin)));
								if($curmonth_active == $curmonth){
									$curday = date('d', strtotime($row_event->event_date_begin));
									$events[intval($curday)][] = $row_event;
								}
							}
						}
					}else{
						$curday = date('d', strtotime($row_event->event_date_begin));
						$events[intval($curday)][] = $row_event;
					}
					
				}
				
				
			}else{
				$events[intval($row_event->day)][] = $row_event;
			}
		}
		$query->free_result();
		return $events;						
	}
	
	
	private function __assign_repeated($data = null, $remer = array())
        {
            $result=$this->db->insert($this->db->dbprefix('eventcal'),$data);

            //check if the insertion is ok
            if($result)
                return $this->db->insert_id();
            else
                return false;
		
	}
	
	function add_event($data = array())
        {
            $result=$this->db->insert($this->db->dbprefix('eventcal'),$data);

            //check if the insertion is ok
            if($result)
                return $this->db->insert_id();
            else
                return false;
		
	}
	
	function edit_event($id, $data = array())
        {
            $this->db->where('id_eventcal', $id);
            $result=$this->db->update($this->db->dbprefix('eventcal'),$data);

            //check if the insertion is ok
            if($result)
                return true;
            else
                return false;
		
	}
    
	function get_event_by_id($id = 0)
    {
        if($id == 0){
            return false;
        }

		$this->db->where('id_eventcal', $id);
		$query = $this->db->get($this->db->dbprefix('eventcal'));
		return $query->row();
	}
	
	function count_event_by($prm = array())
    {
        $this->_table = $this->db->dbprefix('eventcal');
        $this->primary_key = 'id_eventcal';
        
        $this->db->select('count(id_eventcal) as jml');
        if(!empty($prm['title'])){
            $prm_title = str_replace('%', ' ', $prm['title']);
            $prm_title = explode(' ', $prm_title);
            
            $counter = 0;
			foreach ($prm_title as $val)
			{
                if($counter == 0){
                    $this->db->like('event_title', $val);
                }else{
                    $this->db->or_like('event_title', $val);
                }
                
                $counter++;
            }
        }
        if(!empty($prm['date'])){
            $this->db->where(" DATE(event_date_begin)", "'".$prm['date']."'", false);
            //$this->db->where(" DATE(event_date_end) >=", $prm['date']);
        }
        if(!empty($prm['date_start'])){
            if(empty($prm['date_end'])){
                $this->db->where(" DATE(event_date_begin) >= '".$prm['date_start']."' ", "", false);
            }else{
                $this->db->where(" DATE(event_date_begin) BETWEEN '".$prm['date_start']."' AND '".$prm['date_end']."' ", "", false);
            }
        }
		$this->db->where('event_repeat', 0);
        
		$query = $this->db->get($this->db->dbprefix('eventcal'));
        $hsl1 = $query->row();
		
		if(!empty($prm['getrepeat']) and $prm['getrepeat'] == 1){
			$this->db->select('count(id_eventcal) as jml');
			if(!empty($prm['title'])){
				$prm_title = str_replace('%', ' ', $prm['title']);
				$prm_title = explode(' ', $prm_title);
				
				$counter = 0;
				foreach ($prm_title as $val)
				{
					if($counter == 0){
						$this->db->like('event_title', $val);
					}else{
						$this->db->or_like('event_title', $val);
					}
					
					$counter++;
				}
			}
			
			$this->db->where('event_repeat', 1);
			
			$query = $this->db->get($this->db->dbprefix('eventcal'));
			$hsl2 = $query->row();
			$hsl = ($hsl1->jml+$hsl2->jml);
		}
		$hsl = ($hsl1->jml);
		return $hsl;
	}
    
	function list_event_by($prm = array())
    {
        $this->_table = $this->db->dbprefix('eventcal');
        $this->primary_key = 'id_eventcal';
        
        $this->db->select($this->db->dbprefix('eventcal').".*, ".$this->db->dbprefix('profiles').".display_name ");
        $this->db->join($this->db->dbprefix('profiles'), $this->db->dbprefix('profiles').".user_id = ".$this->db->dbprefix('eventcal').".user_id ", 'left');
        
        if(!empty($prm['title'])){
            $prm_title = str_replace('%', ' ', $prm['title']);
            $prm_title = explode(' ', $prm_title);
            
            $counter = 0;
			foreach ($prm_title as $val)
			{
                if($counter == 0){
                    $this->db->like('event_title', $val);
                }else{
                    $this->db->or_like('event_title', $val);
                }
                
                $counter++;
            }
        }
        if(!empty($prm['date'])){
            $this->db->where(" DATE(event_date_begin)", "'".$prm['date']."'", false);
            //$this->db->where(" DATE(event_date_end) >=", $prm['date']);
        }
        
        
        if(!empty($prm['date_start'])){
            if(empty($prm['date_end'])){
                $this->db->where(" DATE(event_date_begin) >= '".$prm['date_start']."' ", "", false);
            }else{
                $this->db->where(" DATE(event_date_begin) BETWEEN '".$prm['date_start']."' AND '".$prm['date_end']."' ", "", false);
            }
        }
        
		$this->db->where('event_repeat', 0);
		
        if(!empty($prm['order'])){
            $this->db->order_by($prm['order']);
        }
        
        // Limit the results based on 1 number or 2 (2nd is offset)
		if (!empty($prm['limit']) && is_array($prm['limit']))
			$this->db->limit($prm['limit'][0], $prm['limit'][1]);
		elseif (!empty($prm['limit']))
			$this->db->limit($prm['limit']);
        
		$query1 = $this->db->get($this->db->dbprefix('eventcal'));
		
		
        if(!empty($prm['getrepeat']) and $prm['getrepeat'] == 1){
			$repset = null;
			if(!empty($prm['repeat_setting'])){
				$repset = $prm['repeat_setting'];
			}
			
			$this->db->select($this->db->dbprefix('eventcal').".*, ".$this->db->dbprefix('profiles').".display_name ");
			$this->db->join($this->db->dbprefix('profiles'), $this->db->dbprefix('profiles').".user_id = ".$this->db->dbprefix('eventcal').".user_id ", 'left');
			
			if(!empty($prm['title'])){
				$prm_title = str_replace('%', ' ', $prm['title']);
				$prm_title = explode(' ', $prm_title);
				
				$counter = 0;
				foreach ($prm_title as $val)
				{
					if($counter == 0){
						$this->db->like('event_title', $val);
					}else{
						$this->db->or_like('event_title', $val);
					}
					
					$counter++;
				}
			}
			
			$this->db->where('event_repeat', 1);
			
			if(!empty($prm['order'])){
				$this->db->order_by($prm['order']);
			}
			
			$query2 = $this->db->get($this->db->dbprefix('eventcal'));
			$rquery1 = $query1->result();
            $rquery2 = $query2->result();
			
			$represult = array();
			
			$curtime = time();
			if(count($rquery2) > 0){
				foreach($rquery2 as $key => $row_event){
					$prm = @json_decode($row_event->event_repeat_prm);
					if(isset($prm->type) and $prm->type == 0){
						if(!empty($repset) and !empty($repset['daily'])){
							$crep = $repset['daily'];
							$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
							$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
							if($count_d_p > 0){
								for($cd = 0; $cd < $count_d_p; $cd++){
									$xday = $count_d_p - $cd;
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 -'.$xday.' day'));
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}
							if($count_d_n > 0){
								for($cd = 0; $cd < $count_d_n; $cd++){
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 +'.$cd.' day'));
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}

						}else{
							$event_time = strtotime(date('Y-m-d').' '.$prm->time.':00:00');
							if($curtime > $event_time){
								$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00 +1 day'));
							}else{
								$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
							}
							
							$represult[] = $row_event;
						}
					
					}elseif(isset($prm->type) and $prm->type == 1){
						for($k = 0; $k < 7; $k++){
							$looptime = strtotime(date('Y-m-d').' +'.$k.' day');
							
							if(date('w', $looptime) == $prm->day){
								$event_time = strtotime(date('Y-m-d').' '.$prm->time.':00:00'.' +'.$k.' day');
								if($curtime <= $event_time){
									$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
									break;
								}else{
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-d').' '.$prm->time.':00:00'.' +'.($k+7).' day'));
									break;
								}
							}
						}
						
						if(!empty($repset) and !empty($repset['weekly'])){
							$crep = $repset['weekly'];
							$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
							$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
							$curdate = substr($row_event->event_date_begin, 0, 10);
							if($count_d_p > 0){
								for($cd = 0; $cd < $count_d_p; $cd++){
									$xday = ($count_d_p - $cd) * 7;
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 -'.$xday.' day'));
									
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}
							if($count_d_n > 0){
								for($cd = 0; $cd < $count_d_n; $cd++){
									$xday = $cd * 7;
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 +'.$xday.' day'));
									
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}
						}else{
							$represult[] = $row_event;
						}
					}elseif(isset($prm->type) and $prm->type == 2){
						$event_time = strtotime(date('Y-m-').$prm->date.' '.$prm->time.':00:00');
						$lasmonthtime = strtotime(date('Y-m-').'01 23:00:00 +1 month -1 day');
						if($event_time <= $lasmonthtime){
							$row_event->event_date_begin = date('Y-m-d H:i:s', $event_time);
						}elseif(!$thismonth){
							$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime(date('Y-m-').$prm->date.' '.$prm->time.':00:00 +1 month'));
						}
						
						if(!empty($repset) and !empty($repset['monthly'])){
							$crep = $repset['monthly'];
							$count_d_p = isset($crep[0]) ? intval($crep[0]) : 0;
							$count_d_n = isset($crep[1]) ? intval($crep[1]) : 1;
							$curdate = substr($row_event->event_date_begin, 0, 10);
							if($count_d_p > 0){
								for($cd = 0; $cd < $count_d_p; $cd++){
									$xday = $count_d_p - $cd;
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 -'.$xday.' month'));
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}
							if($count_d_n > 0){
								for($cd = 0; $cd < $count_d_n; $cd++){
									$xday = $cd;
									$row_event->event_date_begin = date('Y-m-d H:i:s', strtotime($curdate.' '.$prm->time.':00:00 +'.$xday.' month'));
									$represult[] = $this->__setEventRow($row_event, $row_event->event_date_begin);
								}
							}
						}else{
							$represult[] = $row_event;
						}
					}
					
				}
			}
			
			$rquery = (object)array_merge((array)$represult, (array)$rquery1);
		}else{
            $rquery = $query1->result();
        }
		
		return $rquery;
	}
    
	private function __setEventRow($data, $newDateBegin = ""){
		$curCls = new stdClass();
		if(count($data) > 0){
			foreach($data as $k1 => $r1){
				if($k1 == 'event_date_begin'){
					$curCls->{$k1} = $newDateBegin;
				}else{
					$curCls->{$k1} = $r1;
				}
			}
		}
		return $curCls;
	}
	
	function updateEvent(){
		
		$data = array(
               'event_date_begin' => $_POST['date'],
               'event_title' => $_POST['eventTitle'],
               'event_content' => $_POST['eventContent']
            );
		$this->db->where('id', $_POST['id']);
		$this->db->update($this->db->dbprefix('eventcal'), $data); 
	}
	
	
	function deleteEvent($id){
		return $this->db->delete($this->db->dbprefix('eventcal'), array('id_eventcal' => $id)); 
	}
	
// end of Model/calendar_m.php
}
