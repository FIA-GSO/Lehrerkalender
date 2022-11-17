<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_customer.
 *
 * (c) 2022 Aphisit Chanathale <chanathaleaphisit@gmail.com>, chanathale GmbH
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
use Chanathale\ChanathaleGso\Domain\Model\Pupil;
use Chanathale\ChanathaleGso\Domain\Model\User;
use Chanathale\ChanathaleGso\Domain\Repository\PupilRepository;
use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\FrontendLogin\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * PupilService
 */
class PupilService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var PupilRepository|null $pupilRepository
     */
    protected ?PupilRepository $pupilRepository = null;

    /**
     * @var PersistenceManager|null $persistenceManager
     */
    protected ?PersistenceManager $persistenceManager = null;

    /**
     * @var UserService|null $userService
     */
    protected ?UserService $userService = null;

    /**
     * PupilService constructor.
     */
    public function __construct (FrontendUserRepository $frontendUserRepository) {
        /** @var PupilRepository pupilRepository */
        $this->pupilRepository = GeneralUtility::makeInstance(PupilRepository::class);
        /** @var PersistenceManager persistenceManager */
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        /** @var UserService userService */
        $this->userService = GeneralUtility::makeInstance(UserService::class);
    }

    /**
     * findAllPupils
     * @return QueryResultInterface
     */
    public static function findAllPupils (): QueryResultInterface {
        /** @var PupilRepository $repo */
        $repo = GeneralUtility::makeInstance(PupilRepository::class);

        return $repo->findAll();
    }

    /**
     * findAllPupilsByFrontendUser
     * @return QueryResultInterface|null
     */
    public function findAllPupilsByFrontendUser (): ?QueryResultInterface {
        $feUser = $this->userService->getFeUserData();

        if (empty($feUser)) {
            return null;
        }

        if (array_key_exists('uid', $feUser)) {
            try {
                return $this->pupilRepository->_findAllByFrontendUser($feUser['uid']);
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * savePupil
     * @param Pupil $pupil
     * @return bool
     */
    public function savePupil (Pupil $pupil): bool {

        try {
            $feUser = $this->userService->getFeUserData();

            if (empty($feUser)) {
                return false;
            }
            $existPupil = $this->pupilRepository->findOneByUid($pupil->getUid());

            // update oder erstellt den Schüler.
            if ($existPupil instanceof Pupil) {
                $pupil->setUsers($existPupil->getUsers());
                $pupil->setUsers(FrontendUserService::addFrontendUser($existPupil->getUsers(), $feUser['uid']));
                $this->pupilRepository->update($pupil);
            } else {
                $pupil->setUsers(FrontendUserService::addFrontendUser($pupil->getUsers(), $feUser['uid']));
                $this->pupilRepository->add($pupil);
            }

            $this->persistenceManager->persistAll();
            $uid = $pupil->getUid();
            SlugUtility::generateSlugsForTable('tx_chanathalegso_domain_model_pupil', 'url_slug', 'pupil_number', $this->pupilRepository->getConnection());

            if (!empty($uid)) {

                return true;
            }
        } catch (Exception $exception) {
            return false;
        }

        return false;
    }

    /**
     * findOneByUid
     * @param int $uid
     * @return Pupil|null
     */
    public function findOneByUid (int $uid) : ?Pupil {
        $pupil = $this->pupilRepository->findOneByUid($uid);

        if ($pupil instanceof Pupil) {
            return $pupil;
        }

        return null;
    }

    /**
     * deletePupil
     * @param Pupil $pupil
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function deletePupil (Pupil $pupil) : void {
        $feUser = $this->userService->getFeUserData()['uid'] ?? '0';
        $pupil->setUsers(FrontendUserService::removeFrontendUser($pupil->getUsers(), $feUser));
        $this->pupilRepository->update($pupil);
        $this->persistenceManager->persistAll();
    }

}