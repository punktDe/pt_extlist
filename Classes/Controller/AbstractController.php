<?php
namespace PunktDe\PtExtlist\Controller;

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

use PunktDe\PtExtbase\Controller\AbstractActionController;
use PunktDe\PtExtbase\Lifecycle\Manager;
use PunktDe\PtExtbase\State\Session\SessionPersistenceManager;
use PunktDe\PtExtbase\State\Session\SessionPersistenceManagerBuilder;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManager;
use PunktDe\PtExtlist\Domain\Model\Bookmark\BookmarkManagerFactory;
use PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory;
use PunktDe\PtExtlist\Extbase\ExtbaseContext;
use PunktDe\PtExtlist\View\BaseView;
use PunktDe\PtExtlist\View\ConfigurableViewInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Abstract controller for all pt_extlist controllers
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Controller
 */
abstract class AbstractController extends AbstractActionController
{
    /**
     * @var BookmarkManager
     */
    protected $bookmarkManager;



    /**
     * This flag is set to true, the configurationBuilder is reset
     *
     * @var bool
     */
    protected $resetConfigurationBuilder = false;



    /**
     * Holds instance of lifecycle manager
     *
     * @var Manager
     */
    protected $lifecycleManager;



    /**
     * Holds instance of session persistence manager builder
     *
     * @var SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * Holds instance of configuration builder factory
     *
     * @var ConfigurationBuilderFactory
     */
    protected $configurationBuilderFactory;



    /**
     * @var ConfigurationBuilder
     *
     */
    protected $configurationBuilder;

    /**
     *
     * @var DataBackendInterface
     */
    protected $dataBackend;



    /**
     * A string which identifies a group of list elements eg. List, Filter, Pager etc.
     * Since all plugins of this extensions need an unique list identifier, it is set in the controller.
     *
     * @var string
     */
    protected $listIdentifier;



    /**
     * Holds an instance of currently logged in fe user
     * If no User is logged in Property will be NULL
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $feUser = null;



    /**
     * @var SessionPersistenceManager
     */
    protected $sessionPersistenceManager;



    /**
     * @var DataBackendFactory
     */
    protected $dataBackendFactory;



    /**
     * @var GetPostVarAdapterFactory
     */
    protected $getPostVarsAdapterFactory;



    /**
     * @var BookmarkManagerFactory
     */
    protected $bookmarkManagerFactory;



    /**
     * Holds an instance of fe user repository
     *
     * @var FrontendUserRepository
     */
    protected $feUserRepository;



    /**
     * @var GetPostVarAdapterFactory
     */
    protected $getPostVarAdapterFactory;



    /**
     * Constructor for all plugin controllers
     *
     * @param Manager $lifecycleManager Lifecycle manager to be injected via DI
     * @param SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder Session persistence manager to be injected via DI
     */
    public function __construct(Manager $lifecycleManager, SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
        parent::__construct($lifecycleManager);
    }



    /**
     * @param DataBackendFactory $dataBackendFactory
     */
    public function injectDataBackendFactory(DataBackendFactory $dataBackendFactory)
    {
        $this->dataBackendFactory = $dataBackendFactory;
    }



    /**
     * @param ConfigurationBuilderFactory $configurationBuilderFactory
     */
    public function injectConfigurationBuilderFactory(ConfigurationBuilderFactory $configurationBuilderFactory)
    {
        $this->configurationBuilderFactory = $configurationBuilderFactory;
    }



    /**
     * @param GetPostVarAdapterFactory $getPostVarsAdapterFactory
     */
    public function injectGetPostVarsAdapterFactory(GetPostVarAdapterFactory $getPostVarsAdapterFactory)
    {
        $this->getPostVarsAdapterFactory = $getPostVarsAdapterFactory;
    }



    /**
     * @param BookmarkManagerFactory $bookmarkManagerFactory
     */
    public function injectBookmarkManagerFactory(BookmarkManagerFactory $bookmarkManagerFactory)
    {
        $this->bookmarkManagerFactory = $bookmarkManagerFactory;
    }



