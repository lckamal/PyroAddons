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
        $this->CI->load->helper('captcha');
        
        $options['name'] = $data['form_slug'];
        $options['id'] = $data['form_slug'];
        $options['value'] = $data['value'];

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

        return '<div id="captcha">' . $cap['image'] . '</div>' . form_input($options);
    }

    public function validate($value, $mode, $field) {
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

        return form_input('img_width', $value);
    }
    
    public function param_img_height($value = null) {

        return form_input('img_height', $value);
    }

}