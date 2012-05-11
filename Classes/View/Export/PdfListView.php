<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Implements a view for rendering PDF
 *
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_PdfListView extends Tx_PtExtlist_View_Export_AbstractExportView {


	/**
	 * @var string domPdf source absolute path
	 */
	protected $dompdfSourcePath;


	/**
	 * Path to fluid template
	 *
	 * @var string
	 */
	protected $templatePath;


	/**
	 * @var string - See domPdf for supported formats
	 */
	protected $paperSize = 'a4';


	/**
	 * @var string - portrait / landscape
	 */
	protected $paperOrientation = 'portrait';


	/**
	 * @var string - css filename
	 */
	protected $cssFilePath;

	
	/**
	 * Initialize additional class properties
	 */
	public function initConfiguration() {
		parent::initConfiguration();
		//echo 's';
		$this->templatePath = $this->exportConfiguration->getSettings('templatePath');
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->templatePath, array('message' => 'No template path given for fluid export! 1284621481'));
		$this->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($this->templatePath));

		$this->paperSize = strtolower($this->exportConfiguration->getSettings('paperSize'));
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->paperSize, array('message' => 'No PaperSize given for the PDF output! 1322585559'));
		
		$this->paperOrientation = $this->exportConfiguration->getSettings('paperOrientation');
		Tx_PtExtbase_Assertions_Assert::isInArray($this->paperOrientation, array('portrait', 'landscape'), array('message' => 'The Orientation must either be portrait or landscape! 1322585560'));


		$this->cssFilePath = t3lib_div::getFileAbsFileName($this->exportConfiguration->getSettings('cssFilePath'));
		Tx_PtExtbase_Assertions_Assert::isTrue(file_exists($this->cssFilePath), array('message' => 'The CSS File with the filename ' . $this->cssFilePath . ' can not be found. 1322587627'));

		$this->dompdfSourcePath = t3lib_div::getFileAbsFileName($this->exportConfiguration->getSettings('dompdfSourcePath'));
		Tx_PtExtbase_Assertions_Assert::isTrue(is_dir($this->dompdfSourcePath), array('message' => 'DomPdf source in path ' . $this->dompdfSourcePath . ' was not found. 1322753515'));
		$this->dompdfSourcePath = substr($this->dompdfSourcePath,-1,1) == '/' ? $this->dompdfSourcePath : $this->dompdfSourcePath . '/';
	}


	/**
	 * @return void
	 */
	public function loadDomPDFClasses() {



		require_once ($this->dompdfSourcePath . 'dompdf_config.inc.php');

		/*
		$includePath = $this->dompdfSourcePath . 'include/';
		
		// load includes
		require_once $includePath . 'dompdf.cls.php';
		require_once $includePath . 'frame_tree.cls.php';
		require_once $includePath . 'stylesheet.cls.php';
		require_once $includePath . 'frame.cls.php';
		require_once $includePath . 'style.cls.php';
		require_once $includePath . 'attribute_translator.cls.php';
		require_once $includePath . 'frame_factory.cls.php';
		require_once $includePath . 'frame_decorator.cls.php';
		require_once $includePath . 'page_frame_decorator.cls.php';
		require_once $includePath . 'frame_reflower.cls.php';
		require_once $includePath . 'page_frame_reflower.cls.php';
		require_once $includePath . 'block_frame_decorator.cls.php';
		require_once $includePath . 'positioner.cls.php';
		require_once $includePath . 'block_positioner.cls.php';
		require_once $includePath . 'block_frame_reflower.cls.php';
		require_once $includePath . 'text_frame_decorator.cls.php';
		require_once $includePath . 'inline_positioner.cls.php';
		require_once $includePath . 'text_frame_reflower.cls.php';
		require_once $includePath . 'canvas_factory.cls.php';
		require_once $includePath . 'canvas.cls.php';
		require_once $includePath . 'abstract_renderer.cls.php';
		require_once $includePath . 'renderer.cls.php';
		require_once $includePath . 'cpdf_adapter.cls.php';
		require_once $includePath . 'font_metrics.cls.php';
		require_once $includePath . 'block_renderer.cls.php';
		require_once $includePath . 'text_renderer.cls.php';
		require_once $includePath . 'image_cache.cls.php';

		require_once $includePath . 'inline_frame_decorator.cls.php';
		require_once $includePath . 'inline_frame_reflower.cls.php';
		require_once $includePath . 'image_frame_reflower.cls.php';
		require_once $includePath . 'image_frame_decorator.cls.php';
		require_once $includePath . 'inline_renderer.cls.php';
		require_once $includePath . 'image_renderer.cls.php';

		require_once $includePath . 'table_frame_decorator.cls.php';
		require_once $includePath . 'cellmap.cls.php';
		require_once $includePath . 'table_frame_reflower.cls.php';
		require_once $includePath . 'table_row_frame_decorator.cls.php';
		require_once $includePath . 'table_row_frame_reflower.cls.php';
		require_once $includePath . 'null_positioner.cls.php';
		require_once $includePath . 'table_cell_frame_reflower.cls.php';
		require_once $includePath . 'table_cell_frame_decorator.cls.php';
		require_once $includePath . 'table_cell_positioner.cls.php';
		require_once $includePath . 'table_cell_renderer.cls.php';

		require_once $includePath . 'null_frame_decorator.cls.php';
		require_once $includePath . 'null_frame_reflower.cls.php';
		require_once $includePath . 'php_evaluator.cls.php';
		*/
	}


	/**
	 * Overwriting the render method to generate a CSV output
	 *
	 * @return  void (never returns)
	 */
	public function render() {

		$this->loadDomPDFClasses();

		$this->assign('csssFilePath', $this->cssFilePath);
		$html = parent::render();
		ob_clean();

		if((int) t3lib_div::_GET('showHTML') == 1) {
			$relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->cssFilePath);
			$html = str_replace($this->cssFilePath, $relativePath, $html);
			die($html);
		}

		$dompdf = new DOMPDF();
		$dompdf->set_paper($this->paperSize, $this->paperOrientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$dompdf->stream("test" . time() .'.pdf', array("Attachment" => 0));

		exit();
	}
}