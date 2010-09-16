<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Class implements configuration for export
 *
 * @package Domain
 * @subpackage Configuration\Export
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Export_ExportConfig {
	
	/**
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/**
	 * Array of all exoort settings
	 * 
	 * @var array
	 */
	protected $exportSettings;
	
	
	/**
	 * Add current date to filename
	 * @var boolean
	 */
	protected $addDateToFileName;
	
	
	/**
	 * Date forma string in php's date format
	 * 
	 * @var string
	 */
	protected $dateFormat = 'Y-m-d';
	
	
	/**
	 * File extension without '.'
	 * @var string
	 */
	protected $fileExtension;
	
	
	/**
	 * @var string
	 */
	protected $fileName;
	
	
	/**
	 * @var string
	 */
	protected $viewClassName;

	
	/**
	 * Mime Type for content inline display
	 * See: http://de.selfhtml.org/diverses/mimetypen.htm
	 * 
	 * @var string
	 */
	protected $contentType;
	
	
	/**
	 * DownloadType
	 * D = Download
	 * I = Show in Browser 
	 * 
	 * @var string
	 */
	protected $downloadType = self::FORCE_DOWNLOAD;

	
	/**
	 * Constant for downloadtype "force download"
	 */
	const FORCE_DOWNLOAD = 'D';

	
	
	/**
	 * Constant for downloadtype "open in browser"
	 */
	const OPEN_IN_BROWSER = 'I';

	
	
	/**
	 * Constructor
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param unknown_type $exportSettings
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $exportSettings) {
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->exportSettings = $exportSettings;
		$this->setPropertiesFromSettings($exportSettings);
	}
	
	
	
	/**
	 * Set the optional export Settings
	 * 
	 * @param array $exportSettings
	 */
	protected function setPropertiesFromSettings(array $exportSettings) {
		
		tx_pttools_assert::isNotEmptyString($exportSettings['fileName'], array('message' => 'No filename given for export file! 1284563488'));
		$this->fileName = $exportSettings['fileName'];
		
		tx_pttools_assert::isNotEmptyString($exportSettings['viewClassName'], array('message' => 'No viewClassName given for export file! 1284563488'));
		tx_pttools_assert::isTrue(class_exists($exportSettings['viewClassName']), array('message' => 'The classname "' . $exportSettings['viewClassName'] . '" for export view does not exist! 1284563683'));
		$this->viewClassName = $exportSettings['viewClassName'];
		
		tx_pttools_assert::isNotEmptyString($exportSettings['fileExtension'], array('message' => 'No file extension given for export file! 1284620580'));
		$this->fileExtension = $exportSettings['fileExtension'];	
		
		if(array_key_exists('downloadtype', $exportSettings)) {
			$this->downloadType = $exportSettings['downloadtype'] == 'D' ? self::FORCE_DOWNLOAD : self::OPEN_IN_BROWSER;	
		}
		
		if(array_key_exists('addDateToFilename', $exportSettings)) {
			$this->addDateToFilename = $exportSettings['addDateToFilename'];	
		}	

		if(array_key_exists('dateFormat', $exportSettings)) {
			$this->dateFormat = $exportSettings['dateFormat'];	
		}
		
		if(array_key_exists('contentType', $exportSettings)) {
			$this->contentType = $exportSettings['contentType'];	
		}
	}
	
	
	
	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getDownloadType() {
		return $this->downloadType;	
	}
	
	
	
	/**
	 * @return boolean
	 */
	public function getAddDateToFilename() {
		return $this->addDateToFilename;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getViewClassName() {
		return $this->viewClassName;	
	}
	
	
	
	/**
	 * @return string
	 */
	public function getFileExtension() {
		return $this->fileExtension;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getDateFormat() {
		return $this->dateFormat;
	}
	
	
	/**
	 * @return string
	 */
	public function getContentType() {
		return $this->contentType;
	}
	
	
	/**
	 * Get additional settings
	 * 
	 * @param string $key
	 * @retun mixed
	 */
	public function getSettings($key = NULL) {
		if($key == NULL) {
			return $this->exportSettings;
		} else  {
			if(array_key_exists($key, $this->exportSettings)) {
				return $this->exportSettings[$key];	
			}
		}
	}
}
?>