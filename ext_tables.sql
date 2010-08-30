#
# Table structure for table 'tx_ptextlist_domain_model_bookmars_bookmark'
#
CREATE TABLE tx_ptextlist_domain_model_bookmars_bookmark (
  id int(11) unsigned NOT NULL auto_increment,
  hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
  name varchar(250) DEFAULT '' NOT NULL,
  content mediumtext,
  description mediumtext,
  fe_user int(11) unsigned NOT NULL,
  list_id varchar(250) DEFAULT '' NOT NULL,
  create_date int(11) unsigned NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;
