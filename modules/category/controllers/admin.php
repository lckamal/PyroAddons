<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Product Module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  Prduct Module
 */
class Admin extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'category';
    
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = new stdClass();
        $this->lang->load('category');
        class_exists('category_m') or $this->load->model('category/category_m');
        $this->load->driver('Streams');
    }
    
    public function index()
    {
        $this->data->categories = $this->category_m->get_category_tree();
        Asset::add_path('pages', 'system/cms/modules/pages/');
        $this->template
            ->title($this->module_details['name'])

            ->append_js('jquery/jquery.ui.nestedSortable.js')
            ->append_js('jquery/jquery.cooki.js')
            ->append_js('jquery/jquery.stickyscroll.js')
            ->append_js('module::index.js')

            ->append_css('pages::index.css')
            ->append_css('module::category.css')
            ->build('admin/index', $this->data);
    }
    public function __index()
    {
        $params = array(
            'stream' => 'category',
            'namespace' => 'category',
            'paginate' => 'yes',
            'pag_segment' => 5,
            'where' => "`category_lang` = 'en'",
            
        );

        $categories = $this->streams->entries->get_entries($params);
        $this->data->categories = $categories;

        // Build the page
        $this->template->title($this->module_details['name'])
                ->build('admin/index', $this->data);
    }

    /**
     * Create category entry
     *
     * @access  public
     * @return  void
     */
    public function lang($category_id = 0)
    {
        $this->data->default_lang = $default_lang = $this->input->get('default_lang') ? $this->input->get('default_lang') : AUTO_LANGUAGE;
        $this->data->mylangs = array_merge(array($default_lang => $this->langs[$default_lang]), $this->langs);
        $this->data->category_options = $this->category_m->where('category_lang', $default_lang)->dropdown('id', 'category_title');
        $this->data->mode = 'insert';

        if($category_id > 0)
        {
            $this->data->mode = 'update';
            unset($this->data->category_options[$category_id]);
            $this->data->category = $this->category_m->get($category_id);
            $category_langs = $this->category_m->where('category_id', $category_id)->get_all();
            
            $this->data->category_lang = null;
            foreach($category_langs as $category_lang)
            {
                $this->data->category_lang[$category_lang->category_lang] = (array)$category_lang;
            }
        }
        
        if($this->input->post('btnAction'))
        {
            $this->data->category = $this->input->post();
            if($this->validate_category() === true)
            {
                if($category_id = $this->save_category($this->data->mode, $default_lang))
                {
                    //delete related catch files
                    $this->pyrocache->delete_all('category_m');
                    $this->session->set_flashdata('success', lang('category:save_success'));
                    redirect('admin/category');
                }
                else{
                    $this->session->set_flashdata('error', lang('category:save_failure'));
                }
            }
        }
        // Build the page
        $this->data->fields = $this->streams->fields->get_stream_fields('category', 'category', (array)$this->data->category);
        $this->template->title($this->module_details['name'])
                ->build('admin/form', $this->data);
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
        $this->category_m->delete_by('category_id', $id);
        $this->category_m->delete_by('category_parent', $id);
        $this->pyrocache->delete_all('category_m');
        $this->session->set_flashdata('error', lang('category:deleted'));
        redirect('admin/category/index');
    }

    private function save_category($mode = 'insert', $default_lang = null)
    {
        $post_inputs = $this->input->post();
        if(!$default_lang){
            $default_lang = $this->input->get('default_lang') ? $this->input->get('default_lang') : $this->site_lang;
        }
        unset($post_inputs['btnAction'], $post_inputs['press_image']);
        
        foreach($post_inputs as $lang_code => $inputs)
        {
            if(in_array($lang_code, array_keys($this->langs)))
            {
                $post_data = array(
                    'category_parent' => $post_inputs['category_parent'],
                    'category_image' => $post_inputs['category_image'],
                    'category_slug' => $post_inputs['category_slug'],
                    'category_title' => $post_inputs[$lang_code]['category_title'],
                    'category_desc' => $post_inputs[$lang_code]['category_desc'],
                    'category_id' => $post_inputs[$lang_code]['category_id'],
                    'category_lang' => $lang_code,
                    'category_status' => $post_inputs['category_status']
                );

                if(isset($inputs['id']))
                {
                    if($lang_code == $default_lang)
                    {
                        $category_id = $this->streams->entries->update_entry($post_inputs[$default_lang]['category_id'], $post_data, 'category', 'category');
                    }
                    $category_id = $this->streams->entries->update_entry($inputs['id'], $post_data, 'category', 'category');  
                }
                else{
                    if($lang_code == $default_lang)
                    {
                        $category_id = $this->streams->entries->insert_entry($post_data, 'category', 'category');
                        if($category_id)
                        {
                            $this->streams->entries->update_entry($category_id, array('category_id' => $category_id), 'category', 'category');
                        }
                    }
                    else{
                        $category_id = $this->streams->entries->insert_entry($post_data, 'category', 'category');         
                    }
                    
                    
                }
            }
        }
        return $category_id;
    }

    private function validate_category()
    {
        $tag_inputs = $this->input->post();
        unset($tag_inputs['btnAction']);
        $site_lang = $default_lang = $this->input->get('default_lang') ? $this->input->get('default_lang') : AUTO_LANGUAGE;
        //$site_lang = $this->site_lang;
        
        //press_lang validation
        
        $exclude_fields = array('category_title', 'category_desc');
        
        //press validation
        $stream_validation_rules = $this->streams->streams->validation_array('category', 'category');
        foreach($stream_validation_rules as $key => &$press_rule)
        {       
            if(in_array($press_rule['field'], $exclude_fields)){
                $stream_validation_rules[] = array(
                    'field' => $site_lang.'['.$press_rule['field'].']',
                    'label' => $press_rule['label'],
                    'rules' => $press_rule['rules']
                );
                unset($stream_validation_rules[$key]);
            }
        }
        $this->form_validation->set_rules($stream_validation_rules);
        if($this->form_validation->run() === false) return false;
        
        return true;
    }

    /**
     * Order the categories and record their children
     *
     * Grabs `order` and `data` from the POST data.
     */
    public function order()
    {
        $order  = $this->input->post('order');
        $data   = $this->input->post('data');
        $root_pages = isset($data['root_pages']) ? $data['root_pages'] : array();

        if (is_array($order))
        {
            //reset all parent > child relations
            $this->category_m->update_all(array('category_parent' => 0));

            foreach ($order as $i => $page)
            {
                $id = str_replace('page_', '', $page['id']);
                
                //set the order of the root pages
                $this->category_m->update($id, array('ordering_count' => $i), true);

                //iterate through children and set their order and parent
                $this->category_m->_set_children($page);
            }

            $this->pyrocache->delete_all('category_m');

            Events::trigger('category_ordered', array($order, $root_pages));
        }
    }

    /**
     * Get the details of a category.
     *
     * @param int $id The id of the page.
     */
    public function ajax_category_details($id, $lang = null)
    {
        $lang = $lang ? $lang : AUTO_LANGUAGE;
        $page = $this->category_m->get_by(array('category_id' => $id, 'category_lang' => $lang));
        $this->load->view('admin/ajax/page_details', array('page' => $page));
    }
}
