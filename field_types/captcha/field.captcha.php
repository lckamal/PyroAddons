<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Text Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Field_captcha {
    /**
     * Required variables
     */
    public $field_type_name = 'Captcha';
    public $field_type_slug = 'captcha';
    public $db_col_type = 'varchar';
    public $version = '1.0';
    public $author = array('name' => 'Sein', 'url' => 'http://sein.com.pl');
    public $custom_parameters = array('img_width', 'img_height');
    public $captcha_dir = '';
    public $CI;

    // --------------------------------------------------------------------------

    public function __construct() {

        $this->CI = &get_instance();

        $this->captcha_dir = BASEPATH . '/../../assets/captcha/';
        is_dir($this->captcha_dir) OR @mkdir($this->captcha_dir, 0777, TRUE);

        if (!$this->CI->db->table_exists('captcha')) {
            $sql = 'CREATE TABLE ' . $this->CI->db->dbprefix('captcha') . ' (
                    captcha_id int(11) unsigned NOT NULL auto_increment,
                    captcha_time int(10) unsigned NOT NULL,
                    ip_address varchar(16) default \'0\' NOT NULL,
                    word varchar(20) NOT NULL,
                    PRIMARY KEY `captcha_id` (`captcha_id`),
                    KEY `word` (`word`)
                    );';
            $this->CI->db->query($sql);
        }
    }

    /**
     * Output form input
     *
     * @param	array
     * @param	array
     * @return	string
     */
    public function form_output($data) {
        $options['name'] = $data['form_slug'];
        $options['id'] = $data['form_slug'];
        $options['value'] = $data['value'];

        //add related js and css
        $this->CI->type->add_js('captcha', 'captcha.js');
        $cap = $this->_create_captcha($data);

        return '<div class="captcha" id="captcha-'.$data['form_slug'].'">' . $cap['image'] . '<button class="reload-captcha btn btn-default btn-b" data-form_slug="'.$data['form_slug'].'">
    <i class="fa fa-refresh"></i> </button>
    </div>' . form_input($options);
    }

    public function validate($value, $mode = null, $field = null) {
        // Up front, let's determine if this 
        // a required field.
        $db_name = $this->CI->db->dbprefix('captcha');

        if (!$value) {
            //$this->CI->session->set_flashdata('error', lang('required'));
            return lang('required');
        }

        $expiration = time() - 7200; // Two hour limit
        $this->CI->db->query("DELETE FROM {$db_name} WHERE captcha_time < " . $expiration);

        $sql = "SELECT COUNT(*) AS count FROM {$db_name} WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($value, $this->CI->input->ip_address(), $expiration);
        $query = $this->CI->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0) {
            //$this->CI->session->set_flashdata('error', lang('required')
            return lang('streams.captcha.invalid');
        }
        else
            return TRUE;
    }

    public function param_img_width($value = null) {
        return array(
            'input'         => form_input('img_width', $value),
            'instructions'  => $this->CI->lang->line('streams.captcha.img_width')
        );
    }
    
    public function param_img_height($value = null) {
        return array(
            'input'         => form_input('img_height', $value),
            'instructions'  => $this->CI->lang->line('streams.captcha.img_height')
        );
    }

    public function _create_captcha($data = array())
    {
        $this->CI->load->helper('captcha');

        $config = array(
            'img_path' => $this->captcha_dir,
            'img_url' => site_url() . 'assets/captcha/',
            'img_width' => '150',
            'img_height' => 30,
            'expiration' => 7200
        );

        if (isset($data['img_width']) and is_numeric($data['img_width'])) {
            $config['img_width'] = $data['max_length'];
        }
        if (isset($data['img_height']) and is_numeric($data['img_height'])) {
            $config['img_height'] = $data['img_height'];
        }
        $cap = create_captcha($config);

        $insert = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->CI->input->ip_address(),
            'word' => $cap['word']
        );

        $this->CI->db->insert('captcha', $insert);
        return $cap;
    }

    public function ajax_captcha()
    {
        $data = $this->CI->input->post();
        $cap = $this->_create_captcha($data);
        echo $cap['image'];
    }

    public function ajax_validate($fild_slug = '')
    {
        $field = $_GET['field'];
        $value = $_GET[$field];
        $valid = $this->validate($value);
        if($valid !== true)
        {
            $valid = false;
        }

        echo json_encode(array(
            'valid' => $valid,
        ));
    }
}