    /**
     * @param FrontendUserRepository $feUserRepository
     */
    public function injectFeUserRepository(FrontendUserRepository $feUserRepository)
    {
        $this->feUserRepository = $feUserRepository;
    }



    /**
     * @param Manager $lifecycleManager
     */
    public function injectLifecycleManager(Manager $lifecycleManager)
    {
        $this->lifecycleManager = $lifecycleManager;
    }



    /**
     * @param GetPostVarAdapterFactory $getPostVarAdapterFactory
     */
    public function injectGetPostVarAdapterFactory(GetPostVarAdapterFactory $getPostVarAdapterFactory)
    {
        $this->getPostVarAdapterFactory = $getPostVarAdapterFactory;
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function initializeAction()
    {
        parent::initializeAction();
        if (!TYPO3_MODE === 'BE') {
            $this->initFeUser();
        }
        $this->initListIdentifier();
        $this->buildConfigurationBuilder();
        $this->buildAndInitSessionPersistenceManager();
        $this->resetSessionOnResetParameter();
        $this->resetOnEmptySubmit();
        $this->dataBackend = $this->dataBackendFactory->getDataBackendInstanceByListIdentifier($this->listIdentifier);
    }



    /**
     * Sets list identifier for this controller
     *
     * @throws \Exception
     */
    protected function initListIdentifier()
    {
        if ($this->settings['listIdentifier'] != '') {
            $this->listIdentifier = $this->settings['listIdentifier'];
        } else {
            throw new \Exception('No list identifier set! List controller cannot be initialized without a list identifier. Most likely you have not set a list identifier in FlexForm 1363797701');
        }
    }



    /**
     * @return \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     * @throws \Exception
     */
    protected function buildConfigurationBuilder()
    {
        $this->configurationBuilder = $this->configurationBuilderFactory->getInstance($this->listIdentifier, $this->resetConfigurationBuilder);
    }



    /**
     * @throws \Exception
     */
    protected function buildAndInitSessionPersistenceManager()
    {
        $this->buildSessionPersistenceManager();
        $this->bookmarkManager = $this->bookmarkManagerFactory->getInstanceByConfigurationBuilder($this->configurationBuilder);
        $this->bookmarkManager->processRequest($this->request);
        $this->lifecycleManager->registerAndUpdateStateOnRegisteredObject($this->sessionPersistenceManager);
        //TODO: if session was restored from bookmark do not reset session
        // We reset session data, if we want to have a reset on empty submit
        if ($this->configurationBuilder->buildBaseConfiguration()->getResetOnEmptySubmit()) {
            $this->sessionPersistenceManager->resetSessionDataOnEmptyGpVars($this->getPostVarsAdapterFactory->getInstance());
        }
        $this->resetOnEmptySubmit();
    }



    /**
     * Reset session if ResetOnEmptySubmit is set in config and gpvars are empty
     */
    protected function resetOnEmptySubmit()
    {
        // We reset session data, if we want to have a reset on empty submit
        if ($this->configurationBuilder->buildBaseConfiguration()->getResetOnEmptySubmit()) {
            $this->sessionPersistenceManager->resetSessionDataOnEmptyGpVars($this->getPostVarsAdapterFactory->getInstance());
        }
    }

    

    /**
     * @return void
     */
    protected function resetSessionOnResetParameter()
    {
        if ($this->configurationBuilder->buildBaseConfiguration()->getResetSessionOnResetParameter()
            && $this->getPostVarAdapterFactory->getInstance()->getParametersByNamespace($this->listIdentifier . '.resetSession')
        ) {
            $this->sessionPersistenceManager->resetSessionData();
        }
    }



    /**
     * @return SessionPersistenceManager
     * @throws \Exception
     */
    protected function buildSessionPersistenceManager()
    {
        // Determine class name of session storage class to use for session persistence
        if (TYPO3_MODE === 'FE') {
            $sessionPersistenceStorageAdapterClassName = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class)->get(ExtbaseContext::class)->isInCachedMode()
                ? $this->configurationBuilder->buildBaseConfiguration()->getCachedSessionStorageAdapter() // We are in cached mode
                : $this->configurationBuilder->buildBaseConfiguration()->getUncachedSessionStorageAdapter(); // We are in uncached mode
        } else {
            $sessionPersistenceStorageAdapterClassName = SessionPersistenceManager::STORAGE_ADAPTER_BROWSER_SESSION;
        }
        // Instantiate session storage for determined class name
        $sessionStorageAdapter = call_user_func($sessionPersistenceStorageAdapterClassName . '::getInstance');
        $this->sessionPersistenceManager = $this->sessionPersistenceManagerBuilder->getInstance($sessionStorageAdapter);
    }



