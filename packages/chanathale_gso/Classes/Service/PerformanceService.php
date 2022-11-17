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

use Chanathale\ChanathaleGso\Domain\Model\Grade;
use Chanathale\ChanathaleGso\Domain\Model\Performance;
use Chanathale\ChanathaleGso\Domain\Model\Pupil;
use Chanathale\ChanathaleGso\Domain\Repository\GradeRepository;
use Chanathale\ChanathaleGso\Domain\Repository\PerformanceRepository;
use Chanathale\ChanathaleGso\Domain\Repository\PupilRepository;
use DateTime;
use Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * GradeService
 */
class PerformanceService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var PerformanceRepository|null
     */
    protected ?PerformanceRepository $performanceRepository = null;

    /**
     * @var PupilRepository|null
     */
    protected ?PupilRepository $pupilRepository = null;

    /**
     * @var UserService|null $userService
     */
    protected ?UserService $userService = null;

    /**
     * @var PersistenceManager|null $persistenceManager
     */
    protected ?PersistenceManager $persistenceManager = null;

    public function __construct () {
        /** @var Performance gradeRepository */
        $this->performanceRepository = GeneralUtility::makeInstance(PerformanceRepository::class);
        /** @var PupilRepository pupilRepository */
        $this->pupilRepository = GeneralUtility::makeInstance(PupilRepository::class);
        /** @var UserService userService */
        $this->userService = GeneralUtility::makeInstance(UserService::class);
        /** @var PersistenceManager persistenceManager */
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
    }

    /**
     * @param Performance $performance
     * @return bool
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function addPerformanceToPupil (Performance $performance) : bool {
        // Fügt die Leistung / Bemerkungen / NOten dem Schüler hinzu.
        $feUser = $this->userService->getFeUserData();

        if (array_key_exists('uid', $feUser)) {
            $existPupil = $this->pupilRepository->findOneByUid($performance->getPupil()->getUid());

            if ($existPupil instanceof Pupil) {
                $performance->setAuthor($feUser['uid']);

                $added = $existPupil->addGrade($performance);
                $this->pupilRepository->update($existPupil);
                $this->persistenceManager->persistAll();

                return $added;
            }
        }

        return false;
    }

    /**
     * filterByDate
     * @param ObjectStorage<Performance> $performances
     * @param DateTime $dateTime
     * @return array
     * @throws Exception
     */
    public function filterByDate (ObjectStorage $performances, DateTime $dateTime) : array {
        $filterMonth = (int) $dateTime->format('n');
        $filterYear = (int) $dateTime->format('Y');
        $filterDay = (int) $dateTime->format('d');
        $items = $performances->getArray();
        $filteredItems = [];

        foreach ($items as $item) {

            if ($item instanceof Performance) {
                $createMonth = (int) date('n', $item->getCreateDate());
                $createYear = (int) date('Y', $item->getCreateDate());
                $createDay = (int) date('d', $item->getCreateDate());

                if ($filterMonth === $createMonth && $filterYear === $createYear && $filterDay === $createDay) {
                    $filteredItems[] = $item;
                }
            }
        }

        return $filteredItems;
    }

    /**
     * savePerformance
     * @param Performance $performance
     * @return void
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function savePerformance (Performance $performance) : void {
        $this->performanceRepository->update($performance);
        $this->persistenceManager->persistAll();
    }

    /**
     * exist
     * @param Performance $performance
     * @return bool
     */
    public function exist (Performance $performance) : bool {
        $model = $this->performanceRepository->findOneByUid($performance->getUid());

        if ($model instanceof Performance) {
            return true;
        }

        return false;
    }

    /**
     * deleteByUid
     * @param int $uid
     * @return bool
     * @throws IllegalObjectTypeException
     */
    public function deleteByUid (int $uid) : bool {
        $object = $this->performanceRepository->findOneByUid($uid);
        if ($object instanceof Performance) {
            $this->performanceRepository->remove($object);
            return true;
        }

        return false;
    }
}