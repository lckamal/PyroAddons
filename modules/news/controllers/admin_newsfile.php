<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Marksheet Module
 *
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Marksheet
 */
class Admin_newsfile extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'newsfile';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('news');
        $this->load->model('news/news_m');

        $this->load->driver('Streams');

        $this->data = new stdClass();
    }

    // --------------------------------------------------------------------------

    /**
     * List all Marksheets using Streams CP Driver
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('newsfile:newsfiles');
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/news/newsfile/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/news/newsfile/delete/-entry_id-',
                'confirm' => true
            )
        );
        $extra['columns'] = array('newsfile');

        $this->streams->cp->entries_table('newsfiles', 'newsfile', 50, 'admin/news/newsfile/index', true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * List all Marksheets using Streams Entries Driver and building a custom template
     *
     * @access  public
     * @return  void
     */
    public function _index()
    {

        $params = array(
            'stream' => 'newsfiles',
            'namespace' => 'newsfile',
            'paginate' => 'yes',
            'page_segment' => 4
        );

        $this->data->newsfiles = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/newsfile/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new Marksheet entry
     *
     * This uses the Streams API CP driver which
     * is designed to handle repetitive CP tasks
     * down to even loading the page.
     *
     * Certain things can be overridden, but this
     * is an example using almost all default values.
     *
     * @access	public
     * @return	void
     */
    public function create()
    {
        $extra = array(
            'return' => 'admin/news/newsfile',
            'success_message' => lang('newsfile:submit_success'),
            'failure_message' => lang('newsfile:submit_failure'),
            'title' => 'lang:newsfile:new'
        );

        $this->streams->cp->entry_form('newsfiles', 'newsfile', 'new', null, true, $extra);
        
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a Marksheet entry
     *
     * This uses the Streams API CP driver which
     * is designed to handle repetitive CP tasks
     * down to even loading the page.
     *
     * Certain things can be overridden, but this
     * is an example using almost all default values.
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0)
    {
        $extra = array(
            'return' => 'admin/news/newsfile',
            'success_message' => lang('newsfile:submit_success'),
            'failure_message' => lang('newsfile:submit_failure'),
            'title' => 'lang:newsfile:edit'
        );

        $this->streams->cp->entry_form('newsfiles', 'newsfile', 'edit', $id, true, $extra);
    }

    public function import($news_id = 0)
    {
        $this->load->library('excel');
        

        if($this->input->post())
        {
            $upload_data = $this->upload_file();
        
            $this->form_validation->set_rules('import', 'Import', 'max_length[255]');
            
            if(isset($upload_data['upload_data']['file_name'])){
                $excel = $upload_data['upload_data']['file_name'];
            }
            else{
                 $this->session->set_flashdata('flashError', 'Unable to save the news.');
                 redirect(site_url('admin/subjects/import/'.$id));
            }
            
            $file = './uploads/excel/'.$excel;
            $objPHPExcel = $this->excel->load($file);
            $sheetData = $this->excel->reader->getActiveSheet()->toArray(null,true,true,true);
            if($sheetData != NULL || !empty($sheetData) || is_array($sheetData)){
                foreach($sheetData as $key => $subjects){
                    if(is_numeric($subjects['A'])){
                        $subjectdata['subject_code'] = $subjects['A'];
                        $subjectdata['subject_name'] = $subjects['B'];
                        $subjectdata['full_marks'] = $subjects['C'];
                        $subjectdata['pass_marks'] = $subjects['D'];
                        $subjectdata['type'] = $subjects['E'];
                        
                     
                        $this->db->insert('subjects', $subjectdata);
                        $this->session->set_flashdata('flashConfirm', 'The Excel file has Sucessfully Imported.');
                    }
                }
            }
        }
        
        Asset :: add_path('imagepicker', BASE_URL . 'addons/shared_addons/modules/imagepicker/');
        $this->template
            ->append_js('imagepicker::imagepicker.js')
            ->append_css('imagepicker::admin.css');
            
        $this->data->news_options = $this->news_m->dropdown('id', 'title');
        $this->template->title($this->module_details['name'])
            ->append_js('module::news.js')
                ->build('admin/import', $this->data);

    }
    // --------------------------------------------------------------------------

    /**
     * Delete a Marksheet entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entries within a Stream.
     * 
     * @access  public
     * @param   int $id The id of Marksheet to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'newsfiles', 'newsfile');
        $this->session->set_flashdata('error', lang('newsfile:deleted'));
        redirect('admin/news/newsfile/');
    }

}
/* End of file admin.php */
