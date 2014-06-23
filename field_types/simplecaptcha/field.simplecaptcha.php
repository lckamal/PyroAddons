<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Streams Simple captcha Type
 *
 * @package   PyroStreams Field Type
 * @author    Kamal Lamichhane
 * @copyright Copyright (c) 2014
 * @link      http://lkamal.com.np
 *
 */

class Field_Simplecaptcha
{
	/**
	 * Required variables
	 */
	public $field_type_name = 'Simple captcha';
	public $field_type_slug = 'simplecaptcha';
	public $db_col_type     = 'varchar';
	
	/**
	 * Optional variables 
	 */
	public $input_is_file     = false;
	public $extra_validation  =   '';
	public $custom_parameters = array();
	public $lang              = array();
	
	/**
	 * create CI instance
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	 * Output form input
	 * Used when adding entry to stream
	 * 
	 * @param	array
	 * @param	array
	 * @return string
	 */
	public function form_output($data, $id = false, $field)
	{
		$captcha_data = $this->captcha_data();
		$rand_key = array_rand($captcha_data);
		$string_sum = $captcha_data[$rand_key];
		$return = '<span class="captcha-text">'.$string_sum.' = </span>';
		$return .= form_input(array('name' => $data['form_slug'].'_key', 'value' => $rand_key, 'type' => 'hidden'));
		$return .= form_input($data['form_slug'], '');
		return $return;
	}
	
	
	/**
	* Process before saving to database
	*
	* @access	public  
	* @param	array
	* @param	obj
	* @return	string
	*/
	public function pre_save($input, $field)
	{
		
	}

	/**
	 * captcha validation
	 */
	public function validate($value, $mode, $field)
	{
		$field_slug = $field->field_slug;
		$key_field = $field_slug.'_key';
		$key_field_val = $this->CI->input->post($key_field);
		if($key_field_val == $value)
		{
			return TRUE;
		}
		else{
			return lang('streams.simplecaptcha.invalid');
		}
	}

	/**
	 * captcha data for random output
	 */
	protected function captcha_data()
	{
		$data = array(
			1 => '0 + 1',
			2 => '1 + 1',
			3 => '1 + 2',
			4 => '2 + 2',
			5 => '2 + 3',
			6 => '3 + 3',
			7 => '4 + 3',
			8 => '4 + 4',
			9 => '5 + 4',
			10 => '5 + 5'
		);
		return $data;
	}
}

/* End of file field.simplecaptcha.php */