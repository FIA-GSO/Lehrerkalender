<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_customer.
 *
 * (c) 2022 Aphisit Chanathale <chanathale@mindshape.de>, mindshape GmbH
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

namespace Chanathale\ChanathaleCustomer\Controller;

use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * FrontendLoginController
 */
class FrontendLoginController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var UserService|null $userService
     */
    protected ?UserService $userService = null;

    /**
     * @var UserAspect|null $userAspect
     */
    protected ?UserAspect $userAspect = null;

    /**
     * construct
     */
    public function __construct () {
        /** @var UserService userService */
        $this->userService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(UserService::class);
        /** @var UserAspect userAspect */
        $this->userAspect = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(UserAspect::class);
    }

    /**
     * showStatusAction
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showStatusAction () : \Psr\Http\Message\ResponseInterface {
        $feUserData = $this->userService->getFeUserData();
        $isLoggedIn = false;

        if (!empty($feUserData)) {
            $isLoggedIn = true;
        }

        $this->view->assign('feUserData', $this->userService->getFeUserData());
        $this->view->assign('isLoggedIn', $isLoggedIn);

        return $this->htmlResponse();
    }

}