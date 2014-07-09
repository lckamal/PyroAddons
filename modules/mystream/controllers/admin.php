<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Stream administration
 * 
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Stream
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'mystream';

    protected $data;

    public function __construct()
    {
        parent::__construct();
		
		$this->data = new stdClass();
		$this->lang->load('mystream');
        $this->load->driver('Streams');
        $this->load->model('mystream/mystream_m');
    }

    // --------------------------------------------------------------------------

    /**
     * List all streams using Streams CP Driver
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('mystream:streams');
        $extra['buttons'] = array(
            array(
                'label' => lang('stream:fields'),
                'url' => 'admin/mystream/fields/index/-entry_id-'
            ),
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/mystream/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/mystream/delete/-entry_id-',
                'confirm' => true
            )
        );

        $this->streams->cp->entries_table('data_streams', 'mystream', 100, 'admin/mystream/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all streams
     *
     * @access  public
     * @return  void
     */
    public function _index()
    {
        $params = array(
            'stream' => 'data_streams',
            'namespace' => 'mystream',
            'paginate' => 'no',
            'pag_segment' => 3
        );

        $this->data->streams = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new stream entry
     *
     * @access	public
     * @return	void
     */
    public function create()
    {
        if ($post = $this->input->post())
        {
            $stream_validation = $this->streams->streams->validation_array('data_streams', 'mystream');

            $this->form_validation->set_rules($stream_validation);
            if ($this->form_validation->run())
            {
                $status = $this->streams->streams->add_stream($post['stream_name'], $post['stream_slug'], $post['stream_namespace'], $post['stream_prefix'], $post['about'], 
                            array('title_column' => $post['title_column']));
                if($status){
                    $this->session->set_flashdata('success', 'Stream added successfully');                    
                    redirect('admin/mystream');
                }
                else{
                    $this->session->set_flashdata('error', 'There was a problem adding stream.');  
                    redirect('admin/mystream/create');                 
                }
            }
        }

        $this->data->exclude_fields = array('sorting', 'is_hidden', 'view_options');
        $this->data->stream_fields = $this->streams->fields->get_stream_fields('data_streams', 'mystream', $this->input->post());
        $this->template->title($this->module_details['name'])
                ->build('admin/form', $this->data);
    }

    public function _create()
    {
        $extra = array(
            'return' => 'admin/mystream',
            'success_message' => lang('mystream:submit_success'),
            'failure_message' => lang('mystream:submit_failure'),
            'title' => lang('mystream:new')
        );

        $this->streams->cp->entry_form('data_streams', 'mystream', 'new', null, true, $extra);
    }
    // --------------------------------------------------------------------------
    
    public function edit($stream_id = 0)
    {
        $this->data->mystream = $mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
        $this->data->exclude_fields = $exclude_fields = array('sorting', 'is_hidden', 'stream_namespace', 'stream_prefix', 'stream_slug');
        if ($this->input->post())
        {
            
            $stream_validation = $this->streams->streams->validation_array('data_streams', 'mystream');
            foreach($stream_validation as $key => &$validation){
                if(in_array($validation['field'], $exclude_fields)){
                    unset($stream_validation[$key]);
                }
            }

            $this->form_validation->set_rules($stream_validation);
            if ($this->form_validation->run())
            {
                $post = $this->input->post();
                $stream_data = array(
                    'stream_name' => $post['stream_name'],
                    'about' => $post['about'],
                    'title_column' => $post['title_column'],
                    'view_options' => serialize($post['view_options'])
                );

                $status = $this->mystream_m->update($stream_id, $stream_data);
                if($status){
                    $this->session->set_flashdata('success', 'Stream updated successfully');                    
                    redirect('admin/mystream');
                }
                else{
                    $this->session->set_flashdata('error', 'There was a problem adding stream.');                    
                }
            }
        }

        
        $this->data->stream_fields = $stream_fields = $this->streams->fields->get_stream_fields('data_streams', 'mystream', (array)$mystream);
        $this->data->field_options = array('id' => 'id');
        if($stream_fields)
        {
            $this->data->field_options['id'] = 'id';
            foreach($stream_fields as $field)
            {
                $this->data->field_options[$field['field_slug']] = $field['field_slug'];
            }
        }
        $this->template->title($this->module_details['name'])
                ->build('admin/form', $this->data);
    }
    /**
     * Edit a stream entry
     *
     * @access	public
     * @return	void
     */
    public function _edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/mystream',
            'success_message' => lang('mystream:submit_success'),
            'failure_message' => lang('mystream:submit_failure'),
            'title' => lang('mystream:edit')
        );

        $this->streams->cp->entry_form('data_streams', 'mystream', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a stream entry
     * 
     * @access  public
     * @param   int $id The id of stream to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($stream_id = 0)
    {
        $this->data->mystream = $mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');
        $stream_fields = $this->streams->fields->get_stream_fields($mystream->stream_slug, $mystream->stream_namespace);
        $this->db->delete('data_streams', array('stream_slug' => $mystream->stream_slug, 'stream_namespace' => $mystream->stream_namespace));

        foreach ($stream_fields as $field) {
           $this->streams->fields->delete_field($field['field_slug'], $mystream->stream_namespace);
        }

        $this->session->set_flashdata('error', lang('mystream:deleted'));
        redirect('admin/mystream');
    }

    public function view_options($stream_id = 0)
    {
        $this->data->mystream = $mystream = $this->streams->entries->get_entry($stream_id, 'data_streams', 'mystream');        

        if($_POST)
        {
            $view_options = $this->input->post('view_options');
            $status = $this->mystream_m->update($stream_id, array('view_options' => serialize($view_options)));
            if($status){
                $this->session->set_flashdata('success', 'View options added successfully to the stream.');
                redirect('admin/mystream');
            }
            else{
                $this->session->set_flashdata('error', 'Problem adding view options to the stream.');
            }
        }

        $stream_fields = $this->streams->fields->get_stream_fields($mystream->stream_slug, $mystream->stream_namespace, $this->input->post());
        $this->data->field_options = array('id' => 'id');
        if($stream_fields)
        {
            $this->data->field_options['id'] = 'id';
            foreach($stream_fields as $field)
            {
                $this->data->field_options[$field['field_slug']] = $field['field_slug'];
            }
        }

        
        $this->template->title($this->module_details['name'])
                ->build('admin/view_options', $this->data);
    }
}
/* End of file admin.php */
