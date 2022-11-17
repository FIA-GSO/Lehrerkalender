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
 * PupilRepository
 */
class PupilRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /**
     * initializeObject
     * @return void
     */
    public function initializeObject() : void {
        $ordering = ["lastname" => QueryInterface::ORDER_ASCENDING];
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
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_chanathalegso_domain_model_pupil')->createQueryBuilder();
        $uids = $queryBuilder->select('uid')
            ->from('tx_chanathalegso_domain_model_pupil')
            ->where(
                $queryBuilder->expr()->inSet('users', (string)$frontUserUid)
            )->execute()->fetchAllAssociative();

        if (count($uids) > 0) {
            $query = $this->createQuery();
            $query->matching(
                $query->in('uid', $uids)
            );

            return $models = $query->execute();
        }

        return null;
    }

    /**
     * getConnection
     * @return Connection
     */
    public function getConnection () : Connection {
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_chanathalegso_domain_model_pupil')->getConnection();
    }
}