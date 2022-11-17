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

namespace Chanathale\ChanathaleGso\Service;

use Chanathale\ChanathaleBase\Utility\SlugUtility;
use Chanathale\ChanathaleGso\Domain\Model\Classroom;
use Chanathale\ChanathaleGso\Domain\Model\Pupil;
use Chanathale\ChanathaleGso\Domain\Repository\ClassroomRepository;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * ClassroomService
 */
class ClassroomService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var ClassroomRepository|null $classroomRepository
     */
    protected ?ClassroomRepository $classroomRepository = null;

    /**
     * @var PersistenceManager|null
     */
    protected ?PersistenceManager $persistenceManager = null;

    /**
     * @var UserService|null
     */
    protected ?UserService $userService = null;

    /**
     *__construct
     */
    public function __construct () {
        /** @var ClassroomRepository classroomRepository */
        $this->classroomRepository = GeneralUtility::makeInstance(ClassroomRepository::class);
        /** @var PersistenceManager persistenceManager */
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        /** @var UserService userService */
        $this->userService = GeneralUtility::makeInstance(UserService::class);
    }

    /**
     * findAllClassrooms
     * @return QueryResultInterface
     */
    public static function findAllClassrooms () : QueryResultInterface {
        $classroomRepository = GeneralUtility::makeInstance(ClassroomRepository::class);
        return $classroomRepository->findAll();
    }

    /**
     * findAllClassroomsByFrontendUser
     * @return QueryResultInterface|null
     */
    public function findAllClassroomsByFrontendUser () : ?QueryResultInterface {
        $feUser = $this->userService->getFeUserData();

        if (array_key_exists('uid', $feUser)) {
            return $this->classroomRepository->_findAllByFrontendUser($feUser['uid']);
        }

        return null;
    }

    /**
     * @param Classroom $classroom
     * @return bool
     */
    public function saveClassroom (Classroom $classroom) : bool {
        try {
            $feUser = $this->userService->getFeUserData();

            if (empty($feUser)) {
                return false;
            }

            if (array_key_exists('uid', $feUser)) {
                // Prüft ob es diesen Klasse schon in der Datenbank gibt.
                $existClassroom = $this->classroomRepository->findOneByTitle($classroom->getTitle());

                // Falls es den gibt update den Datensatz, sont erstelle ihn.
                if ($existClassroom instanceof Classroom) {
                    $classroom->setUsers(FrontendUserService::addFrontendUser($existClassroom->getUsers(), $feUser['uid']));
                    $this->classroomRepository->update($classroom);
                } else {
                    $classroom->setUsers(FrontendUserService::addFrontendUser($classroom->getUsers(), $feUser['uid']));
                    $this->classroomRepository->add($classroom);
                }
            }

            $this->persistenceManager->persistAll();
            $uid = $classroom->getUid();
            // update den URL-Slug, der für die Klassendetail-Seite gebraucht wird.
            SlugUtility::generateSlugsForTable('tx_chanathalegso_domain_model_classroom', 'url_slug', 'title', $this->classroomRepository->getConnection());
            $this->persistenceManager->persistAll();

            if (!empty($uid)) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * @param Pupil $pupil
     * @param Classroom|null $classroom
     * @return bool
     */
    public function addPupilToClassroom (Pupil $pupil, Classroom $classroom = null) : bool {
        $class = $classroom;

        if (empty($classroom) && $pupil->getClassroom() instanceof Classroom) {
            $class = $pupil->getClassroom();
        }

        try {

            if ($class instanceof Classroom) {
                $added = $class->addPupil($pupil);

                // Fügt den Schüler der ausgewählten Klasse
                if ($added) {
                    $this->classroomRepository->update($class);
                    $this->persistenceManager->persistAll();

                    return true;
                }
            }
        } catch (Exception $exception) {
            return false;
        }

        return false;
    }

    /**
     * findAllByFrontendUserAndClassroomUids
     * @param ObjectStorage<Classroom> $classrooms
     * @return QueryResultInterface|null
     */
    public function findAllByFrontendUserAndClassroomUids (ObjectStorage $classrooms) : ?QueryResultInterface {
        $uids = [];

        foreach ($classrooms->toArray() as $classroom) {
            if ($classroom instanceof Classroom) {
                $uids[] = $classroom->getUid();
            }
        }

        $feUser = $this->userService->getFeUserData();

        if (empty($feUser)) {
            return null;
        }

        if (array_key_exists('uid', $feUser)) {
            // Lädt alle Klasse, die der Benutzer zu Verfügung steht.
            return $this->classroomRepository->_findAllByFrontendUserAndUids($feUser['uid'], $uids);
        }

        return null;
    }

}