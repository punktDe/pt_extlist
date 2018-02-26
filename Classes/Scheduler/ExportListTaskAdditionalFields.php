<?php
namespace PunktDe\PtExtlist\Scheduler;
/*
 * This file is part of the Punktde\PtExtlist package.
 *
 * This package is open source software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Scheduler\Task\AbstractTask;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ExportListTaskAdditionalFields implements AdditionalFieldProviderInterface
{
    /**
     * @var array
     */
    protected $configuration = array(
        'pageId' => 'tx_ptextlist_pageid',
        'listIdentifier' => 'tx_ptextlist_listidentifier',
        'labelPageId' => 'Page id',
        'labelListIdentifier' => 'List identifier',
    );

    /**
     * @var array
     */
    protected $additionalFields = [];

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param array $taskInfo
     * @param AbstractTask $task
     * @param SchedulerModuleController $schedulerModule
     * @return array
     */
    public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule): array
    {
        $this->initializeObjectManager();

        $this->getFieldValuePageId($taskInfo, $task, $schedulerModule);
        $this->getFieldValueListIdentifier($taskInfo, $task, $schedulerModule);

        $this->generatePageIdField($taskInfo);
        $this->generatelistIdentifierField($taskInfo);

        return $this->additionalFields;
    }


    protected function initializeObjectManager()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
    }


    /**
     * @param array $taskInfo
     * @param $task
     * @param SchedulerModuleController $schedulerModule
     */
    protected function getFieldValuePageId(array &$taskInfo, $task, SchedulerModuleController $schedulerModule)
    {
        $configuration = $this->configuration;
        if (empty($taskInfo[$configuration['pageId']]))
        {
            $pageId = $configuration['pageId'];
            if ($schedulerModule->CMD == 'edit') {
                $taskInfo[$pageId] = $task->$pageId;
            } else {
                $taskInfo[$pageId] = '';
            }
        }
    }

    /**
     * @param array $taskInfo
     * @param $task
     * @param SchedulerModuleController $schedulerModule
    */
    protected function getFieldValueListIdentifier(array &$taskInfo, $task, SchedulerModuleController $schedulerModule)
    {
        $configuration = $this->configuration;

        if (empty($taskInfo[$configuration['listIdentifier']])) {
            $listIdentifier = $configuration['listIdentifier'];
            if ($schedulerModule->CMD == 'edit') {
                $taskInfo[$listIdentifier] = $task->$listIdentifier;
            } else {
                $taskInfo[$listIdentifier] = '';
            }
        }
    }

    /**
     * @param array $taskInfo
     */
    protected function generatePageIdField(array &$taskInfo) {
        $view = $this->getView('pageId');
        $view->assign('pageId', $this->configuration['pageId']);
        $view->assign('value', $taskInfo[$this->configuration['pageId']]);

        $this->additionalFields[$this->configuration['pageId']] = array(
        'code' => $view->render(),
        'label' => $this->configuration['labelPageId']
        );
    }

    /**
     * @param array $taskInfo
     */
    protected function generatelistIdentifierField(array &$taskInfo) {
        $view = $this->getView('listIdentifier');
        $view->assign('listIdentifier', $this->configuration['listIdentifier']);
        $view->assign('value', $taskInfo[$this->configuration['listIdentifier']]);

        $this->additionalFields[$this->configuration['listIdentifier']] = array(
            'code' => $view->render(),
            'label' => $this->configuration['labelListIdentifier']
        );
    }

    /**
     * @param string $field
     * @return object|StandaloneView
     */
    protected function getView(string $field)
    {
        $view = $this->objectManager->get(StandaloneView::class); /** @var StandaloneView $view */
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:pt_extlist/Resources/Private/Templates/Scheduler/ExportList/TaskAdditionalField' . ucfirst($field) .'.html'));
        return $view;
    }


    /**
     * @param array $submittedData
     * @param SchedulerModuleController $schedulerModule
     * @return bool
     */
    public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule)
    {
        $submittedData[$this->configuration['pageId']] = trim($submittedData[$this->configuration['pageId']]);
        $submittedData[$this->configuration['listIdentifier']] = trim($submittedData[$this->configuration['listIdentifier']]);
        return true;
    }

    /**
     * @param array $submittedData
     * @param AbstractTask $task
     */
    public function saveAdditionalFields(array $submittedData, AbstractTask $task)
    {
        $configuration = $this->configuration;

        $pageId = $configuration['pageId'];
        $task->$pageId = $submittedData[$pageId];

        $listIdentifier = $configuration['listIdentifier'];
        $task->$listIdentifier = $submittedData[$listIdentifier];
    }

}
