<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_xxx.
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

use Chanathale\ChanathaleGso\Domain\Model\Classroom;
use Chanathale\ChanathaleGso\Domain\Model\Pupil;
use Chanathale\ChanathaleGso\Domain\Repository\ClassroomRepository;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * ClassroomHeaderDataProcessor
 */
class ClassroomHeaderDataProcessor implements \TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface {

    public function process (ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) {
        if (!ExtensionManagementUtility::isLoaded('chanathale_gso')) {
            return $processedData;
        }
        $record = $this->getRecord();
        $processedData['classroom'] = $record;
        return $processedData;
    }

    /**
     * getRecord
     * @return Classroom|null
     */
    public function getRecord () : ?Classroom {
        $vars = GeneralUtility::_GET('tx_chanathalegso_classroomdetail');

        if (!isset($vars['classroom'])) {
            return null;
        }

        $recordUid = (int) $vars['classroom'];
        $repo = GeneralUtility::makeInstance(ClassroomRepository::class);
        $record = $repo->findOneByUid($recordUid);

        if ($record instanceof Classroom) {
            return $record;
        }

        return null;
    }
}