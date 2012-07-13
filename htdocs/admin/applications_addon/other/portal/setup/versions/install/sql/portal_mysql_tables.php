<?php


$TABLE[] = "CREATE TABLE portal_blocks (
  block_id int(5) NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  align tinyint(1) NOT NULL default '0',
  template tinyint(1) NOT NULL default '0', 
  position int(5) NOT NULL default '0',
  block_code text NOT NULL,
  PRIMARY KEY  (block_id)
);";


$TABLE[] = "CREATE TABLE IF NOT EXISTS publish_general_configuration (
  activity varchar(30) NOT NULL,
  field_date varchar(30) NOT NULL,
  field_author_id varchar(30) NOT NULL,
  parents text NOT NULL,
  enabled tinyint(1) unsigned DEFAULT '1',
  UNIQUE KEY activity (activity)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;"

$TABLE[] = "CREATE TABLE IF NOT EXISTS publish_format_data (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  configuration_id int(11) unsigned NOT NULL,
  member_id int(10) unsigned NOT NULL,
  parent_id int(10) unsigned NOT NULL,
  status_date int(10) unsigned NOT NULL,
  news tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


?>