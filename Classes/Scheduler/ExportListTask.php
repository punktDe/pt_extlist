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
        $_POST['id'] = 978;
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $this->extbaseBootstrap = $this->objectManager->get('PunktDe\PtExtbase\Extbase\Bootstrap');
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
        $request->setArgument('listIdentifier', 'ZcaImporterExportListWithoutZoraCardNumber');

        /** @var Response $response */
        $response = $this->objectManager->get(Response::class);

        $this->extbaseDispatcher->dispatch($request, $response);

        var_dump($response->shutdown());
    }




}