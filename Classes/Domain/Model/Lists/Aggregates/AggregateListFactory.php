<?php


namespace PunktDe\PtExtlist\Domain\Model\Lists\Aggregates;

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
 * Class implements factory the aggregate list builder
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Lists\Aggregates
 */
class AggregateListFactory
{
    /**
     * Get defined aggregate rows as list data structure
     * if no aggregate Rows are defined return an empty list structure
     *  
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData
     */
    public static function getAggregateListData(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend, \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $aggregateListBuilder = new \PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\AggregateListBuilder($configurationBuilder);
        $aggregateListBuilder->injectArrayAggregator(\PunktDe\PtExtlist\Domain\Model\Lists\Aggregates\ArrayAggregatorFactory::createInstance($dataBackend));
        // TODO make this class non-static and use injection for rendererChainFactory here
        // $rendererChainFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Renderer_RendererChainFactory'); /* @var $rendererChainFactory Tx_PtExtlist_Domain_Renderer_RendererChainFactory */
        // $aggregateListBuilder->injectRenderer($rendererChainFactory->getRendererChain($configurationBuilder->buildRendererChainConfiguration()));
        $aggregateListBuilder->injectDataBackend($dataBackend);
        
        $aggregateListData = $aggregateListBuilder->buildAggregateListData();

        return $aggregateListData;
    }
}
