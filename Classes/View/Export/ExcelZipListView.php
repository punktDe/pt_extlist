<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 punkt.de GmbH
 *  Authors:
 *    Christian Herberger <herberger@punkt.de>,
 *    Ursula Klinger <klinger@punkt.de>,
 *    Daniel Lienert <lienert@punkt.de>,
 *    Joachim Mathes <mathes@punkt.de>
 *  All rights reserved
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
 * ExcelZipListView
 *
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_ExcelZipListView extends Tx_PtExtlist_View_Export_ExcelListView {

	/**
	 * Clears output buffer and sends corresponding headers
	 * Needed in Zip-Export because of content type
	 *
	 * @return void
	 */
	protected function clearOutputBufferAndSendHeaders() {
		ob_clean();
		// redirect output to client browser
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment;filename="' . $this->getFilenameFromTs() . '"');
		header('Cache-Control: max-age=0');
	}

	/**
	 * Builds a zip file from an excel export
	 *
	 * @param $objWriter PHPExcel_Writer_IWriter
	 */
	protected function saveOutputAndExit(PHPExcel_Writer_IWriter $objWriter) {
		if (!is_dir(PATH_site . '/fileadmin/_temp_/')) {
			mkdir(PATH_site . '/fileadmin/_temp_/');
		}
		$now = time();
		$workPath = PATH_site . '/fileadmin/_temp_/' .$now . '/';
		$zipFile = $this->getFilenameFromTs();
		$excelFile = str_replace('.zip', '.xls', $zipFile);
		if (mkdir($workPath)) {
			chdir($workPath);
			$objWriter->save($excelFile);
			$zipPassword = $this->exportConfiguration->getSettings('password');
			$execResult = '';
			if ($zipPassword) {
				exec('zip -e -P ' . escapeshellarg($zipPassword) . ' ' . escapeshellarg($zipFile) . ' ' . escapeshellarg($excelFile), $execOutput, $execResult);
			} else {
				exec('zip ' . escapeshellarg($zipFile) . ' ' . escapeshellarg($excelFile), $execOutput, $execResult);
			}
			if ($execResult !== 0) {
				if (TYPO3_DLOG) t3lib_div::devLog('exec(zip) didn\'t work, error code '.$execResult, 'pt_extlist', 2);
			}
			$zipContent = file_get_contents($zipFile);
			unlink($zipFile);
			unlink($excelFile);
			chdir('..');
			rmdir($now);

			echo $zipContent;
		}
		exit();
	}
}

?>