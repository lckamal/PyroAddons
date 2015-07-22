<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Sitesetting extends Module
{

    public $version = '1.0';

    public function __construct()
    {
    	parent::__construct();
    	$this->lang->load('sitesetting/sitesetting');
    }
    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Site Settings'
            ),
            'description' => array(
                'en' => 'Extended module to manage settings with stream.'
            ),
            'frontend' => false,
            'backend' => true,
            'menu' => 'settings',
            'skip_xss' => true,
        );
    }
	public function admin_menu(&$menu)
	{
		add_admin_menu_place('lang:cp:nav_module', 2);
	}
	
    public function install()
    {
        $this->settings_table_to_stream('add');
        return true;
    }

    public function uninstall()
    {
        $this->settings_table_to_stream('remove');
        return true;
    }
	
    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return true;
    }

    /**
     * convert settings to stream to manage settings from admin
     * @param  string $action
     * @return bool
     */
    public function settings_table_to_stream($action = 'add')
    {
        $this->load->driver('Streams');
        if($action == 'add')
        {
            $stream_slug = 'settings';
            $namespace = 'streams'; 
            // $this->db->query("ALTER TABLE `default_settings` DROP PRIMARY KEY;");
            // $this->db->query("ALTER TABLE `default_settings` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY  FIRST;");
            // $this->db->query("ALTER TABLE `default_settings` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
            // $this->db->query("ALTER TABLE `default_settings` CHANGE `default` `default` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
            // $this->db->query("ALTER TABLE `default_settings` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
            // $this->db->query("ALTER TABLE `default_settings` CHANGE `options` `options` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
            // $this->db->query("ALTER TABLE `default_settings` CHANGE `module` `module` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
            // Add settings streams
            if ( ! $this->streams->utilities->convert_table_to_stream($stream_slug, $namespace, null, 'Settings', 'Create settings', 'title', $view_options = array('id', 'slug', 'title', 'value', 'module'))) return false;
            $this->_settings_to_stream($stream_slug, $namespace);
        }else{
            
            $this->db->delete('data_streams', array('stream_namespace' => 'settings'));
            $fields = array('slug', 'title', 'description', 'type', 'default', 'value', 'options', 'is_required', 'is_gui', 'module', 'order');
            foreach ($fields as $field) {
               $this->streams->fields->delete_field($field, 'settings');
            } 
        }
        return TRUE;
    }

    /**
     * convert our settings table to dynamic so that we can add from streams GUI
     * @return void
     */
    private function _settings_to_stream($stream_slug = null, $namespace = null)
    {
        if(!$stream_slug || !$namespace) return false;

        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Slug', 'slug', 'text', $extra = array('max_length' => '100'), array('required' => true, 'unique' => 'true'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Title', 'title', 'text', $extra = array('max_length' => '100'), array('required' => true, 'title_column' => true));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Description', 'description', 'text', $extra = array('max_length' => '255'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Type', 'type', 'choice', $extra = array(
                'choice_data' => "text : Text\n
                textarea : Textarea\n
                select : Select\n
                select-multiple : Multi select\n
                radio : Radio\n
                checkbox : Checkbox", 
        'choice_type' => 'dropdown', 'default_value' => 'text'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Default', 'default', 'text', $extra = array('max_length' => '100'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Value', 'value', 'textarea', $extra = array('max_length' => '500'), array('instructions' => 'What will be the value of this field?'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Options', 'options', 'textarea', $extra = array('max_length' => '500'), array('instructions' => 'Some options if type is select / radio / multiselect / checkbox. eg. 1=Yes|0=No'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Is Required?', 'is_required', 'choice', $extra = array('choice_data' => "0 : No\n1 : Yes", 'choice_type' => 'dropdown', 'default_value' => '0'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Is GUI?', 'is_gui', 'choice', $extra = array('choice_data' => "0 : No\n1 : Yes", 'choice_type' => 'dropdown', 'default_value' => '0'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Module', 'module', 'text', $extra = array('max_length' => '50'), array('instructions' => 'This will be shown on a tab on settings.'));
        $this->streams->utilities->convert_column_to_field($stream_slug, $namespace, 'Order', 'order', 'integer', $extra = array('max_length' => '4'), array('instructions' => 'Leaving this blank will be safer way.'));
        
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return '<p>Manage settings from streams. go to <a href="'.site_url('admin/streams').'">Streams</a> to manage settings.</p>';
    }

}