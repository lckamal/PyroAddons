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
class Admin_streams extends Admin_Controller
{
    // This will set the active section tab
    protected $section = 'streams';

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('product');

        $this->load->driver('Streams');
    }

    public function index()
    {
        $extra['title'] = lang_label('lang:product:streams');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('product:field_assignments'),
                'url' => 'admin/product/streams/fields/products'
            ),
            array(
                'label' => lang('product:view_options'),
                'url' => 'admin/product/streams/view_options/products'
            )
        );

    	$this->streams->cp->entries_table('products', 'product', 10, 'admin/product/streams/index', true, $extra);
    }

    public function view_options($stream_slug)
    {
        $this->streams->cp->view_options($stream_slug, 'product', 'admin/product/streams/index');
    }

    public function fields($stream_slug)
    {

        $extra['title'] = lang('product:streams').' &rarr; '.lang('product:'.$stream_slug) .' &rarr; '.lang('product:field_assignments');

        $extra['buttons'] = array(
            array(
                'label' => 'lang:global:edit',
                'url' => 'admin/product/streams/assignment/'.$stream_slug.'/edit/-assign_id-'
            )
        );

        $this->streams->cp->assignments_table($stream_slug, 'product', null, null, true, $extra);   
    }

    public function assignment($stream_slug, $method = 'new', $id = null)
    {
        $extra['title'] = lang('product:streams').' &rarr; '.lang('product:'.$stream_slug) .' &rarr; '.lang('product:'.$method.'_assignment');

        $this->streams->cp->field_form($stream_slug, 'product', $method, 'admin/product/streams/fields/'.$stream_slug, $id, array(), true, $extra);
    }
}