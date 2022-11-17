<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_environment.
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

namespace Chanathale\ChanathaleEnvironment\Utility;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Context
 */
class Context
{

    protected const STAGING_URL = 'thescape';
    protected const WINDOWS_DEVELOPMENT_URL = '.local';
    protected const DEVELOPMENT_URL = '.test';

    /**
     * initialize
     *
     * @return void
     */
    public static function initialize(): void
    {
        $context = null;
        // extract the extension configuration
        try {
            $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('chanathale_environment');
        } catch (ExtensionConfigurationExtensionNotConfiguredException | ExtensionConfigurationPathDoesNotExistException $e) {
            die('chanathale_enviroment: Could not load extension configuration.');
        }

        // choose the context, and add the additional configuration for that context as well
        // This file is usually something like typo3conf/AdditionalConfiguration.Development.php
        // the context is set via .htaccess or web server configuration like
        // SetEnv TYPO3_CONTEXT Development
        if (isset($_SERVER['TYPO3_CONTEXT'])) {
            $context = $_SERVER['TYPO3_CONTEXT'];
        } elseif (array_key_exists('REDIRECT_TYPO3_CONTEXT', $_SERVER)) {
            // for web servers with CGI
            $context = $_SERVER['REDIRECT_TYPO3_CONTEXT'];
        } elseif (getenv('TYPO3_CONTEXT')) {
            $context = getenv('TYPO3_CONTEXT');
        } elseif ($_SERVER['argc'] > 0 && array_key_exists('argc', $_SERVER)) {
            // check for command line parameter --context=Production
            // find --context=Production from the command line
            foreach ($_SERVER['argv'] as $argumentValue) {
                if (str_starts_with($argumentValue, '--context=')) {
                    $context = substr($argumentValue, 10);
                    break;
                }
            }
        }

        // check if there is a file, outside the htdocs/ directory
        // it is outside the htdocs/ directory, so it is not attached to the rest
        // of the application
        if (empty($context) && is_file(Environment::getPublicPath() . '../TYPO3_CONTEXT')) {
            $context = trim(file_get_contents(Environment::getPublicPath() . '../TYPO3_CONTEXT'));
        }

        // possibility to override the context (via extension configuration)
        if (!empty($extensionConfiguration['forceContext'])) {
            $context = $extensionConfiguration['forceContext'];
        }

        // fallback to a certain context (like Production)
        // useful for legacy projects that don't fully incorporate the Context principles yet
        if (empty($context) && !empty($extensionConfiguration['fallbackContext'])) {
            $context = $extensionConfiguration['fallbackContext'];
        }

        if (array_key_exists('SERVER_NAME', $_SERVER)) {
            $serverName = $_SERVER['SERVER_NAME'] ?? '';

            if (!empty($serverName)) {
                $context = "Production";

                if (str_contains($serverName, Context::DEVELOPMENT_URL)) {
                    $context = 'Development';
                }

                if (str_contains($serverName, Context::WINDOWS_DEVELOPMENT_URL)) {
                    $context = 'Development/Windows';
                }

                if (str_contains($serverName, Context::STAGING_URL)) {
                    $context = "Production/Staging";
                }
            }
        }

        // define a constant, and define the $_SERVER['TYPO3_CONTEXT'] for TypoScript condition
        // define('TYPO3_CONTEXT', $context);
        // $_SERVER['TYPO3_CONTEXT'] = $context;
        // putenv('TYPO3_CONTEXT=' . $context);
        if (!empty($context)) {
            // check for "Production/Live/Server1" etc
            $contextParts = explode('/', $context);

            if (isset($contextParts[0])) {
                $contextMainPart = $contextParts[0];
            }

            if (isset($contextParts[1])) {
                $contextSubPart1 = $contextParts[1];
            }

            if (isset($contextParts[2])) {
                $contextSubPart2 = $contextParts[2];
            }

            if (isset($contextMainPart) && isset($contextSubPart1) && isset($contextSubPart2)) {
                // check for a more specific configuration as well, e.g. "AdditionalConfiguration.Production.Live.Server4.php"
                $additionalContextConfiguration = Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration.' . $contextMainPart . '.' . $contextSubPart1 . '.' . $contextSubPart2 . '.php';

                if (file_exists($additionalContextConfiguration)) {
                    require_once($additionalContextConfiguration);
                }
            } else if (isset($contextMainPart) && isset($contextSubPart1)) {
                // check for a more specific configuration as well e.g. "AdditionalConfiguration.Development.Profiling.php"
                $additionalContextConfiguration = Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration.' . $contextMainPart . '.' . $contextSubPart1 . '.php';

                if (file_exists($additionalContextConfiguration)) {
                    require_once($additionalContextConfiguration);
                }
            } else if (isset($contextMainPart)) {
                // include the most general file e.g. "AdditionalConfiguration.Staging.php"
                $additionalContextConfiguration = Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration.' . $contextMainPart . '.php';

                if (file_exists($additionalContextConfiguration)) {
                    require_once($additionalContextConfiguration);
                }
            }

            // add the context information to the site name
            $publicPath = getenv('TYPO3_PATH_ROOT');

            // get Git Tag from release folder for TYPO3 Sitename
            if ($context === 'Production') {
                $regex = '~/(v\d+\.\d+\.\d+)/~i';
                preg_match_all($regex, $publicPath, $matches, PREG_SET_ORDER);

                if (count($matches) === 1 && count($matches[0]) === 2) {
                    $tag = $matches[0][1];
                }
            } else if (str_contains($context, 'Staging')) {
                $regex = '/releases\/(\d+)\/public/';
                preg_match_all($regex, $publicPath, $matches, PREG_SET_ORDER);

                if (count($matches) === 1 && count($matches[0]) === 2) {
                    $tag = $matches[0][1];
                }
            } else {
                $tag = '';
            }

            // set TYPO3 Sitename (Context + Version)
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] .= ' - ' . $context . (!empty($tag) ? ' (' . $tag . ')' : '');
        } else {
            // no context variable is found Then quit, otherwise the
            // request is called with no specific configuration and might have ugly side effects
            die('chanathale_environment: No environment variable TYPO3_CONTEXT found.');
        }
    }
}