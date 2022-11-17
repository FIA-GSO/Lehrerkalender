<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension mindshape_xxx.
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

use Chanathale\ChanathaleGso\Domain\Model\Event;
use Chanathale\ChanathaleGso\Domain\Model\Meeting;
use Chanathale\ChanathaleGso\Service\CalendarService;
use Chanathale\ChanathaleGso\Service\ClassroomService;
use Chanathale\ChanathaleGso\Service\MeetingService;
use Chanathale\ChanathaleGso\Service\RoomService;
use Chanathale\ChanathaleGso\Service\SubjectService;
use Chanathale\ChanathaleGso\Service\ValidationService;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CalendarController
 */
class CalendarController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var CalendarService|null
     */
    private ?CalendarService $calendarService = null;

    /**
     * @var SubjectService|null $subjectService
     */
    private ?SubjectService $subjectService = null;

    /**
     * @var ClassroomService|null $classroomService
     */
    private ?ClassroomService $classroomService = null;

    /**
     * @var MeetingService|null $meetingService
     */
    private ?MeetingService $meetingService = null;

    /**
     * construct
     */
    public function __construct () {
        $this->calendarService = GeneralUtility::makeInstance(CalendarService::class);
        $this->subjectService = GeneralUtility::makeInstance(SubjectService::class);
        $this->classroomService = GeneralUtility::makeInstance(ClassroomService::class);
        $this->meetingService = GeneralUtility::makeInstance(MeetingService::class);
    }

    /**
     * showAction
     * @return ResponseInterface
     * @throws DBALException
     * @throws Exception
     */
    public function showAction () : ResponseInterface {
        $this->view->assign('event', new Event());
        $this->view->assign('meeting', new Meeting());
        $this->view->assign('events', $this->calendarService->getEvents());
        $this->view->assign('meetings', $this->meetingService->prepareFullCalendar($this->meetingService->findAllMeetingsByFrontendUser()));
        $this->view->assign('eventObjects', $this->calendarService->getEventsModel());
        $this->view->assign('meetingObjects', $this->meetingService->findAllMeetingsByFrontendUser());
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);
        $this->view->assign('subjects', $this->subjectService->findAllSubjectByFrontendUser());
        $this->view->assign('classrooms', $this->classroomService->findAllClassroomsByFrontendUser());
        $this->view->assign('rooms', RoomService::findAll());

        return $this->htmlResponse();
    }

    /**
     * saveEventAction
     * @param Event $event
     * @return ResponseInterface
     * @throws \Exception
     */
    public function saveEventAction (Event $event) : ResponseInterface {
        $response = [
            'html' => '',
            'statusCode' => 404
        ];
        $saved = null;
        $validationResult = ValidationService::validateEntity($this->settings, 'saveEvent', $event);
        $response['validationResult'] = $validationResult;

        if ($validationResult['valid']) {
            if (empty($event->getId())) {
                $saved = $this->calendarService->addEvent($event);
            } else {
                $saved = $this->calendarService->saveEvent($event);
            }
        }

        if (is_array($saved)) {
            $response['eventObject'] = $saved;
            $response['statusCode'] = 200;
        }

        $this->view->assign('settings', $this->settings);
        $this->view->assign('event', $event);
        $this->view->assign('rooms', RoomService::findAll());
        $this->view->assign('subjects', $this->subjectService->findAllSubjectByFrontendUser());
        $this->view->assign('classrooms', $this->classroomService->findAllClassroomsByFrontendUser());
        $response['html'] = $this->view->render();

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }

    /**
     * saveMeetingAction
     * @param Meeting $meeting
     * @return ResponseInterface
     */
    public function saveMeetingAction (Meeting $meeting) : ResponseInterface {

        return $this->jsonResponse();
    }

    /**
     * deleteEventAction
     * @return ResponseInterface
     */
    public function deleteEventAction () : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'deleted' => false,
        ];
        $jsonKey = (int) (GeneralUtility::_POST('jsonKey') ?? '0');
        $response['deleted'] = $this->calendarService->deleteEventByJsonKey($jsonKey);

        if ($response['deleted']) {
            $response['statusCode'] = 200;
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}