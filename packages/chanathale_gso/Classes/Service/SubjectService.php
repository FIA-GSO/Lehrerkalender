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

use Chanathale\ChanathaleGso\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * SubjectService
 */
class SubjectService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * @var UserService|null
     */
    protected ?UserService $userService = null;

    /**
     * @var SubjectRepository|null
     */
    protected ?SubjectRepository $subjectRepository = null;

    /**
     * construct
     */
    public function __construct () {
        $this->subjectRepository = GeneralUtility::makeInstance(SubjectRepository::class);
        $this->userService = GeneralUtility::makeInstance(UserService::class);
    }

    /**
     * findAllSubjectByFrontendUser
     * @return QueryResultInterface|null
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function findAllSubjectByFrontendUser () : ?QueryResultInterface {
        $feUser = $this->userService->getFeUserData();

        if (empty($feUser)) {
            return null;
        }

        if (array_key_exists('uid', $feUser)) {
            try {
                // Holt alle Fächer des Bneutzer, den der Abteilungsleiter ihn zugewiesen.
                return $this->subjectRepository->_findAllByFrontendUser($feUser['uid']);
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }
}