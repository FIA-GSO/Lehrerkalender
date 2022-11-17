<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Utility;

/*
 * This file is part of the TYPO3 extension chanathale_base.
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

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * SlugUtility
 */
class SlugUtility {
    /**
     * @param string $table
     * @param string $slugField
     * @param string $segmentField
     * @param array $excludeUid
     * @param \TYPO3\CMS\Core\Database\Connection $connection
     */
    public static function generateSlugsForTable (string $table, string $slugField, string $segmentField, Connection $connection, array $excludeUid = []) : void {
        /** @var SlugHelper $slugHelper */
        $slugHelper = GeneralUtility::makeInstance(SlugHelper::class,
            $table,
            $slugField,
            $GLOBALS['TCA'][$table]['columns'][$slugField]['config'] ?? []);

        $queryBuilder = $connection->createQueryBuilder();

        if (empty($excludeUid)) {
            $queryBuilder = $queryBuilder
                ->select('pid', 'uid', $segmentField, $slugField)
                ->from($table)
                ->where($table . '.' . $slugField . ' != :null')->setParameter('null', serialize(null))
                ->andWhere($table . '.' . $slugField . ' != :empty')->setParameter('empty', serialize([]));
        } else {
            $queryBuilder = $queryBuilder
                ->select('pid', 'uid', $segmentField, $slugField)
                ->from($table)
                ->where($table . '.' . $slugField . ' != :null')->setParameter('null', serialize(null))
                ->andWhere($table . '.' . $slugField . ' != :empty')->setParameter('empty', serialize([]))
                ->andWhere(
                    $queryBuilder->expr()->notIn('uid', $excludeUid)
                );
        }
        $records = $queryBuilder->execute()->fetchAll();

        foreach ($records as $record) {
            $uid = (int) $record['uid'];
            $pid = (int) $record['pid'];
            $slug = $slugHelper->generate($record, $pid);

            $slug = str_replace('/', '-', $slug);

            try {
                $state = RecordStateFactory::forName($table)->fromArray($record, $pid, $uid);

                if (false === $slugHelper->isUniqueInSite($slug, $state)) {
                    $slug .= '-' . $uid;
                }
            } catch (SiteNotFoundException $e) {
                // nothing
            }

            $queryBuilder
                ->update($table)
                ->set($slugField, $slug)
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
                    )
                )->execute();
        }
    }
}
