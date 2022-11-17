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

namespace Chanathale\ChanathaleGso\Service;

use Chanathale\ChanathaleGso\Domain\Model\Classroom;
use Chanathale\ChanathaleGso\Domain\Model\Event;
use Chanathale\ChanathaleGso\Domain\Model\Room;
use Chanathale\ChanathaleGso\Domain\Model\Subject;
use Exception;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * CalendarService
 */
class CalendarService implements SingletonInterface {

    /**
     * @var UserService|null
     */
    protected ?UserService $userService = null;

    /**
     * @var MeetingService|null $meetingService
     */
    protected ?MeetingService $meetingService = null;

    public function __construct () {
        /** @var UserService userService */
        $this->userService = GeneralUtility::makeInstance(UserService::class);
        /** @var MeetingService meetingService */
        $this->meetingService = GeneralUtility::makeInstance(MeetingService::class);
    }

    /**
     * getMeetingModal
     * @return QueryResultInterface|null
     */
    public function getMeetingModal () : ?QueryResultInterface {
        return $this->meetingService->findAllMeetingsByFrontendUser();
    }

    /**
     * getFeUserUid
     * @return int|null
     */
    public function getFeUserUid () : ?int {
        // Holt die ID des angemeldeten Benutzer
        return $this->userService->getFeUserData()['uid'] ?? null;
    }

    /**
     * getEvents
     * @return array|null
     */
    public function getEvents () : ?array {
        $feUser = $this->getFeUserUid();

        if (!empty($feUser)) {
            $jsonFile = null;

            // Lädt die JSON-Datei des Benutzers für den Lehrerkalender, falls die nicht gibt erstelle ine JSON-Datei.
            if (file_exists(Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json')) {
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            } else {
                file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode([]));
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            }

            if (!empty($jsonFile)) {
                // Wandelt JSON in array um
                $storedEventData = json_decode(file_get_contents($jsonFile), true);

                if (is_array($storedEventData)) {

                    if (count($storedEventData) > 0) {
                        return $storedEventData;
                    }
                }
            }
        }

        return null;
    }

    public function getEventsModel () : ?array {
        $feUser = $this->getFeUserUid();
        $eventObjects = [];

        if (!empty($feUser)) {
            $jsonFile = null;

            // Liest die JSON des aktuellen Benutzer und falls es die nicht gibt erstellt die.
            if (file_exists(Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json')) {
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            } else {
                file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode([]));
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            }

            if (!empty($jsonFile)) {
                // Wnadelt JSON in einen array um
                $storedEventData = json_decode(file_get_contents($jsonFile), true);

                if (is_array($storedEventData)) {

                    if (count($storedEventData) > 0) {

                        // Die einzelene Unterrichtseinheiten werden in einen Entity gemapped.
                        foreach ($storedEventData as $key => $eventObject) {
                            $classroom = new Classroom();
                            $classroom->_setProperty('uid', $eventObject['classroom']);
                            $room = new Room();
                            $room->_setProperty('uid', $eventObject['room']);
                            $subject = new Subject();
                            $subject->_setProperty('uid', $eventObject['subject']);

                            /** @var Event $event */
                            $event = new Event();
                            $event->setId($key);
                            $event->setContent($eventObject['content']);
                            $event->setStart($eventObject['date']);
                            $event->setEnd($eventObject['duration']);
                            $event->setSubject($subject);
                            $event->setClassroom($classroom);
                            $event->setRoom($room);
                            $event->setDateTime((int) $eventObject['tstamp']);
                            $eventObjects[] = $event;
                        }

                        return $eventObjects;
                    }
                }
            }
        }

        return null;
    }

