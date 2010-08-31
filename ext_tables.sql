#
# Table structure for table 'tx_ptextlist_domain_model_bookmars_bookmark'
#
CREATE TABLE tx_ptextlist_domain_model_bookmarks_bookmark (
  uid int(11) unsigned NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) unsigned DEFAULT '0' NOT NULL,
  crdate int(11) unsigned DEFAULT '0' NOT NULL,
  deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

  t3ver_oid int(11) DEFAULT '0' NOT NULL,
  t3ver_id int(11) DEFAULT '0' NOT NULL,
  t3ver_wsid int(11) DEFAULT '0' NOT NULL,
  t3ver_label varchar(30) DEFAULT '' NOT NULL,
  t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
  t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
  t3ver_count int(11) DEFAULT '0' NOT NULL,
  t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
  t3_origuid int(11) DEFAULT '0' NOT NULL,
  
  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l18n_parent int(11) DEFAULT '0' NOT NULL,
  l18n_diffsource mediumblob NOT NULL,
  
  name varchar(250) DEFAULT '' NOT NULL,
  content mediumtext,
  description mediumtext,
  fe_user int(11) unsigned DEFAULT '0' NOT NULL,
  fe_group int(11) unsigned DEFAULT '0' NOT NULL,
  list_id varchar(250) DEFAULT '' NOT NULL,
  create_date int(11) unsigned DEFAULT '0' NOT NULL,
  is_public tinyint(4) unsigned DEFAULT '0' NOT NULL,
  
  PRIMARY KEY (uid),
  KEY parent (pid),
  KEY fe_group_key (fe_group),
  KEY list_id_key (list_id)
) ENGINE=InnoDB;
