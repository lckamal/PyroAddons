CREATE TABLE  `pyrocms`.`newsletter_messages` (
  `id` tinyint(4) NOT NULL auto_increment,
  `subject` varchar(255) default NULL,
  `body` text,
  `active` tinyint(1) NOT NULL default '1',
  `date_sent` datetime default '0000-00-00 00:00:00',
  `modified` datetime default '0000-00-00 00:00:00',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Newsletter Messages';

CREATE TABLE  `pyrocms`.`newsletter_groups` (
  `id` tinyint(4) NOT NULL auto_increment,
  `group_name` varchar(200) default 'New Group',
  `group_description` text,
  `group_public` tinyint(1) NOT NULL default '0',
  `modified` datetime default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Newsletter recipient groups';

CREATE TABLE  `pyrocms`.`newsletter_recipients` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `email` varchar(255) default '',
  `active` tinyint(1) NOT NULL default '1',
  `modified` datetime default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Newsletter recipients';

CREATE TABLE  `pyrocms`.`newsletter_recipients_groups` (
  `user_id` tinyint(4) NOT NULL,
  `group_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Newsletter recipient/group relationship table';