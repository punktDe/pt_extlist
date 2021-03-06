<?php


namespace PunktDe\PtExtlist\View\Export;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtbase\Exception\Assertion;

/**
 * Implements a view for rendering a export with fluid
 *
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class FluidListView extends AbstractExportView
{
    /**
     * Path to fluid template
     *
     * @var string
     */
    protected $templatePath;


    /**
     * Initialize additional class properties
     * @throws Assertion
     */
    public function initConfiguration()
    {
        $this->templatePath = $this->exportConfiguration->getSettings('templatePath');
        Assert::isNotEmptyString($this->templatePath, ['message' => 'No template path given for fluid export!', 1284621481]);
        $this->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->templatePath));
    }


    /**
     * Overwriting the render method to generate a downloadable output
     *
     * @return   void (never returns)
     * @throws \Exception
     */
    public function render($actionName = null)
    {
        ob_clean();

        $outputData = parent::render();

        $this->sendHeader($this->getFilenameFromTs());
        $out = fopen('php://output', 'w');

        fwrite($out, $outputData);

        fclose($out);

        exit();
    }
}
