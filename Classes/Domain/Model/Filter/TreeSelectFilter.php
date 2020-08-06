<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;
use PunktDe\PtExtbase\Tree\ExtJsJsonWriterVisitor;
use PunktDe\PtExtbase\Tree\JsonTreeWriter;
use PunktDe\PtExtbase\Tree\Node;
use PunktDe\PtExtbase\Tree\Tree;
use PunktDe\PtExtbase\Tree\TreeContext;
use PunktDe\PtExtbase\Tree\TreeRepositoryBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;
use PunktDe\PtExtlist\Utility\DbUtils;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
 * Class implements a tree select filter
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_TreeSelectFilterTest
 */
class TreeSelectFilter extends AbstractOptionsFilter
{
    /**
     * @var Tree
     */
    protected $tree;



    /**
     * SingleSelect or multi-select checkbox tree
     * @var integer
     */
    protected $multiple;



    /**
     * @var string
     */
    protected $treeNodeRepository;



    /**
     * @var string
     */
    protected $treeNamespace;



    /**
     * @var integer
     */
    protected $treeMaxDepth;



    /**
     * @var integer
     */
    protected $treeRootNode = null;



    /**
     * @var array
     */
    protected $options;



    /**
     * @var bool
     */
    protected $treeRespectEnableFields = true;



    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;


    /**
     * @var TreeContext
     */
    protected $treeContext;


