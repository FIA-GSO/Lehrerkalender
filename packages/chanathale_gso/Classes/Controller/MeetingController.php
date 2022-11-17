<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_gso.
 *
 * (c) 2022 Aphisit Chanathale <chanathale@mindshape.de>, mindshape GmbH
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

namespace Chanathale\ChanathaleGso\Controller;

use Chanathale\ChanathaleGso\Domain\Model\Meeting;
use Chanathale\ChanathaleGso\Service\MeetingService;
use Chanathale\ChanathaleGso\Service\ValidationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

/**
 * MeetingController
 */
class MeetingController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var MeetingService|null $meetingService
     */
    protected ?MeetingService $meetingService = null;

    /**
     * construct
     */
    public function __construct () {
        /** @var MeetingService meetingService */
        $this->meetingService = GeneralUtility::makeInstance(MeetingService::class);
    }

    /**
     * saveAction
     * @param Meeting $meeting
     * @return ResponseInterface
     */
    public function saveAction (Meeting $meeting) : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'html' => '',
            'uid' => $meeting->getUid() ?? 0,
            'meeting' => null,
        ];
        $validationResult = ValidationService::validateEntity($this->settings, 'saveMeeting', $meeting);
        $response['validationResult'] = $validationResult;

        if ($validationResult['valid']) {
            $this->meetingService->setFullCalendarData($meeting);
            $persisted = false;

            if ($this->meetingService->hasMeeting($meeting)) {
                $persisted = $this->meetingService->updateMeeting($meeting);
            } else {
                $persisted = $this->meetingService->addMeeting($meeting);
            }

            if ($persisted) {
                $response['meeting'] = $this->meetingService->prepareFullCalendarModal($meeting);
                $response['statusCode'] = 200;
            }
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }

    /**
     * deleteAction
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction () : ResponseInterface {
        $response = [
            'statusCode' => 404,
        ];
        $uid = (int) GeneralUtility::_POST('uid') ?? '0';
        $deleted = $this->meetingService->deleteMeetingByUid($uid);

        if ($deleted) {
            $response['statusCode'] = 200;
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}