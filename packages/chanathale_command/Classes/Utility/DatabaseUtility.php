<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_command.
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

namespace Chanathale\ChanathaleCommand\Utility;

use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * DatabaseUtility
 */
class DatabaseUtility
{
    /**
     * @param string $name
     * @return \TYPO3\CMS\Core\Database\Connection
     */
    public static function getConnection(string $name = ConnectionPool::DEFAULT_CONNECTION_NAME): ?Connection
    {
        /** @var \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        try {
            $connection = $connectionPool->getConnectionByName($name);
        } catch (DBALException $exception) {
            $connection = null;
        }

        return $connection;
    }
}
