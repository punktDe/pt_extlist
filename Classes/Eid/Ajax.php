<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Stub for eID ajax calls.
 */

if (!defined ('TYPO3_MODE')) die ('Access denied.');

require_once t3lib_extMgm::extPath('pt_extbase') . 'Classes/Utility/AjaxDispatcher.php';

$TSFE = t3lib_div::makeInstance('tslib_fe', $TYPO3_CONF_VARS, 0, 0); /* @var $TSFE tslib_fe */
$TSFE->config['config'] = array();
$TSFE->renderCharset = 'utf-8';
$TSFE->fe_user = tslib_eidtools::initFeUser();
$GLOBALS['TSFE'] = $TSFE;

$typoscriptInclude = '<INCLUDE_TYPOSCRIPT:source="FILE:EXT:pt_extlist/Configuration/TypoScript/setup.txt">';

require_once(PATH_t3lib . 'class.t3lib_tsparser.php');
$tsParser = t3lib_div::makeInstance('t3lib_TSparser'); /* @var $tsParser t3lib_TSparser */
$externalTSFileContent = $tsParser->checkIncludeLines($typoscriptInclude);
$tsParser->parse($externalTSFileContent);

$GLOBALS['TSFE']->tmpl->setup = $tsParser->setup;

$GLOBALS['TSFE']->sys_page = t3lib_div::makeInstance('t3lib_pageSelect');

$dispatcher = t3lib_div::makeInstance('Tx_PtExtbase_Utility_AjaxDispatcher'); /* @var $dispatcher Tx_PtExtbase_Utility_AjaxDispatcher */
$dispatcher->initCallArguments();
$dispatcher->setExtensionName('PtExtlist');
$dispatcher->setPluginName('');
$dispatcher->setControllerName('');
$dispatcher->setActionName(t3lib_div::_GP('action'));

header('Content-Type: text/html; charset=' . $TSFE->renderCharset);
echo $dispatcher->dispatch();

?>