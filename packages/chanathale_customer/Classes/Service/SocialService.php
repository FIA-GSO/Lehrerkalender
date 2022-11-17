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

namespace Chanathale\ChanathaleCustomer\Service;

use Chanathale\ChanathaleCustomer\Domain\Repository\SocialRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * Class SocialService
 */
class SocialService implements \TYPO3\CMS\Core\SingletonInterface
{

    /**
     * @var SocialRepository|null $socialRepository
     */
    protected ?SocialRepository $socialRepository = null;

    /**
     * SocialService constructor.
     */
    public function __construct()
    {
        /** @var SocialRepository socialRepository */
        $this->socialRepository = GeneralUtility::makeInstance(SocialRepository::class);
    }

    /**
     * findAll
     * @return QueryResultInterface
     */
    public function findAll(): QueryResultInterface
    {
        return $this->socialRepository->findAll();
    }
}