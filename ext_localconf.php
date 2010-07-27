<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

/**
 * Configure the Plugin to call the
 * right combination of Controller and Action according to
 * the user input (default settings, FlexForm, URL etc.)
 */

require_once '/home/ry21/public_html/ry21.ptlistdev.play.punkt.de/htdocs/typo3/sysext/extbase/Classes/Utility/Extension.php';

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,																		// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	'pi1',																			// A unique name of the plugin in UpperCamelCase
	array(																			// An array holding the controller-action-combinations that are accessible 
		'List' => 'list',	// The first controller and its first action will be the default
		'Filterbox' => 'show,submit',
		'Pager' => 'show,submit'
		),
	array(
	   	'List' => 'list',														// An array of non-cachable controller-action-combinations (they must already be enabled)
		'Filterbox' => 'show,submit',
		'Pager' => 'show,submit'
	)

);

?>
