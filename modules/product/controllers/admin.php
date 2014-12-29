<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Streams Sample Module
 *
 * This is a sample module for PyroCMS
 * that illustrates how to use the streams core API
 * for data management.
 *
 * @author 		Adam Fairholm - PyroCMS Dev Team
 * @website		http://pyrocms.com
 * @package 	PyroCMS
 * @subpackage 	Streams Sample Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'product';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('product_category_m');
        $this->load->model('product_m');

        $this->lang->load('product');

        $this->data = new stdClass();

        $this->load->driver('Streams');
    }

    // --------------------------------------------------------------------------

    /**
     * List all products using Streams CP Driver
     *
     * In this alternate index, we are using the
     * Streams API driver to 
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('product:products');
        $extra['sorting'] = true;
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/product/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/product/delete/-entry_id-',
                'confirm' => true
            )
        );
		
        $this->streams->cp->entries_table('products', 'product', 20, 'admin/product/index', true, $extra);
    }


    // --------------------------------------------------------------------------

    /**
     * List all products using Streams Entries Driver and building a custom template
     *
     * We are using the Streams API to grab
     * data from the products database. It handles
     * pagination as well.
     *
     * @access  public
     * @return  void
     */
    public function _index()
    {
        // The get_entries function in the
        // entries Streams API drivers grabs
        // entries from a stream.
        // The only really required parameters are
        // stream and namespace.

        $params = array(
            'stream' => 'products',
            'namespace' => 'product',
            'paginate' => 'yes',
            'page_segment' => 4,
			'where' => "'id' = 20",
        );

        $this->data->products = $this->streams->entries->get_entries($params);

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    // --------------------------------------------------------------------------

    /**
     * Create a new product entry
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
            'return' => 'admin/product',
            'success_message' => lang('product:submit_success'),
            'failure_message' => lang('product:submit_failure'),
            'title' => 'New Product'
        );

        $this->streams->cp->entry_form('products', 'product', 'new', null, true, $extra);
    }

    // --------------------------------------------------------------------------
    
    /**
     * Edit a FAQ entry
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
            'return' => 'admin/product',
            'success_message' => lang('product:submit_success'),
            'failure_message' => lang('product:submit_failure'),
            'title' => 'Edit Product'
        );

        $this->streams->cp->entry_form('products', 'product', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a FAQ entry
     * 
     * This uses the Streams API Entries driver which is
     * designed to work with entries within a Stream.
     * 
     * @access  public
     * @param   int $id The id of FAQ to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'products', 'product');
        $this->session->set_flashdata('error', lang('product:deleted'));
        redirect('admin/product');
    }
}
/* End of file admin.php */
