<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_gso.
 *
 * (c) 2022 Aphisit Chanathale <chanathale@chanathale.de>, chanathale GmbH
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Chanathale\ChanathaleGso\DataProcessing;

use Chanathale\ChanathaleGso\Domain\Repository\ClassroomRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class AddClassroomTitleToMenuProcessor implements \TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface {

    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (!$processorConfiguration['menus']) {
            return $processedData;
        }

        if (!ExtensionManagementUtility::isLoaded('chanathale_gso')) {
            return $processedData;
        }

        $record = $this->getRecord();

        if ($record) {
            $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
            foreach ($menus as $menu) {
                if (isset($processedData[$menu])) {
                    $this->addRecordToMenu($record, $processedData[$menu]);
                }
            }
        }

        return $processedData;
    }

    /**
     * getRecord
     * @return array|null
     */
    public function getRecord() : ?array {
        $vars = GeneralUtility::_GET('tx_chanathalegso_classroomdetail');

        if(!isset($vars['classroom'])){
            return null;
        }

        $recordUid = (int)$vars['classroom'];
        $recordRepository = GeneralUtility::makeInstance(ClassroomRepository::class);
        $record = $recordRepository->findByUid($recordUid);
        $fields = ['title'];
        $recordAsArray = [];

        foreach ($fields as $field) {
            $recordAsArray[$field] = $record->_getProperty($field);
        }

        return $recordAsArray;
    }

    /**
     * addRecordToMenu
     * @param $record
     * @param array $menu
     * @return void
     */
    public function addRecordToMenu ($record, array &$menu) : void {
        // remove last element
        array_pop($menu);

        $menu[] = [
            'data' => $record,
            'title' => $record['title'],
            'active' => 1,
            'current' => 1,
            'link' => GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            'isRecord' => true
        ];
    }
}