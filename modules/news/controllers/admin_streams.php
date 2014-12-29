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

        $this->lang->load('news');

        $this->load->driver('Streams');
    }

    public function index()
    {
        $extra['title'] = lang_label('lang:news:streams');
        
        $extra['buttons'] = array(
            array(
                'label' => lang('news:field_assignments'),
                'url' => 'admin/news/streams/fields/newss'
            ),
            array(
                'label' => lang('news:view_options'),
                'url' => 'admin/news/streams/view_options/newss'
            )
        );

    	$this->streams->cp->entries_table('newss', 'news', 10, 'admin/news/streams/index', true, $extra);
    }

    public function view_options($stream_slug)
    {
        $this->streams->cp->view_options($stream_slug, 'news', 'admin/news/streams/index');
    }

    public function fields($stream_slug)
    {

        $extra['title'] = lang('news:streams').' &rarr; '.lang('news:'.$stream_slug) .' &rarr; '.lang('news:field_assignments');

        $extra['buttons'] = array(
            array(
                'label' => 'lang:global:edit',
                'url' => 'admin/news/streams/assignment/'.$stream_slug.'/edit/-assign_id-'
            )
        );

        $this->streams->cp->assignments_table($stream_slug, 'news', null, null, true, $extra);   
    }

    public function assignment($stream_slug, $method = 'new', $id = null)
    {
        $extra['title'] = lang('news:streams').' &rarr; '.lang('news:'.$stream_slug) .' &rarr; '.lang('news:'.$method.'_assignment');

        $this->streams->cp->field_form($stream_slug, 'news', $method, 'admin/news/streams/fields/'.$stream_slug, $id, array(), true, $extra);
    }
}