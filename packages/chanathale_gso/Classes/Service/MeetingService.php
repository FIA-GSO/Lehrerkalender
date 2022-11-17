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

namespace Chanathale\ChanathaleGso\Service;

use Chanathale\ChanathaleGso\Domain\Model\Meeting;
use Chanathale\ChanathaleGso\Domain\Repository\MeetingRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Reflection\Exception\UnknownClassException;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * MeetingService
 */
class MeetingService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var MeetingRepository|null $meetingRepository
     */
    protected ?MeetingRepository $meetingRepository = null;

    /**
     * @var PersistenceManager|null $persistenceManager
     */
    protected ?PersistenceManager $persistenceManager = null;

    /**
     * @var UserService|null $userService
     */
    protected ?UserService $userService = null;

    /**
     * construct
     */
    public function __construct () {
        /** @var MeetingRepository meetingRepository */
        $this->meetingRepository = GeneralUtility::makeInstance(MeetingRepository::class);

        /** @var PersistenceManager persistenceManager */
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        /** @var UserService userService */
        $this->userService = GeneralUtility::makeInstance(UserService::class);
    }

    /**
     * setFullCalendarData
     * @param Meeting $meeting
     * @return void
     */
    public function setFullCalendarData (Meeting &$meeting) : void {
        // Format 2022-09-26T07:45:00
        // wandelt die Startzeit und das Datum in den Forma tin dme der Kalender braucht.
        $dateTime = date('Y-m-d', ((int) $meeting->getCreateDate())) . 'T' . (date('h:i:s', strtotime($meeting->getStartTime())));
        $endTime = date('Y-m-d', ((int) $meeting->getCreateDate())) . 'T' . (date('h:i:s', (strtotime(($meeting->getStartTime())) + $meeting->getDuration() * 60)));

        $meeting->setFullCalendarStart($dateTime);
        $meeting->setFullCalendarEnd($endTime);
    }

    /**
     * addMeeting
     * @param Meeting $meeting
     * @return bool
     */
    public function addMeeting (Meeting $meeting) : bool {
        // Fügt Meeting ins DB
        $feUser = $this->userService->getFeUserData();
        $meeting->setAuthor($feUser['uid'] ?? 0);
        $this->setFullCalendarData($meeting);

        try {
            $this->meetingRepository->add($meeting);
            $this->persistenceManager->persistAll();
            return true;
        } catch (IllegalObjectTypeException|UnknownClassException $e) {
            DebuggerUtility::var_dump($e->getMessage());
        }

        return false;
    }


    /**
     * updateMeeting
     * @param Meeting $meeting
     * @return bool
     */
    public function updateMeeting (Meeting $meeting) : bool {
        // update den Datensatz
        $feUser = $this->userService->getFeUserData();
        $meeting->setAuthor($feUser['uid'] ?? 0);
        $this->setFullCalendarData($meeting);

        try {
            $this->meetingRepository->update($meeting);
            $this->persistenceManager->persistAll();
            return true;
        } catch (UnknownObjectException|IllegalObjectTypeException $e) {
            DebuggerUtility::var_dump($e->getMessage());
        }

        return false;
    }

    /**
     * hasMeeting
     * @param Meeting $meeting
     * @return bool
     */
    public function hasMeeting (Meeting $meeting) : bool {
        // Prüft ob das Meeting schon in der DB gibt.
        if (!empty($meeting->getUid())) {
            $foundMeeting = $this->meetingRepository->findOneByUid($meeting->getUid());

            if ($foundMeeting instanceof Meeting) {
                return true;
            }
        }

        return false;
    }

    /**
     * findAllMeetingsByFrontendUser
     * @return QueryResultInterface|null
     */
    public function findAllMeetingsByFrontendUser () : ?QueryResultInterface {
        // Lädt alle Meetings des Benutzers.
        $feUser = $this->userService->getFeUserData();
        $meetings = $this->meetingRepository->findByAuthor(($feUser['uid'] ?? 0));

        if ($meetings instanceof QueryResultInterface) {
            return $meetings;
        }

        return null;
    }

    /**
     * prepareFullCalendar
     * @param QueryResultInterface|null $queryResult
     * @return array
     */
    public function prepareFullCalendar (?QueryResultInterface $queryResult) : array {
        $objects = [];

        foreach ($queryResult as $record) {

            if ($record instanceof Meeting) {
                $object = $this->prepareFullCalendarModal($record);
                $objects[$record->getUid()] = $object;
            }
        }

        return $objects;
    }

    /**
     * prepareFullCalendarModal
     * @param Meeting $record
     * @return array
     */
    public function prepareFullCalendarModal (Meeting $record) : array {
        return [
            'uid' => $record->getUid(),
            'title' => $record->getTopic() . ' ( ' . $record->getRoom()->getTitle() . ' )',
            'start' => $record->getFullCalendarStart(),
            'end' => $record->getFullCalendarEnd()
        ];
    }

    /**
     * deleteMeetingByUid
     * @param int $uid
     * @return bool
     * @throws IllegalObjectTypeException
     */
    public function deleteMeetingByUid (int $uid) : bool {
        // Löscht das Meeting aus der DB.
        $object = $this->meetingRepository->findOneByUid($uid);
        if ($object instanceof Meeting) {
            $this->meetingRepository->remove($object);
            $this->persistenceManager->persistAll();

            return true;
        }

        return false;
    }

}