    /**
     * @return void
     */
    public function initFilter()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class); /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
        $this->treeContext = $this->objectManager->get(TreeContext::class);
        $this->treeContext->setRespectEnableFields($this->treeRespectEnableFields);
        $this->buildTree();
    }


    /**
     * @throws \Exception
     * @see AbstractFilter::initFilterByTsConfig()
     *
     */
    protected function initFilterByTsConfig()
    {
        parent::initFilterByTsConfig();

        if ($this->filterConfig->getSettings('multiple')) {
            $this->multiple = $this->filterConfig->getSettings('multiple');
        }

        $this->treeNodeRepository = $this->filterConfig->getSettings('treeNodeRepository');
        if (!$this->treeNodeRepository || !class_exists($this->treeNodeRepository)) {
            throw new \Exception('The treeNodeRepository with className ' . $this->treeNodeRepository . ' could no be found. ', 1328459171);
        }

        $this->treeNamespace = trim($this->filterConfig->getSettings('treeNamespace'));

        if ($this->filterConfig->getSettings('treeMaxDepth')) {
            $this->treeMaxDepth = $this->filterConfig->getSettings('treeMaxDepth');
        }

        if (array_key_exists('treeRootNode', $this->filterConfig->getSettings())) {
            $this->treeRootNode = (int)$this->filterConfig->getSettings('treeRootNode');
        }


        if (array_key_exists('treeRespectEnableFields', $this->filterConfig->getSettings())) {
            $this->treeRespectEnableFields = (int)$this->filterConfig->getSettings('treeRespectEnableFields') === 1 ? true : false;
        }
    }



    /**
     * Build the criteria for a single field
     *
     * @param FieldConfig $fieldIdentifier
     * @return SimpleCriteria
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        $fieldName = DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
        $singleCriteria = null;

        $singleCriteria = Criteria::in($fieldName, $this->getFilterNodeUIds());

        return $singleCriteria;
    }



    /**
     * @return array
     */
    protected function getFilterNodeUIds()
    {
        $treeNodeUIds = [];

        foreach ($this->filterValues as $filterValue) {
            $treeNodeUIds = array_merge($treeNodeUIds, $this->getSubTreeUIDs($filterValue));
            $treeNodeUIds[] = $filterValue;
        }

        return array_unique($treeNodeUIds);
    }



    /**
     * @see AbstractFilter::initFilterByGpVars()
     *
     */
    protected function initFilterByGpVars()
    {
        if (array_key_exists('filterValues', $this->gpVarFilterData)) {
            $this->filterValues = GeneralUtility::trimExplode(',', $this->gpVarFilterData['filterValues']);
        }
    }



    /**
     * @return array
     */
    public function _persistToSession()
    {
        return [
            'filterValues' => $this->filterValues,
            'invert' => $this->invert
        ];
    }



    /**
     * @return string
     */
    public function getTreeNodes()
    {
        $this->options = $this->getOptions();
        return $this->buildTreeNodes();
    }



    /**
     * @return string
     */
    protected function buildTreeNodes()
    {
        if ($this->treeRootNode) {
            $subTreeRootNode = $this->tree->getNodeByUid($this->treeRootNode);
            $this->tree = Tree::getInstanceByRootNode($subTreeRootNode);
        }


        if (isset($this->treeMaxDepth)) {
            $this->tree->setRestrictedDepth($this->treeMaxDepth);
            $this->tree->setRespectRestrictedDepth(true);
        }


        $arrayWriterVisitor = $this->objectManager->get(ExtJsJsonWriterVisitor::class);
        $arrayWriterVisitor->registerFirstVisitCallback($this, 'alterNodeArrayOnFirstVisit');
        $arrayWriterVisitor->registerLastVisitCallBack($this, 'alterNodeArrayOnLastVisit');
        $arrayWriterVisitor->setMultipleSelect($this->getMultiple());
        $arrayWriterVisitor->setSelection($this->filterValues);

        $jsonTreeWriter = $this->objectManager->get(JsonTreeWriter::class, [$arrayWriterVisitor], $arrayWriterVisitor);

        return $jsonTreeWriter->writeTree($this->tree);
    }



    /**
     * @return Tree
     */
    protected function buildTree()
    {
        $treeRepositoryBuilder = TreeRepositoryBuilder::getInstance();
        $treeRepositoryBuilder->setNodeRepositoryClassName($this->treeNodeRepository);

        $treeRepository = $treeRepositoryBuilder->buildTreeRepository();

        // TODO this method does not exist in current treeRepository!
        if (method_exists($treeRepository, 'setRespectEnableFields')) {
            $treeRepository->setRespectEnableFields($this->treeRespectEnableFields);
        }

        $this->tree = $treeRepository->loadTreeByNamespace($this->treeNamespace);
    }



    /**
     * @param Node $node
     * @param array $nodeArray
     * @return array
     */
    public function alterNodeArrayOnFirstVisit($node, $nodeArray)
    {
        $nodeArray['rowCount'] = $this->options[$node->getUid()]['rowCount'];

        return $nodeArray;
    }



    /**
     * @param Node $node
     * @param array $currentNode
     * @return array
     */
    public function alterNodeArrayOnLastVisit($node, $currentNode)
    {
        foreach ($currentNode['children'] as $child) {
            $currentNode['rowCount'] += $child['rowCount'];
        }

        $currentNode['text'] .= sprintf(' (%s)', (int)$currentNode['rowCount']);

        return $currentNode;
    }



    /**
     * Returns value of selected option, return an array always
     *
     * @return array
     */
    public function getValue()
    {
        if (count($this->filterValues) > 1) {
            return implode(', ', $this->filterValues);
        } else {
            reset($this->filterValues);
            return (current($this->filterValues));
        }
    }



    /**
     *
     * Multiple or dropdown select
     * @return integer
     */
    public function getMultiple()
    {
        return $this->multiple;
    }



    /**
     * Used for breadcrumbs or for the header in exports
     *
     * @return string
     */
    public function getDisplayValue()
    {
        $displayValues = [];

        foreach ($this->filterValues as $value) {
            $node = $this->tree->getNodeByUid($value);
            if ($node instanceof Node) {
                $displayValues[] = $node->getLabel();
            }
        }

        return implode(', ', $displayValues);
    }



    /**
     * Get a list of subtree Uids
     *
     * @param $nodeUid
     * @return array
     */
    protected function getSubTreeUIDs($nodeUid)
    {
        $subtreeNodeIdArray = [];

        $treeNode = $this->tree->getNodeByUid($nodeUid);

        if ($treeNode instanceof Node) {
            $subTreeNodes = $treeNode->getSubNodes();
            $subtreeNodeIdArray = [];

            foreach ($subTreeNodes as $subTreeNode) {
                /** @var Node $subTreeNode */
                $subtreeNodeIdArray[] = $subTreeNode->getUid();
            }
        } else {
            $subtreeNodeIdArray[] = -1;
        }

        return $subtreeNodeIdArray;
    }
}
