<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar extends Public_Controller {

    protected $section = 'calendar home';
    protected $validation_rules;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('calendar_m');
        $this->lang->load('calendar');
		
		$css_name = 'orig_biggest';
        
		// $v1 = $this->variables_m->get_by('name', 'modcalendar_calendar_style');
        $vname = 'modcalendar_calendar_style';
        if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        {
            $v1 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
		
		// $v2 = $this->variables_m->get_by('name', 'modcalendar_calendar_size');
        $vname = 'modcalendar_calendar_size';
        if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        {
            $v2 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v2, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
		
		// $v2 = $this->variables_m->get_by('name', 'modcalendar_calendar_dateformat');
        $vname = 'modcalendar_calendar_dateformat';
        if(!$v3 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        {
            $v3 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v3, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        
		// $v4 = $this->variables_m->get_by('name', 'modcalendar_repeat_emersion');
        $vname = 'modcalendar_repeat_emersion';
        if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        {
            $v4 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        
		if(empty($this->data)){
			$this->data = new stdClass();
		}
		
		if(!empty($v1->data) and !empty($v2->data)){
			$css_name = $v1->data.'_'.$v2->data;
		}
		
		$ar_default_repeat = array('daily'=>array(0, 1), 'weekly'=>array(0, 1), 'monthly'=>array(0, 1));
		
		$this->data->dateformat = empty($v3->data) ? 'M d, Y H:i' : $v3->data;
		$this->data->repeat_emersion = empty($v4->data) ? $ar_default_repeat : json_decode($v4->data, true);
		$rco = $this->data->repeat_emersion;
		$this->data->repeat_count = 0;
		if(!empty($rco) and !empty($rco['daily']) and !empty($rco['weekly']) and !empty($rco['monthly'])){
			$this->data->repeat_count = intval(@$rco['daily'][0])+intval(@$rco['daily'][1])+intval(@$rco['weekly'][0])+intval(@$rco['weekly'][1])+intval(@$rco['monthly'][0])+intval(@$rco['monthly'][1]);
		}
		
        $this->template->append_css('module::calendar_'.$css_name.'.css');
        
        
    }
  
  

    function index(){

        // The forth segment will be used as timeid
        $timeid = $this->uri->segment(3);
        if($timeid==0 or strlen($timeid) == 0){
            $this->load->model('variables/variables_m');
            $vname = 'modcalendar_home_styles';
            // $v1 = $this->variables_m->get_by('name', $vname);
            if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v1 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v1 and $v1->data == 1){
                redirect('calendar/display');
            }
            
            $time = time();
            
        }else{
            $time = $timeid;
        }
		
        $this->data->path_src = "show";
        $this->_date($time);
        $this->template
                ->append_js('module::calendar_ori.js')
                ->set('menu_calendar', $this->_set_menu())
                ->build('index', $this->data);
    } 
  
    function show(){
        $this->index();
    }
  
	function display($year = '', $month = '', $day = '', $page = 1){
        
		
		$check_date = false;
		$arprm = array();
		$dtpost = $this->input->post();
		$_POST = null;
        if(!empty($dtpost) and count($dtpost) > 0){
            $arprm = array(
               'date_start'  => $dtpost['txs_from'],
               'date_end'     => $dtpost['txs_to'],
               'title' => $dtpost['txs_title'],
               'getrepeat' => (!empty($dtpost['txs_repeat']) ? $dtpost['txs_repeat'] : 0)
            );
            
            if(empty($dtpost['txs_from']) and empty($dtpost['txs_title'])){
                $this->session->unset_userdata('search_prm');
                $check_date = true;
            }else{
                $search_prm = json_encode($arprm);
                $this->session->set_userdata('search_prm', $search_prm);
            }
            $year = '0'; $month = '0'; $day = '0';

        }elseif($this->session->userdata('search_prm')){
            if(empty($dtpost['txs_from']) and empty($dtpost['txs_title'])){
                $this->session->unset_userdata('search_prm');
                $check_date = true;
            }else{
                $dtsearch = $this->session->userdata('search_prm');
                $arprm = (array)json_decode($dtsearch);
            }
            $year = '0'; $month = '0'; $day = '0';
 
        }else{
            $check_date = true;
        }
        
        if($check_date){
            if(empty($year) or empty($month) or empty($day)){
                $year = '0'; $month = '0'; $day = '0';
            }else{
            
                if(checkdate($month, $day, $year)){
                    $date_load = date('Y-m-d', strtotime(trim($year).'-'.trim($month).'-'.trim($day)));
                    $show_all = false;
                    $arprm = array(
                       'date_start'  => $date_load
                    );
                }else{
                    $year = '0'; $month = '0'; $day = '0';
                }
            }
            if(!isset($arprm['getrepeat'])){
		$arprm['getrepeat'] = 1;
	    }
            // echo "<br/>dbg3<br/>";
            // print_r($arprm);
        }
		
        $this->data->search_prm = $arprm;

		// $max_row = $this->calendar_m->count_event_by($arprm, 1);
		$max_row = $this->pyrocache->model('calendar_m', 'count_event_by', array($arprm, 1));
		// $max_row = $max_row + $this->data->repeat_count-1;
		
		$paging_param = array('page'=>$page, 'maxrow' => $max_row, 'pagerow' => 10, 'wide' => 2, 
		'url'=> site_url('calendar/display/'.$year.'/'.$month.'/'.$day.'/%s'));
        $this->load->library('libpaging');
		$this->data->pagination = $this->libpaging->_paging($paging_param);
		
		
		//$this->data->data_read = $this->calendar_m->list_event_by($arprm+array('limit'=>$this->data->pagination['limit'], 'order'=>'event_date_begin ASC', 'repeat_setting'=>$this->data->repeat_emersion));
		$this->data->data_read = $this->pyrocache->model('calendar_m', 'list_event_by', array($arprm+array('limit'=>$this->data->pagination['limit'], 'order'=>'event_date_begin ASC', 'repeat_setting'=>$this->data->repeat_emersion)));
		
		$this->data->flash_message = $this->_flash_message();
		
		$this->template
			->title($this->module_details['name'])
			->set_breadcrumb( lang('calendar_home_label'), 'calendar')
			//->set_breadcrumb( date('M d, Y', strtotime($date_load)))
			->set_metadata('description', lang('calendar_home_label'))
			->set_metadata('keywords', lang('calendar_style_list'))
			->append_js('module::datepicker.js')
			->append_js('module::calendar_list.js')
			->append_css('module::datepicker.css')
            ->set('menu_calendar', $this->_set_menu())
			->build('display', $this->data);

	}
	
	function view(){
        $timeid = $this->uri->segment(3);
        if($timeid==0 or strlen($timeid) == 0){
            $time = time();
        }else{
            $time = $timeid;
        }

        $this->data->path_src = "view";
        $this->_date($time);
        $this->template
                ->append_js('module::calendar_ori.js')
                ->set('menu_calendar', $this->_set_menu())
                ->build('index', $this->data);
	}
	
	function detail($data_read = 0, $date_str = ""){
        if(empty($data_read)){
            $this->session->set_flashdata('notice', lang('calendar_invalid_param'));
			redirect('calendar/display');
        }
		
		$last_date_str = strpos($date_str, '.');
		if($last_date_str !== false && $last_date_str == 13){
			$this->data->date_str = substr($date_str, 0, 10).' '.substr($date_str, 11, 2);
			$this->data->date_url = substr($date_str, 0, 13);
		}else{
			$this->data->date_str = "";
			$this->data->date_url = "";
		}
		
		// $this->data->data_read = $this->calendar_m->get_event_by_id($data_read);
		$this->data->data_read = $this->pyrocache->model('calendar_m', 'get_event_by_id', array($data_read));

		$this->template
			->title($this->module_details['name'])
			->set_breadcrumb( lang('calendar_home_label'), 'calendar')
			->set_breadcrumb( $this->data->data_read->event_title )
			->set_metadata('description', lang('calendar_home_label'))
			->set_metadata('keywords', lang('calendar_detail_label'))
			->build('detail', $this->data);

	}
  
  

    function dayevents ($date = NULL)
    {
        // Date ranges for select boxes
        $this->data->hours = array_combine($hours = range(0, 23), $hours);
        $this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
        

        //add post values to base_where if f_module is posted
        $base_where = $this->input->post('f_title') ? array('title' => $this->input->post('f_title')) : array();
        //$base_where = $this->input->post('f_date') ? $base_where + array('date' => $this->input->post('f_date')) : $base_where;

        // Create pagination links
        // $total_rows = $this->calendar_m->count_event_by($base_where + array('date'=> $date));
        $total_rows = $this->pyrocache->model('calendar_m', 'count_event_by', array($base_where + array('date'=> $date)));
		// $total_rows = $total_rows + $this->data->repeat_count-1;
        $pagination = create_pagination('admin/calendar/list/', $total_rows);

        // Using this data, get the relevant results
        // $calendar = $this->calendar_m->list_event_by($base_where + array('date'=> $date, 'limit'=> $pagination['limit'], 'order' => 'event_date_begin desc', 'repeat_setting'=>$this->data->repeat_emersion));
        $calendar = $this->calendar_m->$this->pyrocache->model('calendar_m', 'list_event_by', array($base_where + array('date'=> $date, 'limit'=> $pagination['limit'], 'order' => 'event_date_begin desc', 'repeat_setting'=>$this->data->repeat_emersion)));
        
        //do we need to unset the layout because the request is ajax?
        $this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';

        $this->template
            ->title($this->module_details['name'])
            ->append_js('admin/filter.js')
            ->set('pagination', $pagination)
            ->set('calendar', $calendar);
        
        
        //$this->input->is_ajax_request() ? $this->template->build('admin/tables/posts', $this->data) : 
        $this->template->build('admin/index', $this->data);
    }

 
 function _date($srctime){
 	
    $year = date('Y', $srctime);
    $month = date('m', $srctime);
    $time = strtotime($year.'-'.$month.'-01');
    
	// $this->data->events=$this->calendar_m->get_events($time, false, true, true, $this->data->repeat_emersion );
	$this->data->events = $this->pyrocache->model('calendar_m', 'get_events', array($time, false, true, true, $this->data->repeat_emersion));
	
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
 
	private function _flash_message($custom_msg = array()){
		$flash_html = "";
		if($this->session->flashdata('error')){
			$flash_html .= "<div class=\"alert error\">".$this->session->flashdata('error')."</div>";
		}
		//echo "<br/>dbg1:".validation_errors()."<br/>";
		if(validation_errors()){
			$flash_html .= "<div class=\"alert error\">".validation_errors()."</div>";
		}
		if(! empty($messages['error'])){
			$flash_html .= "<div class=\"alert error\">".$messages['error']."</div>";
		}
		if($this->session->flashdata('notice')){
			$flash_html .= "<div class=\"alert warning\">".$this->session->flashdata('notice')."</div>";
		}
		if(! empty($messages['notice'])){
			$flash_html .= "<div class=\"alert warning\">".$messages['notice']."</div>";
		}
		if($this->session->flashdata('success')){
			$flash_html .= "<div class=\"alert success\">".$this->session->flashdata('success')."</div>";
		}
		if(! empty($messages['success'])){
			$flash_html .= "<div class=\"alert success\">".$messages['success']."</div>";
		}
		
		if(! empty($custom_msg)){
			$flash_html .= "<div class=\"alert ".$custom_msg['status']."\">".$custom_msg['message']."</div>";
		}
		
		return $flash_html;
	}

	private function _set_menu(){
		$menu_html = "";
		
        $vname = 'modcalendar_menu_status';
        if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
        { 
            $v1 = $this->variables_m->get_by('name', $vname);
            $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        }
        if($v1 and $v1->data == 1){
            $menu_html .= "<div class=\"cal_menu\">";
            $menu_html .= "<a href=\"".site_url('calendar/display')."\">".lang('calendar_style_list')."</a>";
            $menu_html .= "<a href=\"".site_url('calendar/view')."\">".lang('calendar_style_calendar')."</a>";
            $menu_html .= "</div>";
        }
        return $menu_html;
	}

}//end class
?>
