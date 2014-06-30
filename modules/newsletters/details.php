<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Newsletters extends Module {

    public $version = '2.2.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Newsletters'
            ),
            'description' => array(
                'en' => 'Newsletter subscriber management.'
            ),
            'frontend' => TRUE,
            'backend' => TRUE,
            'menu' => 'structure', 
            'sections' => array(
                'dashboard' => array(
                    'name'   => 'newsletters.dashboard',
                    'uri'    => 'admin/newsletters',
                ),
                'newsletters' => array(
                    'name'  => 'letter_letter_title', // These are translated from your language file
                    'uri'   => 'admin/newsletters/mails',
                        'shortcuts' => array(
                            'draft' => array(
                                'name'  => 'newsletters_draft_title',
                                'uri'   => 'admin/newsletters/mails/index/draft',
                                'class' => ''
                            ),
                            'send' => array(
                                'name'  => 'newsletters_sent_title',
                                'uri'   => 'admin/newsletters/mails/index/sent',
                                'class' => ''
                            ),
                            'trash' => array(
                                'name'  => 'newsletters_trash_title',
                                'uri'   => 'admin/newsletters/mails/index/trash',
                                'class' => ''
                            ),
                            'create' => array(
                                'name'  => 'newsletters.add_title',
                                'uri'   => 'admin/newsletters/mails/create',
                                'class' => 'add'
                            ),
                        )
                ),
                'groups' => array(
					'name'   => 'newsletters.groups',
					'uri' 	 => 'admin/newsletters/groups',
					'shortcuts' => array(
						array(
						    'name' 	=> 'newsletters.create_groups',
						    'uri'	=> 'admin/newsletters/groups/create',
						    'class' => 'add'
						)
				    )
				),
                'recipients' => array(
					'name'   => 'newsletters.recipients',
					'uri' 	 => 'admin/newsletters/recipients',
					'shortcuts' => array(
						array(
						    'name' 	=> 'newsletters.create_recipients',
						    'uri'	=> 'admin/newsletters/recipients/create',
						    'class' => 'add'
						)
				    )
				),
            )
        );
    }

    public function install()
    {
    	$prefix = $this->db->dbprefix;
        $this->dbforge->drop_table('newsletters');
        $this->dbforge->drop_table('newsletter_groups');
        $this->dbforge->drop_table('newsletter_recipients');
        $this->dbforge->drop_table('newsletter_recipients_groups');
        
        $this->db->delete('settings', array('module' => 'newsletters'));
        
        $newsletters = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'body' => array(
                'type' => 'TEXT',                
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                
            ),
            'template_id' => array(
                'type' => 'INT',
                'constraint' => 5,
            ),
            'date_sent' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'modified' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created' => array(
                'type' => 'TIMESTAMP',
            ),
        );

        $this->dbforge->add_field($newsletters);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('newsletters');

        $newsletter_groups = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
            ),
            'group_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'group_description' => array(
                'type' => 'TEXT',                
            ),
            'group_public' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
                
            ),
            'modified' => array(
                'type' => 'TIMESTAMP',
            ),
        );

        $this->dbforge->add_field($newsletter_groups);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('newsletter_groups');
        $this->db->query("INSERT INTO `{$prefix}newsletter_groups` (`group_name`, `group_description`, `group_public`) VALUES
		('Subscribers', ' E-Newsletter(s) subscribers', 1);");
		
        $newsletter_recipients = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                
            ),
            'modified' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created' => array(
                'type' => 'TIMESTAMP',
            ),
        );

        $this->dbforge->add_field($newsletter_recipients);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('newsletter_recipients');
        
        $newsletter_recipients_groups = array(
            'recipient_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'group_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
        );

        $this->dbforge->add_field($newsletter_recipients_groups);
        //$this->dbforge->create_table('newsletter_recipients_groups');
        
        if($this->dbforge->create_table('newsletter_recipients_groups')  AND
           is_dir($this->upload_path.'newsletters') OR @mkdir($this->upload_path.'newsletters',0777,TRUE))
        {
            return TRUE;
        }
        else{
            $this->uninstall();
        }
    }

    public function uninstall()
    {
        $this->dbforge->drop_table('newsletters');
        $this->dbforge->drop_table('newsletter_groups');
        $this->dbforge->drop_table('newsletter_recipients');
        $this->dbforge->drop_table('newsletter_recipients_groups');
        $this->db->delete('settings', array('module' => 'newsletters'));
        {
            return TRUE;
        }
    }


    public function upgrade($old_version)
    {
        // Your Upgrade Logic
        return TRUE;
    }

    public function help()
    {
        // Return a string containing help info
        // You could include a file and return it here.
        return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
    }
}
/* End of file details.php */
