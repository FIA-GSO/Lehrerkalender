<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Utility;

use Exception;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Error\Http\InternalServerErrorException;
use TYPO3\CMS\Core\Error\Http\ServiceUnavailableException;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * LinkUtility
 */
class LinkUtility
{
    /**
     * getAnchor
     *
     * @param string $text
     * @return string
     */
    public static function getAnchor(string $text): string
    {
        return preg_replace(
            [
                '/\s+/',
                '/(?!\-)[[:punct:]]/',
                '/ä/',
                '/ö/',
                '/ü/',
            ],
            [
                '-',
                '',
                'ae',
                'oe',
                'ue',
            ],
            mb_strtolower($text)
        );
    }


    /**
     * getLinkCli
     *
     * @param int $pageUid
     * @param array $args
     * @param bool $absolute
     * @return string
     * @throws SiteNotFoundException
     */
    public static function getUrlCli(int $pageUid, array $args = [], bool $absolute = false): string
    {
        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        $site = $siteFinder->getSiteByPageId($pageUid);

        return (string)$site->getRouter()->generateUri($pageUid, $args);
    }

    /**
     * getUrlTypoLink
     *
     * @param string $parameter
     * @param array $additionalParams
     * @param bool $absolute
     * @param int $initFEPageUid
     * @param int $initFELanguageUid
     * @return string
     * @throws AspectNotFoundException
     * @throws InternalServerErrorException
     * @throws ServiceUnavailableException
     * @throws SiteNotFoundException
     */
    public static function getUrlTypoLink(string $parameter, array $additionalParams = [], bool $absolute = false, int $initFEPageUid = 1, int $initFELanguageUid = 0): string
    {
        // init FE context if called from Command/CLI context
        TSFEUtility::initFE($initFEPageUid, $initFELanguageUid);

        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        return $contentObjectRenderer->typoLink_URL([
            'parameter' => $parameter,
            'forceAbsoluteUrl' => $absolute,
            'additionalParams' => GeneralUtility::implodeArrayForUrl(NULL, $additionalParams)
        ]);
    }

    /**
     * warmupUrl
     *
     * @param string $url
     * @return void
     * @throws Exception
     */
    public static function warmupUrl(string $url): void
    {
        if (GeneralUtility::isValidUrl($url)) {
            $contextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $content = file_get_contents($url, false, stream_context_create($contextOptions));

            if (!$content) {
                throw new Exception(sprintf("warmup fetch failed for url '%s'", $url));
            }
        } else {
            throw new Exception(sprintf("warmup invalid url '%s'", $url));
        }
    }
}
