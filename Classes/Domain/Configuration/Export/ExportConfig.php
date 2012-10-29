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
 * Class implements configuration for export
 *
 * @package Domain
 * @subpackage Configuration\Export
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_Export_ExportConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

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



	protected function init() {
		$this->setRequiredValue('fileName', 'No fileName given for export file! 1284563488');
		$this->setRequiredValue('fileExtension', 'No file extension given for export file! 1284620580');
		
		$this->setRequiredValue('viewClassName', 'No viewClassName given for export file ' . $this->fileName . '1284563489');
		Tx_PtExtbase_Assertions_Assert::isTrue(class_exists($this->viewClassName), array('message' => 'The class name "' . $this->viewClassName . '" for export view does not exist! 1284563683'));
				
		$this->setBooleanIfExistsAndNotNothing('addDateToFilename');
		$this->setValueIfExistsAndNotNothing('dateFormat');
		$this->setValueIfExistsAndNotNothing('contentType');
		
		if(array_key_exists('downloadtype', $this->settings)) {
			$this->downloadType = $this->settings['downloadtype'] == 'D' ? self::FORCE_DOWNLOAD : self::OPEN_IN_BROWSER;
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
	
}
?>