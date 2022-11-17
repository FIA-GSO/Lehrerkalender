<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Service;

use Chanathale\ChanathaleBase\Utility\TypoScriptUtility;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * AssetsService
 */
class AssetsService implements SingletonInterface
{
    /**
     * constants
     */
    protected const TYPE_CSS = 'css';
    protected const TYPE_JS = 'js';

    protected const URL_CACHE_PARAMETER = 'v';

    protected const BROWSER_SYNC_CLIENT_IDENTIFIER = 'browser-sync-client';
    protected const BROWSER_SYNC_CLIENT_HREF = '/browser-sync/browser-sync-client.js';
    protected const BROWSER_SYNC_CLIENT_VERSION = '2.27.7';

    /**
     * @var ?bool $isBrowserSyncActive
     */
    protected static ?bool $isBrowserSyncActive = null;

    /**
     * @var array $typoScriptConstants
     */
    protected static array $typoScriptConstants = [];

    /**
     * @var array $argumentsDefault
     */
    public static array $argumentsDefault = [
        'integrity' => true,
        'crossorigin' => 'anonymous'
    ];

    /**
     * handleCss
     *
     * @param array $arguments
     * @param bool $mergeArgumentsDefault
     * @return void
     * @throws Exception
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     */
    public static function handleCss(array $arguments, bool $mergeArgumentsDefault = false): void
    {
        // merge default arguments
        if ($mergeArgumentsDefault) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $arguments,
                AssetsService::$argumentsDefault
            );
        }

        // add css (multiple vs single)
        if (isset($arguments['identifiers']) && count($arguments['identifiers']) > 0) {
            // multiple css
            foreach ($arguments['identifiers'] as $argument) {
                $args = $arguments;
                $args['identifier'] = $argument;
                unset($args['identifiers']);

                self::addCss($args);
            }
        } else if (isset($arguments['identifier']) && $arguments['identifier'] !== '') {
            // single css
            self::addCss($arguments);
        } else {
            throw new Exception('Css: Argument "identifiers" must be array OR "identifier" must be string');
        }
    }

    /**
     * handleJs
     *
     * @param array $arguments
     * @param bool $mergeArgumentsDefault
     * @return void
     * @throws Exception
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     */
    public static function handleJs(array $arguments, bool $mergeArgumentsDefault = false): void
    {
        // merge default arguments
        if ($mergeArgumentsDefault) {
            ArrayUtility::mergeRecursiveWithOverrule(
                $arguments,
                AssetsService::$argumentsDefault
            );
        }

        // add css (multiple vs single)
        if (isset($arguments['identifiers']) && count($arguments['identifiers']) > 0) {
            // multiple js
            foreach ($arguments['identifiers'] as $argument) {
                $args = $arguments;
                $args['identifier'] = $argument;
                unset($args['identifiers']);

                self::addJs($args);
            }
        } else if (isset($arguments['identifier']) && $arguments['identifier'] !== '') {
            // single js
            self::addJs($arguments);
        } else {
            throw new Exception('Css: Argument "identifiers" must be array OR "identifier" must be string');
        }
    }

    /**
     * addCss
     *
     * @param array $arguments
     * @return void
     * @throws Exception
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     */
    public static function addCss(array $arguments): void
    {
        $data = self::getData($arguments, self::TYPE_CSS);

        if ($data['url']['build'] !== '') {
            if ($arguments['inlineWithTagNoScript']) {
                $inlineStyle = file_get_contents($data['href']['absolute']);
                $style = '<noscript>' . LF . '<style>' . LF . $inlineStyle . LF . '</style>' . LF . '</noscript>';

                $GLOBALS['TSFE']->additionalHeaderData[] = $style;
            } else {
                $data['attributes'] = self::getTagAttributes($arguments, $data);

                $data['url']['cache'] = self::getUrlWithCacheParameter($arguments, $data);

                GeneralUtility::makeInstance(AssetCollector::class)->addStyleSheet($arguments['identifier'], $data['url']['cache'], $data['attributes']);
            }
        }
    }

    /**
     * addJs
     *
     * @param array $arguments
     * @return void
     * @throws Exception
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     */
    public static function addJs(array $arguments): void
    {
        $data = self::getData($arguments, self::TYPE_JS);

        if ($data['url']['build'] !== '') {
            $data['attributes'] = self::getTagAttributes($arguments, $data);

            $data['url']['cache'] = self::getUrlWithCacheParameter($arguments, $data);

            GeneralUtility::makeInstance(AssetCollector::class)->addJavaScript($arguments['identifier'], $data['url']['cache'], $data['attributes']);
        }
    }

    /**
     * getData
     *
     * @param array $arguments
     * @param string $type
     * @return array
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     * @throws Exception
     * @throws \Exception
     */
    protected static function getData(array $arguments, string $type): array
    {
        $data = [
            'href' => [
                'relative' => '',
                'absolute' => '',
            ],
            'url' => [
                'build' => '',
                'parts' => [],
            ]
        ];

        // init
        self::$typoScriptConstants['path'] = TypoScriptUtility::getConstants('path');
        self::$typoScriptConstants['browserSync'] = TypoScriptUtility::getConstants('browserSync');
        self::isBrowserSyncActive();

        // inject browser-sync-client only if browser sync is active
        if (self::$isBrowserSyncActive === false && $arguments['identifier'] === self::BROWSER_SYNC_CLIENT_IDENTIFIER) {
            return $data;
        }

        // get href absolute
        $data['href']['absolute'] = self::getHref($arguments, $type, true);

        // if asset file is not found throw error (only for Development Context)
        if (file_exists($data['href']['absolute']) === false
            && Environment::getContext()->isDevelopment() === true
            && $arguments['identifier'] !== self::BROWSER_SYNC_CLIENT_IDENTIFIER
        ) {
            throw new FileNotFoundException(sprintf('File not found (build assets!?): %s', $data['href']['absolute']));
        }

        // get site configuration
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($GLOBALS['TSFE']->id);

        // get href relative
        $data['href']['relative'] = self::getHref($arguments, $type);

        // set url parts
        // default url (if BrowserSync is inactive) => https://$HOST/$ASSET
        $data['url']['parts']['scheme'] = $site->getBase()->getScheme();
        $data['url']['parts']['host'] = $site->getBase()->getHost();
        $data['url']['parts']['path'] = $data['href']['relative'];

        // custom url (if BrowserSync is active) => https://$HOST:$PORT/$ASSET
        if (self::$isBrowserSyncActive === true) {
            $data['url']['parts']['port'] = (int)self::$typoScriptConstants['browserSync']['port'];
        }

        // build url
        $data['url']['build'] = HttpUtility::buildUrl($data['url']['parts']);

        return $data;
    }

    /**
     * isBrowserSyncActive
     *
     * @return bool
     */
    protected static function isBrowserSyncActive(): bool
    {
        if (is_null(self::$isBrowserSyncActive)) {
            if (Environment::getContext()->isDevelopment() === true && isset(self::$typoScriptConstants['browserSync']['port'])) {
                $connection = @fsockopen('localhost', (int)self::$typoScriptConstants['browserSync']['port']);

                if (is_resource($connection) === true) {
                    $isActive = true;
                    fclose($connection);
                } else {
                    $isActive = false;
                }

                self::$isBrowserSyncActive = $isActive;
            } else {
                self::$isBrowserSyncActive = false;
            }
        }

        return self::$isBrowserSyncActive;
    }

    /**
     * getHref
     *
     * @param array $arguments
     * @param string $type
     * @param bool $absolute
     * @return string
     */
    protected static function getHref(array $arguments, string $type, bool $absolute = false): string
    {
        if ($arguments['identifier'] !== self::BROWSER_SYNC_CLIENT_IDENTIFIER) {
            // /assets/css/css.identifier.min.css
            // /assets/js/js.identifier.min.js
            $hrefRelative = sprintf("%s/%s.%s.min.%s", self::$typoScriptConstants['path']['assets'][$type], $type, $arguments['identifier'], $type);

            if ($absolute) {
                return GeneralUtility::getFileAbsFileName(ltrim($hrefRelative, '/'));
            } else {
                return $hrefRelative;
            }
        } else {
            return self::BROWSER_SYNC_CLIENT_HREF;
        }
    }

    /**
     * getTagAttributes
     *
     * @param array $arguments
     * @param array $data
     * @return array
     */
    protected static function getTagAttributes(array $arguments, array &$data): array
    {
        $attributes = [
            'crossorigin' => 'anonymous',
            'integrity' => true,
        ];

        // set asset hash
        $data['href']['hash'] = '';

        if ($arguments['identifier'] !== self::BROWSER_SYNC_CLIENT_IDENTIFIER) {
            // set attribute crossorigin
            if ($arguments['crossorigin'] !== '') {
                $attributes['crossorigin'] = $arguments['crossorigin'];
            }

            // set attribute integrity
            if ($arguments['integrity'] === true) {
                if (self::$isBrowserSyncActive === false) {
                    // set asset hash
                    $data['href']['hash'] = self::getHash($data);

                    $attributes['integrity'] = 'sha512-' . $data['href']['hash'];
                } else {
                    unset($attributes['integrity']);
                }
            } else {
                unset($attributes['integrity']);
            }
        } else {
            unset($attributes['integrity']);
        }

        return $attributes;
    }

    /**
     * getHash
     *
     * @param array $data
     * @return string
     */
    protected static function getHash(array $data): string
    {
        return base64_encode(hash('sha512', file_get_contents($data['href']['absolute']), true));
    }

    /**
     * getUrlWithCacheParameter
     *
     * @param array $arguments
     * @param array $data
     * @return string
     */
    protected static function getUrlWithCacheParameter(array $arguments, array $data): string
    {
        $format = '%s?%s=%s';

        if ($arguments['identifier'] !== self::BROWSER_SYNC_CLIENT_IDENTIFIER) {
            if ($data['href']['hash'] !== '') {
                return sprintf($format, $data['url']['build'], self::URL_CACHE_PARAMETER, $data['href']['hash']);
            } else {
                return $data['url']['build'];
            }
        } else {
            return sprintf($format, $data['url']['build'], self::URL_CACHE_PARAMETER, self::BROWSER_SYNC_CLIENT_VERSION);
        }
    }
}
