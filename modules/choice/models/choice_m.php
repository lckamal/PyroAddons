<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * faq module
 *
 * @author      Kamal Lamichhane
 * @website     http://lkamal.com.np
 * @package     PyroCMS
 * @subpackage  faq Module
 */
class Choice_m extends MY_Model {
	
	public $site_lang = 'en';
	
	public function __construct()
	{		
		parent::__construct();
		
		$this->_table = 'choices';
        $this->primary_key = 'id';
        $this->site_lang = Settings::get('site_lang');
        
	}

	/**
	 * get choices with press and language.
	 * 
	 * @access public
	 * @param int $press_id (default: 0)
	 * @param string $lang (default: 'en')
	 * @return void
	 */
	public function get_choices($press_id = 0, $lang = 'en')
	{
		return $this->choice_m
	    	->join('presses_choices', 'presses_choices.choices_id = choice_choices.id', 'left')
	    	->where(array('presses_choices.row_id' => $press_id,
	    		'choice_choices.choice_lang' => $lang))
	    	->get_all();
	}
}
