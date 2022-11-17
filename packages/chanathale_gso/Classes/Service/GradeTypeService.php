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

use Chanathale\ChanathaleGso\Domain\Model\GradeType;
use Chanathale\ChanathaleGso\Domain\Model\Performance;
use Chanathale\ChanathaleGso\Domain\Model\Subject;
use Chanathale\ChanathaleGso\Domain\Repository\GradeTypeRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * GradeTypeService
 */
class GradeTypeService implements SingletonInterface {

    /**
     * @var GradeTypeRepository|null $gradeTypeRepository
     */
    protected ?GradeTypeRepository $gradeTypeRepository = null;

    /**
     * GradeTypeService constructor.
     */
    public function __construct () {
        /** @var GradeTypeRepository gradeTypeRepository */
        $this->gradeTypeRepository = GeneralUtility::makeInstance(GradeTypeRepository::class);
    }

    /**
     * findAllGradeTypes
     * @return QueryResultInterface|null
     */
    public static function findAllGradeTypes () : ?QueryResultInterface {
        /** @var GradeTypeRepository $repo */
        $repo = GeneralUtility::makeInstance(GradeTypeRepository::class);

        // Holt die alle Leistungsarten (z.B Klausuren)
        return $repo->findAll();
    }

    /**
     * hasPerformances
     * @param QueryResultInterface|null $gradeTypes
     * @param ObjectStorage<Performance>|array $performances
     * @return array
     */
    public static function hasPerformances (?QueryResultInterface $gradeTypes, array|ObjectStorage $performances) : array {
        $array = [];

        /** @var GradeType $gradeType */
        foreach ($gradeTypes->toArray() as $gradeType) {

            /** @var Performance $performance */
            foreach ($performances as $performance) {
                $array[$gradeType->getUid()] = false;

                // Prüft ob der Schpler irgendwelche Noten / Leistungen / Bemerkungen hat für den Leistungsart
                if ($performance->getGradeType()->getUid() === $gradeType->getUid()) {
                    $array[$gradeType->getUid()] = true;
                    break;
                }
            }
        }

        return $array;
    }

    /**
     * getCommentGradeType
     * @param string $title
     * @return GradeType|null
     */
    public static function getCommentGradeType (string $title = "Bemerkungen") : ?GradeType {
        /** @var GradeTypeRepository $repo */
        $repo = GeneralUtility::makeInstance(GradeTypeRepository::class);

        return $repo->findOneByTitle($title) ?? null;
    }

    /**
     * filterByGradeType
     * @param GradeType $gradeType
     * @param ObjectStorage $performances
     * @return array
     */
    public static function filterByGradeType (GradeType $gradeType, ObjectStorage $performances) : array {
        $array = [];

        if ($performances instanceof ObjectStorage) {

            foreach ($performances->toArray() as $performance) {

                if ($performance->getGradeType()->getUid() === $gradeType->getUid()) {
                    $array[] = $performance;
                }
            }
        } else {

            foreach ($performances as $performance) {

                if ($performance->getGradeType()->getUid() === $gradeType->getUid()) {
                    $array[] = $performance;
                }
            }
        }


        return $array;
    }

    /**
     * filterBySubject
     * @param Subject $subject
     * @param ObjectStorage<Performance>|array $performances
     * @return array
     */
    public static function filterBySubject (Subject $subject, array|ObjectStorage $performances) : array {
        $array = [];

        if ($performances instanceof ObjectStorage) {
            foreach ($performances->toArray() as $performance) {

                if ($performance->getSubject()->getUid() === $subject->getUid()) {
                    $array[] = $performance;
                }
            }
        } else {
            foreach ($performances as $performance) {

                if ($performance->getSubject()->getUid() === $subject->getUid()) {
                    $array[] = $performance;
                }
            }
        }


        return $array;
    }

}