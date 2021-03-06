<?php
namespace PunktDe\PtExtlist\ViewHelpers\Namespaces;
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
use PunktDe\PtExtbase\State\IdentifiableInterface;
use PunktDe\PtExtbase\State\Session\SessionPersistenceManagerBuilder;
use PunktDe\PtExtbase\Utility\NamespaceUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * GPValueViewHelper
 * 
 * USAGE:
 * 
 * 
 * Set namespace and arguments as string: 
 * {extlist:namespace.GPArray(nameSpace:'<generalNamespaceString>' 
 * 							  arguments:'<argument.specific.nameSpacePart>:{<valueAsString>},<second.namespacepart>:{<secondValue>}')}
 * 
 * Set namespace from object:
 * {extlist:namespace.GPArray(object:'{filter}' arguments:'invert')}
 * 
 * @see Tx_PtExtlist_Tests_ViewHelpers_Namespace_GPArrayViewHelperTest
 */
class GPArrayViewHelper extends AbstractViewHelper
{
    /**
     * Holds instance of session persistence manager builder
     *
     * @var SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;

    /**
     * Injects session persistence manager factory (used by DI)
     *
     * @param SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }

    /**
     * Initialize arguments.
     *
     * @return void
     * @author Sebastian Kurfürst <sebastian@typo3.org>
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('arguments', 'string', 'Arguments');
        $this->registerArgument('object', 'object', 'IdentifiableInterface');
        $this->registerArgument('nameSpace', 'string', 'nameSpace');
    }

    /**
     * render build key/value GET/POST-array within the namespace of the given object
     *
     * @param string $arguments : list of arguments
     * @param IdentifiableInterface $object
     *    either as list of 'key : value' pairs
     *  or as list of properties wich are then recieved from the object
     * @param string $nameSpace
     * @return array GPArray of objects namespace
     */
    public function render()
    {
        $object = $this->arguments['object'] ?? null;
        $nameSpace = $this->arguments['nameSpace'] ?? '';
        $argumentStringArray = $this->getArgumentArray($this->arguments['arguments']);
        $argumentArray = [];
        
        foreach ($argumentStringArray as $key => $value) {
            if ($object !== null && $value === false) {
                $value = $this->getObjectValue($object, $key);
            }
            
            if (!$nameSpace) {
                $argumentArray = array_merge_recursive($argumentArray, $this->buildObjectValueArray($object, $key, $value));
            } else {
                $argumentArray = array_merge_recursive($argumentArray, $this->buildNamespaceValueArray($nameSpace, $key, $value));
            }
        }

        $this->sessionPersistenceManagerBuilder->getInstance()->addSessionRelatedArguments($argumentArray);

        return $argumentArray;
    }

    /**
     * Use the objects getter to get the value
     *
     * @param IdentifiableInterface $object
     * @param string $property
     * @return mixed value
     */
    protected function getObjectValue(IdentifiableInterface $object, $property)
    {
        $getterMethod = 'get'.ucfirst($property);
        Assert::isTrue(method_exists($object, $getterMethod), ['message' => 'The Object' . get_class($object) . ' has no getter method "'  . $getterMethod . '" ! 1280929630']);
        
        return $object->$getterMethod();
    }

    /**
     * Get an argument array out of a string
     * 
     * @param string $argumentString
     * @return array
     */
    public function getArgumentArray($argumentString)
    {
        $argumentArray = [];
        $argumentChunks = GeneralUtility::trimExplode(',', $argumentString);
        
        foreach ($argumentChunks as $argument) {
            if (strstr($argument, ':')) {
                list($key, $value) = GeneralUtility::trimExplode(':', $argument);
                $argumentArray = NamespaceUtility::saveDataInNamespaceTree($key, $argumentArray, $value);
            } else {
                $key = $argument;
                $argumentArray[$key] = false;
            }
        }
        
        return $argumentArray;
    }

    /**
     * Get the valueArray with the right objectNamespace
     *
     * @param IdentifiableInterface $object
     * @param string $key
     * @param string $value
     * @return array
     */
    public function buildObjectValueArray(IdentifiableInterface $object, $key, $value)
    {
        $nameSpace = $object->getObjectNamespace();
        Assert::isNotEmptyString($nameSpace, ['message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624']);
        
        return $this->buildNamespaceValueArray($nameSpace, $key, $value);
    }

    /**
     * Building a namespace array filled with an value.
     *
     * @param string $nameSpace
     * @param string $key
     * @param mixed $value
     * @return array The built array filled with the given value.
     */
    public function buildNamespaceValueArray($nameSpace, $key, $value)
    {
        $nameSpaceChunks =  GeneralUtility::trimExplode('.', $nameSpace);
        
        $returnArray = [];
        $pointer = &$returnArray;
        
        // Build array
        foreach ($nameSpaceChunks as $chunk) {
            $pointer = &$pointer[$chunk];
        }

        // Add value
        $pointer[$key] = $value;
        return $returnArray;
    }
}
