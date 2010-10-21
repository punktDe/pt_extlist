<?php

########################################################################
# Extension Manager/Repository config file for ext "pt_extlist".
#
# Auto generated 12-02-2010 15:05
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'pt_extlist',
	'description' => 'List generator for Typo3 based on ExtBase',
	'category' => '',
	'author' => 'Daniel Lienert, Christoph Ehscheidt, Michael Knoll',
	'author_email' => 't3extensions@punkt.de',
	'author_company' => 'http://www.punkt.de',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid,pt_tools',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.1071',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '1.0.2',
			'fluid' => '1.0.2',
            'pt_tools' => '1.0.2 dev'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:23:{s:14:"ext_tables.php";s:4:"5ff8";s:14:"ext_tables.sql";s:4:"450e";s:16:"kickstarter.json";s:4:"955f";s:37:"Classes/Controller/BuchController.php";s:4:"60b4";s:40:"Classes/Controller/SpracheController.php";s:4:"7a76";s:33:"Classes/Domain/Model/Ausleihe.php";s:4:"7d83";s:30:"Classes/Domain/Model/Autor.php";s:4:"9f18";s:29:"Classes/Domain/Model/Buch.php";s:4:"1930";s:33:"Classes/Domain/Model/Exemplar.php";s:4:"af97";s:34:"Classes/Domain/Model/Kategorie.php";s:4:"df7a";s:34:"Classes/Domain/Model/Kommentar.php";s:4:"3023";s:31:"Classes/Domain/Model/Person.php";s:4:"bbdb";s:32:"Classes/Domain/Model/Sprache.php";s:4:"a1be";s:33:"Classes/Domain/Model/Standort.php";s:4:"d6bf";s:28:"Classes/Domain/Model/Tag.php";s:4:"f607";s:31:"Classes/Domain/Model/Verlag.php";s:4:"9175";s:25:"Configuration/TCA/tca.php";s:4:"c3fc";s:34:"Configuration/TypoScript/setup.txt";s:4:"e23f";s:40:"Resources/Private/Language/locallang.xml";s:4:"5b1b";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"2553";s:44:"Resources/Private/Templates/Buch/create.html";s:4:"d41d";s:42:"Resources/Private/Templates/Buch/edit.html";s:4:"d41d";s:42:"Resources/Private/Templates/Buch/list.html";s:4:"3964";}',
);

?>