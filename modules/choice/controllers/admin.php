<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Choices module
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Streams module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'choice';

    protected $data;
	
    public function __construct()
    {
        parent::__construct();

        $this->lang->load('choice');

        $this->load->driver('Streams');
        $this->load->model('choice/choice_m');
        $this->data->layout = $this->input->get('layout') ? $this->input->get('layout') : 'default';
        $this->template->set_layout($this->data->layout);
    }

    // --------------------------------------------------------------------------

    /**
     * List all Choices using Streams Entries Driver and building a custom template
     *
     * @access  public
     * @return  void
     */
    public function index()
    {
        $this->data->choices = $this->db->where(array('field_type' => 'dbchoice'))->get('data_fields')->result_array();

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    public function view($field_slug = null)
    {
        $this->data->field = $this->db->where(array('field_slug' => $field_slug))->get('data_fields')->row();
        $this->data->field_choices = $this->choice_m->where(array('field_slug' => $field_slug, 'choice_lang' => AUTO_LANGUAGE))->get_all();

        // Build the page
        $this->template->title($this->module_details['name']. " &raquo; ".$field_slug)
                ->append_js('module::entry_sorting.js')
                ->build('admin/view', $this->data);

    }
    // --------------------------------------------------------------------------

    /**
     * Create a new Choice entry
     *
     * @access	public
     * @return	void
     */
    public function create($field_slug = null, $choice_id = 0)
    {
        $this->data->field = $this->db->where(array('field_slug' => $field_slug))->get('data_fields')->row();

        //edit mode
        if($choice_id > 0)
        {
            $choices = $this->choice_m->where('choice_id', $choice_id)->get_all();
            if($choices)
            {
                $mychoice = array();
                foreach ($choices as $key => $choice) {
                    $mychoice['choice_id'] = $choice->choice_id;
                    $mychoice['field_slug'] = $choice->field_slug;
                    $mychoice[$choice->choice_lang]['id'] = $choice->id;
                    $mychoice[$choice->choice_lang]['choice_title'] = $choice->choice_title;
                }
            }
            $this->data->choice = $mychoice;
        }

		if($this->input->post())
		{
			if($this->validate_choice() === true)
			{
				if($this->save_choices($field_slug))
				{
					$this->session->set_flashdata('success', lang('choice:submit_success'));
					redirect('admin/choice/view/'.$field_slug);
				}
				else{
					$this->session->set_flashdata('error', lang('choice:submit_failure'));
				}
			}
		}
        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/form', $this->data);
    }
	
    public function edit($field_slug = null, $choice_id = 0)
    {
        $this->create($field_slug, $choice_id);
    }
    // --------------------------------------------------------------------------

    /**
     * Delete a Choice entry
     * 
     * @access  public
     * @param   int $id The id of Choice to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($field_slug = null, $id = 0)
    {
        $this->choice_m->delete_by('choice_id', $id);
        $this->session->set_flashdata('error', lang('choice:deleted'));
        redirect('admin/choice/view/'.$field_slug);
    }
    
    private function save_choices($field_slug = null)
	{
		$choice_inputs = $this->input->post();
	    unset($choice_inputs['btnAction']);
	    
	    foreach($choice_inputs as $lang_code => $inputs)
	    {	
	    	if(in_array($lang_code,	array_keys($this->langs)))
	    	{
		    	$choice_data = array(
		    		'choice_title' => $choice_inputs[$lang_code]['choice_title'],
		    		'choice_lang' => $lang_code,
                    'field_slug' => $field_slug
		    	);
		    	if(isset($inputs['id']))
		    	{
                    $this->choice_m->update($inputs['id'], $choice_data);
		    	}
		    	else{
		    		if($lang_code == $this->site_lang)
			    	{
				    	$choice_id = $this->choice_m->insert($choice_data);
				    	if($choice_id)
				    	{
					    	$this->choice_m->update($choice_id, array('choice_id' => $choice_id));
				    	}
			    	}
			    	else{
				    	$choice_data['choice_id'] = $choice_id;
						$this->choice_m->insert($choice_data);
			    	}
		    	}
	    	}
	    	
	    }
	    return true;
	}
	
	/**
	* Validate each language inputs
	*/
    private function validate_choice()
    {
    	
	    $choice_inputs = $this->input->post();
	    unset($choice_inputs['btnAction']);
	    $site_lang = Settings::get('site_lang');

        foreach($this->langs as $lang_code => $lang)
        {
            $this->form_validation->set_rules($lang_code.'[choice_title]', 'Title ' .$lang_code, 'trim|required');
        }
	    if($this->form_validation->run() === false) return false;
	    
	    return true;
    }

}
/* End of file admin.php */
