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
 * Abstract view for exports. This class acts as a base class for
 * all exports. It handles settings for export and header generation etc.
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
abstract class Tx_PtExtlist_View_AbstractExportView extends Tx_PtExtlist_View_BaseView {
	
	/**
	 * Constant for downloadtype "force download"
	 *
	 */
	const FORCE_DOWNLOAD = 'D';

	
	
	/**
	 * Constant for downloadtype "open in browser"
	 *
	 */
	const OPEN_IN_BROWSER = 'I';
	
	
	
	/**
	 * Suffix of filename
	 *
	 * @var string
	 */
	protected $filenameSuffix;
	
	
	
	/**
	 * Prefix of filename
	 *
	 * @var string
	 */
	protected $filenamePrefix;
	
	
	
	/**
	 * Download type of export
	 *
	 * @var string
	 */
	protected $downloadType = self::FORCE_DOWNLOAD;
	
	
	
	/**
	 * True, if date and timestamp should be used in filename
	 *
	 * @var bool
	 */
	protected $useDateAndTimestampInFilename = false;
	
	
	
	/**
	 * Holds full filename with suffix, prefix and date
	 *
	 * @var string
	 */
	protected $fullFilename;
	
	
    
    /**
     * Returns true, if view has a template
     * 
     * TODO We return true here, because otherwise, another view class is
     * instantiated in controller.
     *
     * @return bool
     */
    public function hasTemplate() {
        return TRUE;
    }
	
    
    
    /**
     * Injector for settings
     *
     * @param array $settings
     */
    public function injectSettings(&$settings) {
    	parent::injectSettings($settings);
    	$this->initSettings();
    }
    
    
    
    /**
     * Initialize class properties by settings
     *
     */
    protected function initSettings() {
    	$exportSettings = $this->settings['export'];
        if (array_key_exists('filename', $exportSettings)) {
        	$this->filenameSuffix = $exportSettings['filename'];
        }
        if (array_key_exists('fileEnding', $exportSettings)) {
        	$this->filenamePrefix = $exportSettings['fileEnding'];
        }
        if (array_key_exists('downloadType', $exportSettings)) {
        	$this->downloadType = $exportSettings['downloadType'];
        }
        if (array_key_exists('addDateToFilename', $exportSettings && $exportSettings['addDateToFilename'] == '1')) {
        	$this->useDateAndTimestampInFilename = true;
        }
    	$this->generateFilenameFromTs();
    }
    
    
    
    /**
     * Helper method for generating file name from TS config
     * 
     * @return  string      File name of Export File
     */
    protected function generateFilenameFromTs() {
        $fullFilename = '';
        
        if ($this->filenamePrefix == '' ) {
            $this->filenamePrefix = $this->getDefaultFilePrefix();
        } 
        
        if ($this->filenameSuffix == '') {
         	$this->filenameSuffix = $this->getDefaultFileSuffix();
        }
        
        $fullFilename .= $this->filenameSuffix; 

        if ($this->useDateAndTimestampInFilename) {
        	$fullFilename .= date('Y-m-d', time());
        }
        
        $fullFilename .= '.' . $this->filenamePrefix;
        $this->fullFilename = $fullFilename;
    }
    
    
    
    /**
     * Generate header depending on download handling setting in TS
     * 
     * Functionality is taken from FPDF!
     * 
     * @param   string  name of the file to send to the browser
     * @return  void
     */
    protected function sendHeader($filename) {
        switch($this->downloadType) {
        	
                case self::OPEN_IN_BROWSER :
                        header('Content-Type: text/x-csv');
                        if(headers_sent()) {
                            throw new Exception('Some data has already been output to browser, can\'t send Export file 1283945901');
                        }
                        header('Content-disposition: inline; filename="'.$filename.'"');
                        break;

                case self::FORCE_DOWNLOAD:
                        if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) {
                            header('Content-Type: application/force-download');
                        } else {
                            header('Content-Type: application/octet-stream');
                        }
                        header('Content-disposition: attachment; filename="'.$filename.'"');
                        break;

                default:
                        throw new Exception('No valid download handling set for Export file! 1283945902');
        }
    }
    
    
    
    /**
     * Returns default filename suffix if no suffix is set in TS
     *
     * @return string
     */
    protected function getDefaultFileSuffix() {
    	return 'list';
    }
    
    
    
    /**
     * Returns default file ending if no file ending is set in TS
     * 
     * @return string
     */
    abstract protected function getDefaultFilePrefix();
    
    
}
 
?>