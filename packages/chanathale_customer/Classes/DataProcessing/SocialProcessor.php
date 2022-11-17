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

namespace Chanathale\ChanathaleCustomer\DataProcessing;

use Chanathale\ChanathaleCustomer\Service\SocialService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class SocialProcessor
 */
class SocialProcessor implements \TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface
{

    /**
     * @var SocialService|null $socialService
     */
    protected ?SocialService $socialService = null;

    /**
     * SocialProcessor constructor.
     */
    public function __construct()
    {
        /** @var SocialService socialService */
        $this->socialService = GeneralUtility::makeInstance(SocialService::class);
    }

    /**
     * process
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array|void
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $processedData['socialIcon'] = $this->socialService->findAll();
        return $processedData;
    }
}