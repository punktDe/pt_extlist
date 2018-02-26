<?php
namespace PunktDe\PtExtlist\Scheduler;

/*
 * This file is part of the Punktde\PtExtlist package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use PunktDe\PtExtbase\Scheduler\AbstractSchedulerTask;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Dispatcher;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\Response;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use PunktDe\PtExtbase\Extbase\Bootstrap;

/**
 * @property string tx_ptextlist_pageid
 * @property string tx_ptextlist_listidentifier
 */
class ExportListTask extends AbstractSchedulerTask
{

    /**
     * @var Dispatcher
     */
    protected $extbaseDispatcher;

    /**
     * @return string
     */
    public function getExtensionName()
    {
        return 'PtExtlist';
    }

    protected function initializeExtbase()
    {
        $_POST['id'] = $this->tx_ptextlist_pageid;
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->extbaseBootstrap = $this->objectManager->get(Bootstrap::class);
        $this->extbaseBootstrap->boot(strtolower($this->getExtensionName()), strtolower('Pi1'));
    }


    public function execute()
    {
        $this->extbaseDispatcher = $this->objectManager->get(Dispatcher::class);

        /** @var Request $request */
        $request = $this->objectManager->get(Request::class);
        $request->setControllerActionName('download');
        $request->setControllerName('Export');
        $request->setControllerExtensionName($this->getExtensionName());
        $request->setArgument('listIdentifier', $this->tx_ptextlist_listidentifier);

        /** @var Response $response */
        $response = $this->objectManager->get(Response::class);

        $this->extbaseDispatcher->dispatch($request, $response);

        file_put_contents('/var/tmp/XXX', $response->shutdown());
        return true;
    }




}
