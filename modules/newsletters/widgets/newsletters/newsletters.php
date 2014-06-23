<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package         PyroCMS
 * @subpackage      Slider Widget
 * @author          lckamal
 *
 * Display a slider configured in the Sliders Module
 *
 * Usage : on a CMS page add {widget_area('name_of_area')}
 * where 'name_of_area' is the name of the widget area you created in the admin control panel
 */

class Widget_Newsletters extends Widgets
{
    public $title       = array(
        'en' => 'Newsletters',
    );
    public $description = array(
        'en' => 'Display newsletter subscription form.',
    );

    public $author      = 'lckamal';
    public $website     = 'http://lkamal.com.np';
    public $version     = '1.0';

    public $fields = array(
        
        array(
            'field' => 'captions',
            'label' => 'Captions',
        )
    );


    public function form($options)
    {
        // load classes, libs
        $this->load->model(array(
            'newsletters/group_m',
        ));
        
        $groups = $this->group_m->where('group_public', 1)->dropdown('id', 'group_name');
        
        return array(
            'options'   => $options,
            'groups' => $groups,
        );
    }


    public function run($options)
    {
        // load classes, libs
        $this->load->model(array(
            'newsletters/group_m',
        ));
        // return vars
        return array(
            'options'   => $options,
        );
    }
}
