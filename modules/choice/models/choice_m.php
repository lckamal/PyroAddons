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

	public function choice_dropdown($options = array())
	{
		$where['choice_lang'] = isset($options['choice_lang']) ? $options['choice_lang'] : AUTO_LANGUAGE;
		if(isset($options['field_slug'])){
			$where['field_slug'] = $options['field_slug'];
		}
		return $this->where($where)->order_by('ordering_count', 'asc')->dropdown('choice_id', 'choice_title');
	}
}