    /**
     * saveEvent
     * @param Event $event
     * @return array|null
     */
    public function saveEvent (Event $event) : ?array {
        $feUser = $this->getFeUserUid();

        if (!empty($feUser)) {
            $jsonFile = null;

            // Liest die JSON des aktuellen Benutzer und falls es die nicht gibt erstellt die.
            if (file_exists(Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json')) {
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            } else {
                file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode([]));
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            }

            if (!empty($jsonFile)) {
                $storedEventData = json_decode(file_get_contents($jsonFile), true);

                if (is_array($storedEventData)) {

                    if ($event->getId() > 0) {
                        $date = $event->getStart();
                        $duration = $event->getEnd();
                        $startTImeUnix = strtotime($event->getStart());
                        $endTimeUnix = $startTImeUnix + ($event->getEnd() * 60);
                        $event->setStart(date('H:i:s', $startTImeUnix));
                        $event->setEnd(date('H:i:s', $endTimeUnix));
                        $endDate = new \DateTime(date('Y-m-d', $event->getDate()));
                        $endDate->modify('+1 day');

                        // Wandelt das Entity in eine array für die JSON-Datei.
                        $object = [
                            'jsonKey' => $event->getId(),
                            'title' => $event->getSubject()->getTitle() . ' ( ' . $event->getClassroom()->getTitle() . ' in ' . $event->getRoom()->getTitle() . ' )',
                            'start' => date('Y-m-d', $event->getDate()) . 'T' . $event->getStart(),
                            'end' => date('Y-m-d', $event->getDate()) . 'T' . $event->getEnd(),
                            'backgroundColor' => $event->getSubject()->getColor(),
                            'borderColor' => $event->getSubject()->getColor(),
                            'content' => $event->getContent(),
                            'subject' => $event->getSubject()->getUid(),
                            'date' => $date,
                            'duration' => $duration,
                            'tstamp' => $startTImeUnix,
                            'classroom' => $event->getClassroom()->getUid(),
                            'room' => $event->getRoom()->getUid()
                        ];

                        // Speichert das object-array in die JSON-Datei.
                        $storedEventData[$event->getId()] = $object;
                        $saved = file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode($storedEventData));

                        if ($saved !== false) {
                            return $object;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Event $event
     * @return array|null
     * @throws Exception
     */
    public function addEvent (Event $event) : ?array {
        $feUser = $this->getFeUserUid();

        if (!empty($feUser)) {
            $jsonFile = null;

            // Liest die JSON des aktuellen Benutzer und falls es die nicht gibt erstellt die.
            if (file_exists(Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json')) {
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            } else {
                file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode([]));
                $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
            }

            if (!empty($jsonFile)) {
                $storedEventData = json_decode(file_get_contents($jsonFile), true);

                if (is_array($storedEventData)) {

                    if ($event->getId() === 0) {
                        $date = $event->getStart();
                        $duration = $event->getEnd();
                        $startTImeUnix = strtotime($event->getStart());
                        $endTimeUnix = $startTImeUnix + ($event->getEnd() * 60);
                        $event->setStart(date('H:i:s', $startTImeUnix));
                        $event->setEnd(date('H:i:s', $endTimeUnix));
                        $endDate = new \DateTime(date('Y-m-d', $event->getDate()));
                        $endDate->modify('+1 day');
                        $time = time();

                        $object = [
                            'jsonKey' => $time,
                            'title' => $event->getSubject()->getTitle() . ' ( ' . $event->getClassroom()->getTitle() . ' in ' . $event->getRoom()->getTitle() . ' )',
                            'start' => date('Y-m-d', $event->getDate()) . 'T' . $event->getStart(),
                            'end' => date('Y-m-d', $event->getDate()) . 'T' . $event->getEnd(),
                            'backgroundColor' => $event->getSubject()->getColor(),
                            'borderColor' => $event->getSubject()->getColor(),
                            'content' => $event->getContent(),
                            'subject' => $event->getSubject()->getUid(),
                            'date' => $date,
                            'duration' => $duration,
                            'tstamp' => $startTImeUnix,
                            'classroom' => $event->getClassroom()->getUid(),
                            'room' => $event->getRoom()->getUid(),
                        ];

                        $storedEventData[$time] = $object;
                        $saved = file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode($storedEventData));

                        if ($saved !== false) {
                            return $object;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * deleteEventByJsonKey
     * @param int $jsonKey
     * @return bool
     */
    public function deleteEventByJsonKey (int $jsonKey) : bool {
        $feUser = $this->getFeUserUid();

        // Liest die JSON des aktuellen Benutzer und falls es die nicht gibt erstellt die.
        if (file_exists(Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json')) {
            $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
        } else {
            file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode([]));
            $jsonFile = Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json';
        }

        if (!empty($jsonFile)) {
            $storedEventData = json_decode(file_get_contents($jsonFile), true);
            // Entfernt die Unterrichtsinhalt aus dem array und schreibt das array als JSON.
            unset($storedEventData[$jsonKey]);
            $saved = file_put_contents((Environment::getPublicPath() . '/fileadmin/calendar_jsons/' . $feUser . '-data.json'), json_encode($storedEventData));

            if ($saved !== false) {
                return true;
            }
        }

        return false;
    }

}