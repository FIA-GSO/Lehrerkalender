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

namespace Chanathale\ChanathaleGso\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * ClassroomRepository
 */
class ClassroomRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /**
     * initializeObject
     * @return void
     */
    public function initializeObject () : void {
        $ordering = ["title" => QueryInterface::ORDER_ASCENDING];
        /** @var QuerySettingsInterface $querySettings */
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);

        $this->setDefaultOrderings($ordering);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * _findAllByFrontendUser
     * @param int $frontUserUid
     * @return QueryResultInterface|null
     * @throws Exception
     * @throws DBALException|InvalidQueryException
     */
    public function _findAllByFrontendUser (int $frontUserUid) : ?QueryResultInterface {

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_chanathalegso_domain_model_classroom')->createQueryBuilder();
        $uids = $queryBuilder->select('uid')
            ->from('tx_chanathalegso_domain_model_classroom')
            ->where(
                $queryBuilder->expr()->inSet('users', (string)$frontUserUid)
            )->execute()->fetchAllAssociative();

        if (count($uids) > 0) {
            $query = $this->createQuery();
            $query->matching(
                $query->in('uid', $uids)
            );

            return $query->execute();
        }

        return null;
    }

    /**
     * _findAllByFrontendUserAndSubject
     * @param int $frontendUserUid
     * @param array $classroomUids
     * @return QueryResultInterface|null
     */
    public function _findAllByFrontendUserAndUids (int $frontendUserUid, array $classroomUids) : ?QueryResultInterface {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_chanathalegso_domain_model_classroom')->createQueryBuilder();
        $constraints = [];

        foreach ($classroomUids as $uid) {
            $constraints[] = $queryBuilder->expr()->eq('uid', $uid);
        }

        $uids = $queryBuilder->select('uid')
            ->from('tx_chanathalegso_domain_model_classroom')
            ->andWhere(
                $queryBuilder->expr()->inSet('users', (string) $frontendUserUid),
                $queryBuilder->expr()->or(
                    ...$constraints
                )
            )->execute()->fetchAllAssociative();

        if (count($uids) > 0) {
            $query = $this->createQuery();
            $query->matching(
                $query->in('uid', $uids)
            );

            return $query->execute();
        }

        return null;
    }

    /**
     * getConnection
     * @return Connection
     */
    public function getConnection () : Connection {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_chanathalegso_domain_model_classroom')->getConnection();
    }
}