<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

    protected $section = 'calendar home';
    protected $validation_rules;
	private $data;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('calendar_m');
        $this->lang->load('calendar');
        $this->template->append_css('module::calendar_admin.css');
        
        
        $this->validation_rules = array(
            array(
                'field' => 'event_title',
                'label' => 'lang:calendar_title_label',
                'rules' => 'trim|htmlspecialchars|required|max_length[255]'
            ),
            array(
                'field' => 'event_date_begin',
                'label' => 'lang:calendar_date_start_label',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'date_s_hour',
                'label' => 'lang:calendar_created_hour',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'date_s_minute',
                'label' => 'lang:calendar_created_minute',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'event_date_end',
                'label' => 'lang:calendar_date_end_label',
                'rules' => 'trim'
            ),
            array(
                'field' => 'date_e_hour',
                'label' => 'lang:calendar_created_hour',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'date_e_minute',
                'label' => 'lang:calendar_created_minute',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'event_repeat',
                'label' => 'lang:calendar_event_repeat_label',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'event_repeat_prm',
                'label' => 'lang:calendar_event_repeat_label',
                'rules' => 'trim'
            ),
            array(
                'field' => 'event_content',
                'label' => 'lang:calendar_content_label',
                'rules' => 'trim'
            )
        );
		
		if(empty($this->data)){
			$this->data = new stdClass();
		}
        
    }
  
  

  function index(){
	// The forth segment will be used as timeid
	$timeid = $this->uri->segment(4);
	if($timeid==0)
		$time = time();
	else
		$time = $timeid;
	
	    $this->_date($time);
        $this->template
                ->append_js('module::calendar.js')
                ->build('admin/calendar_home', $this->data);
				
  }
  
	public function setting()
	{
	
		if($this->current_user->group != 'admin'){
			redirect('users/login/users');
		}
		$this->load->library('form_validation');
		
		$vrules = array(
			array(
				'field' => 'set_calendar_default',
				'label' => 'lang:calendar_home_default_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_widget_default',
				'label' => 'lang:calendar_widget_default_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_menu_status',
				'label' => 'lang:calendar_menu_status_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_widget_size',
				'label' => 'lang:calendar_widget_size_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_widget_hover',
				'label' => 'lang:calendar_widget_hover_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_calendar_size',
				'label' => 'lang:calendar_size_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_calendar_style',
				'label' => 'lang:calendar_style_label',
				'rules' => 'trim'
			),
			array(
				'field' => 'set_calendar_dateformat',
				'label' => 'lang:calendar_dateformat_label',
				'rules' => 'trim'
			)
		);
		
		$this->form_validation->set_rules($vrules);
		$this->load->model('variables/variables_m');
        
		if ($this->form_validation->run())
		{
		    
			
            if ($this->input->post('set_calendar_default') >= 0)
		    {
			    //$this->member_m->set_styles($this->input->post('set_calendar_default'));
			    $vname = 'modcalendar_home_styles';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    
			    if($v1 == 0){
			        
                    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_default')
				    );
				    $this->variables_m->insert($ardata);
				}else{

                    if (!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_default')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            
            if ($this->input->post('set_widget_default') >= 0)
		    {
			    //$this->member_m->set_styles($this->input->post('set_widget_default'));
			    $vname = 'modcalendar_widget_styles';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_default')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_default')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            if ($this->input->post('set_menu_status') >= 0)
		    {
			    //$this->member_m->set_styles($this->input->post('set_menu_status'));
			    $vname = 'modcalendar_menu_status';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_menu_status')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_menu_status')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            if ($this->input->post('set_widget_size') >= 0)
		    {
			    //$this->member_m->set_styles($this->input->post('set_widget_size'));
			    $vname = 'modcalendar_widget_size';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_size')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_size')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
			
            if ($this->input->post('set_widget_hover') >= 0)
		    {
			    //$this->member_m->set_styles($this->input->post('set_widget_hover'));
			    $vname = 'modcalendar_widget_hover';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_hover')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_widget_hover')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            
            if($this->input->post('set_calendar_size'))
		    {
			    //$this->member_m->set_styles($this->input->post('set_calendar_size'));
			    $vname = 'modcalendar_calendar_size';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_size')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_size')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
        		
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
                
		    }
		    
            if($this->input->post('set_calendar_style'))
		    {
			    //$this->member_m->set_styles($this->input->post('set_calendar_style'));
			    $vname = 'modcalendar_calendar_style';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_style')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_style')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            if($this->input->post('set_calendar_dateformat'))
		    {
			    //$this->member_m->set_styles($this->input->post('set_calendar_dateformat'));
			    $vname = 'modcalendar_calendar_dateformat';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_dateformat')
				    );
				    $this->variables_m->insert($ardata);
				}else{
				    
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $this->input->post('set_calendar_dateformat')
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
		    
            
            if($this->input->post('set_repeat_weekly_p'))
		    {
			    $arr_repeat_prms = array(
					'daily'=>array($this->input->post('set_repeat_daily_p'),$this->input->post('set_repeat_daily_n')),
					'weekly'=>array($this->input->post('set_repeat_weekly_p'),$this->input->post('set_repeat_weekly_n')),
					'monthly'=>array($this->input->post('set_repeat_monthly_p'),$this->input->post('set_repeat_monthly_n')),
			    ); 
			    $arr_repeat_prms_json = json_encode($arr_repeat_prms);
			    $vname = 'modcalendar_repeat_emersion';
                if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname))
                { 
                    $v1 = $this->variables_m->check_name($vname);
                    $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                }
			    
			    if($v1 == 0){
			        $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $arr_repeat_prms_json
				    );
				    $this->variables_m->insert($ardata);
				}else{
                    if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
                    { 
                        $v2 = $this->variables_m->get_by('name', $vname);
                    }
				    $ardata = array(
				        'name'				=> $vname,
				        'data'				=> $arr_repeat_prms_json
				    );
				    $this->variables_m->update($v2->id, $ardata);
				}
				$_POST['set_repeat_emersion'] = $arr_repeat_prms_json;
                
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'check_name_'.$vname);
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
        		
		    }
            
            
			$this->session->set_flashdata('success', $this->lang->line('member_process_finish'));
			$post = (object)$this->input->post();
			
		}else{
		
			if(empty($post)){
				$post = new stdClass();
			}
			$vname = 'modcalendar_home_styles';
            if(!$v1 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v1 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v1, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v1 and $v1->data == 1){
				$post->set_calendar_default = 1;
			}else{
				$post->set_calendar_default = 0;
			}
            
			$vname = 'modcalendar_widget_styles';
            if(!$v2 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v2 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v2, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v2 and $v2->data == 1){
				$post->set_widget_default = 1;
			}else{
				$post->set_widget_default = 0;
			}
            
			$vname = 'modcalendar_menu_status';
            if(!$v3 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v3 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v3, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v3 and $v3->data == 1){
				$post->set_menu_status = 1;
			}else{
				$post->set_menu_status = 0;
			}
            
			$vname = 'modcalendar_widget_size';
            if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v4 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v4 and $v4->data == 1){
				$post->set_widget_size = 1;
			}else{
				$post->set_widget_size = 0;
			}
            
			$vname = 'modcalendar_widget_hover';
            if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v4 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v4 and $v4->data == 1){
				$post->set_widget_hover = 1;
			}else{
				$post->set_widget_hover = 0;
			}
            
			$vname = 'modcalendar_calendar_size';
            if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v4 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v4){
				$post->set_calendar_size = $v4->data;
			}else{
				$post->set_calendar_size = 'biggest';
			}
            
			$vname = 'modcalendar_calendar_style';
            if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v4 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v4){
				$post->set_calendar_style = $v4->data;
			}else{
				$post->set_calendar_style = 'orig';
			}
			
			$vname = 'modcalendar_calendar_dateformat';
            if(!$v4 = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $v4 = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($v4, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
			if($v4){
				$post->set_calendar_dateformat = $v4->data;
			}else{
				$post->set_calendar_dateformat = 'M d, Y H:i';
			}
			
			
			$vname = 'modcalendar_repeat_emersion';
            if(!$vr = $this->pyrocache->get('calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname))
            { 
                $vr = $this->variables_m->get_by('name', $vname);
                $this->pyrocache->write($vr, 'calendar_cache'.DIRECTORY_SEPARATOR.'get_by_'.$vname);
            }
            
			if($vr){
				$post->set_repeat_emersion = $vr->data;
			}else{
				$post->set_repeat_emersion = null;
			}
		}
		
		$this->template
			->title($this->module_details['name'])
			->set('post', $post)
			->build('admin/settings', $this->data);

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
		//$total_rows = $this->calendar_m->count_event_by($base_where + array('date'=> $date));
        $total_rows = $this->pyrocache->model('calendar_m', 'count_event_by', array($base_where + array('date'=> $date)));
		$pagination = create_pagination('admin/calendar/list/', $total_rows);

		// Using this data, get the relevant results
		//$calendar = $this->calendar_m->list_event_by($base_where + array('date'=> $date));
        $calendar = $this->pyrocache->model('calendar_m', 'list_event_by', array($base_where + array('date'=> $date, 'limit'=> $pagination['limit'], 'order' => 'event_date_begin desc')));

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

  
    function list_calendar(){
	
        if(empty($this->data)){
			$this->data = new stdClass();
		}
        // Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
        

		//add post values to base_where if f_module is posted
		$base_where = $this->input->post('f_title') ? array('title' => $this->input->post('f_title')) : array();
		$base_where = $this->input->post('f_date') ? $base_where + array('date' => $this->input->post('f_date')) : $base_where;

		// Create pagination links
		// $total_rows = $this->calendar_m->count_event_by($base_where);
		$total_rows = $this->pyrocache->model('calendar_m', 'count_event_by', array($base_where+array('getrepeat'=>1)));
		$pagination = create_pagination('admin/calendar/list/', $total_rows);

		// Using this data, get the relevant results
		// $calendar = $this->calendar_m->list_event_by($base_where + array('limit'=> $pagination['limit'], 'order' => 'event_date_begin desc'));
		$calendar = $this->pyrocache->model('calendar_m', 'list_event_by', array($base_where + array('getrepeat'=>1, 'limit'=> $pagination['limit'], 'order' => 'event_date_begin desc')));

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
  
    function create($date_begin = ''){
	
        if(empty($this->data)){
			$this->data = new stdClass();
		}
        // Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
        
        $this->load->library('form_validation');

		$this->form_validation->set_rules($this->validation_rules);

        if ($this->input->post('event_date_begin'))
        {
            $date_begin = sprintf('%s %s:%s', $this->input->post('event_date_begin'), $this->input->post('date_s_hour'), $this->input->post('date_s_minute'));
        }else{
            if(strlen($date_begin) > 7){
                $date_begin = date('Y-m-d', strtotime($date_begin));
            }else{
                $date_begin = date('Y-m-d H:i:s');
            }
        }
        
        
        $dbg_date_end = $this->input->post('event_date_end');
        //echo "<br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> <br/> data = ".$dbg_date_end."|";
        if(empty($dbg_date_end) or strpos($dbg_date_end, '1970-01-01')){
            $date_end = '';
            $date_end_hour = '';
            $date_end_minute = '';
            $date_end_saved = NULL;
        }else{
            $date_end = date('Y-m-d', strtotime($dbg_date_end));
            $date_end_hour = $this->input->post('date_e_hour'); //date('H', strtotime($dbg_date_end));
            $date_end_minute = $this->input->post('date_e_minute'); //date('i', strtotime($dbg_date_end), '0');
            $date_end_saved = sprintf('%s %s:%s', $date_end, $date_end_hour, $date_end_minute);
        }
        
		if ($this->form_validation->run())
		{
			
            
            /* 
            // They are trying to put this live
			if ($this->input->post('status') == 'live')
			{
				role_or_die('calendar', 'put_live', 'admin/calendar/create', 'Sorry. You are not allowed to set live (publish) calendar.');
			}
             */
            $repeat_prm = array(
				'type'=> $this->input->post('repeat_type'),
				'time'=> $this->input->post('repeat_time'),
				'day'=> $this->input->post('repeat_day'),
				'date'=> $this->input->post('repeat_date')
			);
			
			$id = $this->calendar_m->add_event(array(
				'event_date_begin'		=> $date_begin,
				'event_date_end'		=> $date_end_saved,
                'event_title'			=> $this->input->post('event_title'),
				'event_content'			=> $this->input->post('event_content'),
				'event_repeat'			=> $this->input->post('event_repeat'),
				'event_repeat_prm'		=> json_encode($repeat_prm),
                'user_id'			    => $this->current_user->id
			));

			if ($id)
			{
				$this->pyrocache->delete_all('calendar_m');
				$this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_read');
				$this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_count');
				$this->session->set_flashdata('success', sprintf($this->lang->line('calendar_add_success'), $this->input->post('event_title')));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('calendar_add_error'));
			}

			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/calendar') : redirect('admin/calendar/edit/' . $id);
		}
		else
		{
			if(empty($post)){
				$post = new stdClass();
			}
			// Go through all the known fields and get the post values
			foreach ($this->validation_rules as $key => $field)
			{
				$post->$field['field'] = set_value($field['field']);
			}
			
            $post->event_date_begin = $date_begin;
            $post->event_date_end = $date_end;
            $post->event_date_end_hour = $date_end_hour;
            $post->event_date_end_minute = $date_end_minute;

			
		}
        
		$this->template
				->title($this->module_details['name'], lang('calendar_create_title'))
				->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
				->append_js('module::calendar.js')
				->set('post', $post)
				->build('admin/calendar_create', $this->data);

	}
		
  function edit($id=0){
	
        
        // Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
        
        $this->load->library('form_validation');
        
        // $post = $this->calendar_m->get_event_by_id($id);
        $post = $this->pyrocache->model('calendar_m', 'get_event_by_id', array($id));
        
		$this->form_validation->set_rules($this->validation_rules);

        if ($this->input->post('event_date_begin'))
        {
            $date_begin = sprintf('%s %s:%s', $this->input->post('event_date_begin'), $this->input->post('date_s_hour'), $this->input->post('date_s_minute'));
        }else{
            $date_begin = $post->event_date_begin;
        }
        
        $dbg_date_end = $this->input->post('event_date_end');
        
        if(empty($dbg_date_end) or strpos($dbg_date_end, '1970-01-01')){
            if(strlen($post->event_date_end) > 9){
                $date_end = date('Y-m-d', strtotime($post->event_date_end));
                $date_end_hour = date('H', strtotime($post->event_date_end));
                $date_end_minute = date('i', strtotime($post->event_date_end));
                $date_end_saved = $post->event_date_end;
            }else{
                $date_end = '';
                $date_end_hour = '';
                $date_end_minute = '';
                $date_end_saved = NULL;
            }
        }else{
            $date_end = date('Y-m-d', strtotime($dbg_date_end));
            $date_end_hour = ($this->input->post('date_e_hour')) ? $this->input->post('date_e_hour') : date('H', strtotime($dbg_date_end));
            $date_end_minute = ($this->input->post('date_e_minute')) ? $this->input->post('date_e_minute') : date('i', strtotime($dbg_date_end));
            $date_end_saved = sprintf('%s %s:%s', $date_end, $date_end_hour, $date_end_minute);
        }
        
		
		if ($this->form_validation->run())
		{
            /* 
            // They are trying to put this live
			if ($this->input->post('status') == 'live')
			{
				role_or_die('calendar', 'put_live', 'admin/calendar/create', 'Sorry. You are not allowed to set live (publish) calendar.');
			}
             */
            // echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/>";
			$repeat_prm = array(
				'type'=> $this->input->post('repeat_type'),
				'time'=> $this->input->post('repeat_time'),
				'day'=> $this->input->post('repeat_day'),
				'date'=> $this->input->post('repeat_date')
			);
			// print_r($repeat_prm);
			// end;
			$hsl = $this->calendar_m->edit_event($id, array(
				'event_date_begin'		=> $date_begin,
				'event_date_end'		=> $date_end_saved,
                'event_title'			=> $this->input->post('event_title'),
				'event_content'			=> $this->input->post('event_content'),
				'event_repeat'			=> $this->input->post('event_repeat'),
				'event_repeat_prm'		=> json_encode($repeat_prm),
				'user_id'			    => $this->current_user->id
			));

			if ($hsl)
			{
				$this->pyrocache->delete_all('calendar_m');
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_read');
				$this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_count');
				$this->session->set_flashdata('success', sprintf($this->lang->line('calendar_edit_success'), $this->input->post('event_title')));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('calendar_edit_error'));
			}

			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/calendar') : redirect('admin/calendar/edit/' . $id);
		}
        
        
        // Go through all the known fields and get the post values
		foreach (array_keys($this->validation_rules) as $field)
		{
			if (isset($_POST[$field]))
			{
				$post->$field = $this->form_validation->$field;
			}
		}

		$post->event_date_begin = $date_begin;
		$post->event_date_end = $date_end;
		$post->event_date_end_hour = $date_end_hour;
		$post->event_date_end_minute = $date_end_minute;
        $post->repeat_prm = @json_decode(@$post->event_repeat_prm);
        
		$this->template
				->title($this->module_details['name'], lang('calendar_edit_title'))
				->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
				->append_js('module::calendar.js')
				->set('post', $post)
				->build('admin/calendar_edit', $this->data);

	}
		
		



	function update($id=0){
	
		if(isset($_POST['add']))
		{
			//check for empty inputs
			if((isset($_POST['date']) && !empty($_POST['date'])) && (isset($_POST['eventTitle']) && !empty($_POST['eventTitle'])) && (isset($_POST['eventContent']) && !empty($_POST['eventContent'])))	
			{
				//update event to the database
				$this->calendar_m->updateEvent();
                $this->pyrocache->delete_all('calendar_m');
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_read');
				$this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_count');
                $this->session->set_flashdata(array('success'=> 'Event updated!'));
				redirect('admin/calendar/index');
			}
			else 
			{
				//alert message for empty input
				$data['alert'] = "No empty input please";
			}
		}
		$this->session->set_flashdata('message', 'Please fill up the information');
		redirect('admin/calendar/update');
		
	}
	
	function delete($id=0){

		// Delete one
		$ids = ($id) ? array($id) : $this->input->post('action_to');

		// Go through the array of slugs to delete
		if ( ! empty($ids))
		{
			$post_titles = array();
			foreach ($ids as $id)
			{
				$this->calendar_m->deleteEvent($id);

				// Wipe cache for this model, the content has changed
				$this->pyrocache->delete_all('calendar_m');
                $this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_read');
				$this->pyrocache->delete('calendar_cache'.DIRECTORY_SEPARATOR.'cache_modcalendar_plugin_count');
				$post_titles[] = '';
			}
		}

		// Some pages have been deleted
		if ( ! empty($post_titles))
		{
			$this->session->set_flashdata('success', $this->lang->line('calendar_delete_success'));
			
		}
		// For some reason, none of them were deleted
		else
		{
			$this->session->set_flashdata('notice', lang('calendar_delete_error'));
		}

		redirect('admin/calendar/list_calendar');
	}

 
 function _date($srctime){
 	
    $year = date('Y', $srctime);
    $month = date('m', $srctime);
    $time = strtotime($year.'-'.$month.'-01');
	// $this->data->events=$this->calendar_m->get_events($time);
	if(empty($this->data)){
		$this->data = new stdClass();
	}
	$this->data->events = $this->pyrocache->model('calendar_m', 'get_events', array($time));

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
	
	return $this->data;
  
 }
 
    
	/**
	 * method to fetch filtered results for calendar list
	 * @access public
	 * @return void
	 */
	public function ajax_filter()
	{
		$title = $this->input->post('f_title');
		$date = $this->input->post('f_date');

		$post_data = array();

		if (strlen($title) > 0)
		{
			$post_data['title'] = $title;
		}

		if (strlen($date) > 0)
		{
			$post_data['date'] = $date;
		}

		// $results = $this->calendar_m->list_event_by($post_data+ array('order' => 'event_date_begin desc'));
		$results = $this->pyrocache->model('calendar_m', 'list_event_by', array($post_data+ array('getrepeat'=>1, 'order' => 'event_date_begin desc')));

		//set the layout to false and load the view
		$this->template
				->set_layout(FALSE)
				->set('calendar', $results)
			->build('admin/table_list');
	}
	
	
	/**
	 * Helper method to determine what to do with selected items from form post
	 * @access public
	 * @return void
	 */
	public function action()
	{
		switch ($this->input->post('btnAction'))
		{
			
			case 'delete':
				role_or_die('calendar', 'delete_live', 'admin/calendar/list_calendar', 'Sorry. You are not allowed to delete event.');
				$this->delete();
				break;
			
			default:
				redirect('admin/calendar/list_calendar');
				break;
		}
	}
	
	/**
	 * Method to check login user is admin or not
	 * @access public
	 * @return void
	 */
	public function ajax_group()
	{
		if($this->current_user->group == 'admin'){
			echo "ok";
		}else{
			echo "nok";
		}
	}

}//end class
?>
