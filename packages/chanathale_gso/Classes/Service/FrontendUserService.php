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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FrontendUserService
 */
class FrontendUserService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * addFrontendUser
     * @param string $usersString
     * @param int $frontendUserUid
     * @return string
     */
    public static function addFrontendUser (string $usersString, int $frontendUserUid) : string {
        $userArray = GeneralUtility::intExplode(',', $usersString);

        if (!in_array($frontendUserUid, $userArray)) {
            $userArray[] = $frontendUserUid;
        }

        return implode(',', $userArray);
    }

    public static function removeFrontendUser (string $usersString, int $frontendUserUid) : string {
        $userArray = GeneralUtility::intExplode(',', $usersString);

        if (in_array($frontendUserUid, $userArray)) {
            $temp[] = $frontendUserUid;
            $userArray = array_diff($userArray, $temp);
        }

        return implode(',', $userArray);
    }
}