    /**
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @throws \Exception
     */
    protected function setViewConfiguration(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        parent::setViewConfiguration($view);
        $this->setCustomPathsInView($view);
    }



    /**
     * Initializes the view before invoking an action method.
     *
     * Override this method to solve assign variables common for all actions
     * or prepare the view in another way before the action is called.
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view The view to be initialized
     * @return void
     * @api
     */
    protected function initializeView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        $this->objectManager->get(ExtbaseContext::class)->setControllerContext($this->controllerContext);
        if (method_exists($view, 'setConfigurationBuilder')) {
            /* @var $view ConfigurableViewInterface */
            $view->setConfigurationBuilder($this->configurationBuilder);
        }
        $this->view->assign('config', $this->configurationBuilder);
    }



    /**
     * Template method for getting template path and filename from
     * TypoScript settings.
     *
     * Overwrite this method in extending controllers to add further namespace conventions etc.
     *
     * @return string Template path and filename
     */
    protected function getTsTemplatePathAndFilename()
    {
        if ($this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template']) {
            return $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
        }
        return $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
    }



    /**
     * Template method for getting class name for view to be used in this controller from
     * TypoScript.
     *
     * Overwrite this method in your extending controller to enable adding
     * further namespace settings etc.
     *
     * @return string View class name to be used in this controller
     */
    protected function getTsViewClassName()
    {
        $viewClassName = null;
        if ($this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view']) {
            $viewClassName = $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];
        }
        if ($this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view']) {
            $viewClassName = $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];
        }
        return $viewClassName;
    }



    /**
     * (non-PHPdoc)
     *
     * @see processRequest() in parent
     */
    public function processRequest(\TYPO3\CMS\Extbase\Mvc\RequestInterface $request, \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response)
    {
        parent::processRequest($request, $response);
        if (TYPO3_MODE === 'BE') {
            // if we are in BE mode, this ist the last line called
            $this->lifecycleManager->updateState(Manager::END);
        }
    }



    /**
     * Set the TS defined custom paths in view
     *
     * @param \TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view
     * @throws \Exception
     */
    protected function setCustomPathsInView(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface $view)
    {
        // TODO we do not get global settings from pt_extlist merged into list_identifier settings here. fix this.
        // Get template for current action from settings for list identifier
        $templatePathAndFilename = $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
        // Get template for current action from global settings (e.g. flexform)
        if (!$templatePathAndFilename) {
            $templatePathAndFilename = $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
        }
        // If no template is given before, take default one
        if (!$templatePathAndFilename) {
            $templatePathAndFilename = $this->templatePathAndFileName;
        }
        if (isset($templatePathAndFilename) && strlen($templatePathAndFilename) > 0) {
            if (file_exists(GeneralUtility::getFileAbsFileName($templatePathAndFilename))) {
                /* @var $view BaseView */
                $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($templatePathAndFilename));
            } else {
                throw new \Exception('Given template path and filename could not be found or resolved: ' . \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($templatePathAndFilename), 1284655110);
            }
        }
    }


    /**
     * (non-PHPdoc)
     *
     * @throws StopActionException
     * @see redirect() in parent
     */
    protected function redirect($actionName, $controllerName = null, $extensionName = null, array $arguments = null, $pageUid = null, $delay = 0, $statusCode = 303)
    {
        $this->lifecycleManager->updateState(Manager::END);
        parent::redirect($actionName, $controllerName, $extensionName, $arguments, $pageUid, $delay, $statusCode);
    }



    protected function initFeUser()
    {
        $userUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        $this->feUser = $this->feUserRepository->findByUid($userUid);
    }
}
