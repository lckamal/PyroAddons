<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package 		PyroCMS
 * @subpackage 		Calendar Widget
 * @author			Eko Muhammad Isa
 * 
 * Show Calendar in your site
 */

class Widget_Calendar_month extends Widgets
{
	public $title		= array(
		'en' => 'Monthly Calendar Event'
	);
	public $description	= array(
		'en' => 'Display a list of dates that contain monthly events'
	);
	public $author		= 'Eko Muhammad Isa';
	public $website		= 'http://www.enotes.web.id/';
	public $version		= '0.2';

	private $data;
	
	public function run($options)
	{
		
		if(strpos(__FILE__, 'shared_addons') !== false){
		    $module_path = site_url('addons/shared_addons/modules/calendar/');
		}else{
		    $module_path = site_url('addons/'.SITE_REF.'/modules/calendar/');
		}
		
		
		$this->load->model('calendar/calendar_m');
		$this->lang->load('calendar/calendar');
        
        $this->load->model('variables/variables_m');
        
        $path_display = 'view';
        $vname = 'modcalendar_widget_styles';
        // $v1 = $this->variables_m->get_by('name', $vname);
        if (!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        { 
            $v1 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        if($v1 and $v1->data == 1){
            $path_display = "display";
        }
		
		if(empty($this->data)){ $this->data = new stdClass(); }
        $this->data->path_display = $path_display;
        
    
		// $vstyle = $this->variables_m->get_by('name', 'modcalendar_calendar_style');
        $vname = 'modcalendar_calendar_style';
        if (!$vstyle = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        { 
            $vstyle = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($vstyle, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
		$style_theme = "orig";
		if(!empty($vstyle)){
			$style_theme = $vstyle->data;
		}
		
        // $v2 = $this->variables_m->get_by('name', 'modcalendar_widget_size');
        $vname = 'modcalendar_widget_size';
        if (!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        { 
            $v2 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v2, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        if($v2 and $v2->data == 1){
            $css_name = 'calendar_clip_'.$style_theme.'_small.css';
        }else{
            $css_name = 'calendar_clip_'.$style_theme.'.css';
        }
        
		
        $vname = 'modcalendar_widget_hover';
        // $v2 = $this->variables_m->get_by('name', $vname);
        if (!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        { 
            $v2 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v2, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        if($v2 and $v2->data == 1){
            $js_array = array('js_name'=>'calendar_clip.js');
        }else{
            $js_array = array('js_name'=>'');
        }
        
		// $v4 = $this->variables_m->get_by('name', 'modcalendar_repeat_emersion');
        $vname = 'modcalendar_repeat_emersion';
        if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        {
            $v4 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        $ar_default_repeat = array('daily'=>array(0, 1), 'weekly'=>array(0, 1), 'monthly'=>array(0, 1));
        $this->data->repeat_emersion = empty($v4->data) ? $ar_default_repeat : json_decode($v4->data, true);
        
        $this->set_data(time());
		return array(
			'caldt' => $this->data,
			'css_name' => $css_name,
			'module_path' => $module_path
		)+$js_array;
	}
	
	function set_data($srctime){
 	
        $year = date('Y', $srctime);
        $month = date('m', $srctime);
        $time = strtotime($year.'-'.$month.'-01');
	    // $this->data->events=$this->calendar_m->get_events($time, true, true, true, $this->data->repeat_emersion);
	    $this->data->events = $this->pyrocache->model('calendar_m', 'get_events', array($time, true, true, true, $this->data->repeat_emersion), 14400);
		
	    $today = date("Y/n/j", time());
	    $this->data->today= $today;
	
	    $current_month = date("n", $time);
	    $this->data->current_month = $current_month;
	
	    $current_year = date("Y", $time);
	    $this->data->current_year = $current_year;
	
	    $current_month_text = date("F Y", $time);
	    $this->data->current_month_text = $current_month_text;
	
	    $total_days_of_current_month = date("t", $time);
	    $this->data->total_days_of_current_month= $total_days_of_current_month;
	
	    $first_day_of_month = mktime(0,0,0,$current_month,1,$current_year);
	    $this->data->first_day_of_month = $first_day_of_month;
	
	    //geting Numeric representation of the day of the week for first day of the month. 0 (for Sunday) through 6 (for Saturday).
	    $first_w_of_month = date("w", $first_day_of_month);
	    $this->data->first_w_of_month = $first_w_of_month;
	
	    //how many rows will be in the calendar to show the dates
	    $total_rows = ceil(($total_days_of_current_month + $first_w_of_month)/7);
	    $this->data->total_rows= $total_rows;
	
	    //trick to show empty cell in the first row if the month doesn't start from Sunday
	    $day = -$first_w_of_month;
	    $this->data->day= $day;
	
	    $next_month = mktime(0,0,0,$current_month+1,1,$current_year);
	    $this->data->next_month= $next_month;
	
	    $next_month_text = date("F \'y", $next_month);
	    $this->data->next_month_text= $next_month_text;
	
	    $previous_month = mktime(0,0,0,$current_month-1,1,$current_year);
	    $this->data->previous_month= $previous_month;
	
	    $previous_month_text = date("F \'y", $previous_month);
	    $this->data->previous_month_text= $previous_month_text;
	
	    $next_year = mktime(0,0,0,$current_month,1,$current_year+1);
	    $this->data->next_year= $next_year;
	
	    $next_year_text = date("F \'y", $next_year);
	    $this->data->next_year_text= $next_year_text;
	
	    $previous_year = mktime(0,0,0,$current_month,1,$current_year-1);
	    $this->data->previous_year=$previous_year;
	
	    $previous_year_text = date("F \'y", $previous_year);
	    $this->data->previous_year_text= $previous_year_text;
	
	    //return $this->data;
      
     }
}
