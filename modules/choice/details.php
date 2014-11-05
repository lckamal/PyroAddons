<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Choice extends Module
{

    public $version = '1.0';

    public function __construct()
    {
        parent::__construct();

        @set_time_limit(60);

        // Load in the FireSale library
        $this->lang->load('choice/choice');
        $this->load->library('streams_core/type');

        // Add our field type path
        $core_path = defined('PYROPATH') ? PYROPATH : APPPATH;

        if (is_dir(SHARED_ADDONPATH.'modules/choice/field_types')) {
            $this->type->addon_paths['choice'] = SHARED_ADDONPATH.'modules/choice/field_types/';
        } elseif (is_dir($core_path.'modules/choice/field_types')) {
            $this->type->addon_paths['choice'] = $core_path.'modules/choice/field_types/';
        } else {
            $this->type->addon_paths['choice'] = ADDONPATH.'modules/choice/field_types/';
        }

        $this->type->gather_types();
    }

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Choice'
            ),
            'description' => array(
                'en' => 'Choices managed from database'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'data',
            'sections' => array(
                'choice' => array(
                    'name' => 'choice:choices',
                    'uri' => 'admin/choice'
                )
            )
        );
    }

    public function install()
    {
        $this->choice_table('add');
        return true;
    }

    public function uninstall()
    {
		$this->choice_table('remove');
		return true;
    }

	public function choice_table($action = 'add')
	{
		$this->load->dbforge();
		if($action == 'add')
		{
			$fields = array(
                'id' => array(
					'type' => 'INT',
					'constraint' => 5,
					'unsigned' => TRUE,
					'auto_increment' => TRUE
                  ),
                'field_slug' => array(
					'type' => 'VARCHAR',
					'constraint' => '30',
				),
				'choice_title' => array(
					'type' => 'VARCHAR',
					'constraint' => '200',
					'null' => TRUE,
				),
				'choice_lang' => array(
					'type' => 'VARCHAR',
					'constraint' => '2',
					'null' => TRUE,
				),
				'choice_id' => array(
					'type' => 'INT',
					'constraint' => '5',
					'unsigned' => TRUE,
                    'default' => 0
				),
        	);
        	$this->dbforge->add_field($fields);
        	$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table('choices', TRUE);
		}
		else{
			$this->dbforge->drop_table('choices');
		}
		return TRUE;
	}
    
    public function upgrade($old_version)
    {
    	// Your Upgrade Logic
        return true;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }
}
