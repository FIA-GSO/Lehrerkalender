<?php
declare(strict_types=1);

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

namespace Chanathale\ChanathaleBase\Utility;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

/**
 * ResponseUtility
 */
class ResponseUtility
{

    /**
     * createRequest
     * @param string $url
     * @param string $method
     * @param string $body
     * @param $headers
     * @return \Psr\Http\Message\RequestInterface
     */
    public static function createRequest(string $url, string $method = "GET", string $body = "", $headers = []): \Psr\Http\Message\RequestInterface
    {
        $bodyContent = self::createBody($body);
        return GeneralUtility::makeInstance(RequestFactory::class)->createRequest($method, $url)->withBody($bodyContent);
    }

    /**
     * ResponseInterface
     * @param string $url
     * @param string $method
     * @param string $body
     * @param $headers
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function getResponse(string $url, string $method = "GET", string $body = "", $headers = []): ResponseInterface
    {
        /** @var ClientInterface $client */
        $client = GeneralUtility::makeInstance(ClientInterface::class);
        return $client->send(self::createRequest($url, $method, $body, $headers));
    }

    /**
     * createBody
     * @param string $body
     * @return StreamInterface
     */
    public static function createBody(string $body): StreamInterface
    {
        return GeneralUtility::makeInstance(StreamFactoryInterface::class)->createStream($body);
    }

}