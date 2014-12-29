	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Product Module
 *
 * @author 		Kamal Lamichhane
 * @website		http://lkamal.com.np
 * @package 	PyroCMS
 * @subpackage 	Prduct Module
 */
class Admin_categories extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'categories';
	
    protected $data;

    public function __construct()
    {
        //$this->load->library('my_streams_cp');
        //$this->load->model('custom_stream_m.php');
        parent::__construct();
		$this->data = new stdClass();
        $this->lang->load('product');

        $this->load->driver('Streams');
    }
	
	public function _index()
    {
        $params = array(
            'stream' => 'categories',
            'namespace' => 'product',
            'paginate' => 'yes',
            'pag_segment' => 5,
			'where' => "category_parent_id IS NULL",
			
        );

        $categories = $this->streams->entries->get_entries($params);

		$this->data->categories = $categories;
		//var_dump($products);exit;
        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/categories/index', $this->data);
    }
	
    /**
     * List all products category
     *
     * @access	public
     * @return	void
     */
    public function index()
    {
        $extra['title'] = lang('product:categories');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/product/categories/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/product/categories/delete/-entry_id-',
                'confirm' => true
            )
        );
        $this->streams->cp->entries_table('categories', 'product', 10, 'admin/product/categories/index', true, $extra);
    }



    public function create()
    {
		$extra['title'] = 'lang:product:new';

        $extra = array(
            'return' => 'admin/product/categories/index',
            'success_message' => lang('product:submit_success'),
            'failure_message' => lang('product:submit_failure'),
            'title' => lang('product:categories:new')
        );

        $this->streams->cp->entry_form('categories', 'product', 'new', null, true, $extra);
    }

   /**
     * Edit a product categories
     *
     * @access	public
     * @return	void
     */
    public function edit($id = 0)
    {
        $this->template->title(lang('product:edit'));

        $extra = array(
            'return' => 'admin/product/categories/index',
            'success_message' => lang('product:submit_success'),
            'failure_message' => lang('product:submit_failure'),
            'title' => lang('product:edit')
        );

        $this->streams->cp->entry_form('categories', 'product', 'edit', $id, true, $extra);
    }

    // --------------------------------------------------------------------------

    /**
     * Delete a product entry
     * 
     * @access  public
     * @param   int $id The id of product to be deleted
     * @return  void
     * @todo    This function does not currently hava any error checking.
     */
    public function delete($id = 0)
    {
        $this->streams->entries->delete_entry($id, 'categories', 'product');
        $this->session->set_flashdata('error', lang('product:deleted'));
        redirect('admin/product/categories/index');
    }



    public function show_subcategories($id, $depth = 1)
    {
        $params = array(
            'stream' => 'categories',
            'namespace' => 'product',
            'paginate' => 'yes',
            'page_segment' => 4,
            'where' => "category_parent_id = $id"
        );

        $categories = $this->streams->entries->get_entries($params);
        $this->load->model('product_category_m');
        $this->data->parent_category = $this->product_category_m->get($id);
        $this->data->categories = $categories;
        //var_dump($products);exit;
        // Build the page

        $this->data->depth = $depth;

        $this->template->title($this->module_details['name'])
             ->build('admin/categories/index_sub', $this->data);

    }
    



    public function view_subcategories($id = 0)
    {
        $extra['title'] = lang('product:categories');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('global:edit'),
                'url' => 'admin/product/categories/edit/-entry_id-'
            ),
            array(
                'label' => lang('global:delete'),
                'url' => 'admin/product/categories/delete/-entry_id-',
                'confirm' => true
            )
        );
        $this->streams->cp->entries_tables_2('categories', 'product', 1, 'admin/product/categories/index', true, $extra,$id);
		
 
    }

}
