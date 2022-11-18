<?php

defined('TYPO3') || die('Access denied.');

// GFX
$GLOBALS['TYPO3_CONF_VARS']['GFX']['processor'] = 'ImageMagick';
$GLOBALS['TYPO3_CONF_VARS']['GFX']['processor_path'] = '/opt/homebrew/bin/';
$GLOBALS['TYPO3_CONF_VARS']['GFX']['processor_path_lzw'] = '/opt/homebrew/bin/';
$GLOBALS['TYPO3_CONF_VARS']['GFX']['processor_colorspace'] = 'sRGB';

// HTTP
$GLOBALS['TYPO3_CONF_VARS']['HTTP']['verify'] = false;

// FE
$GLOBALS['TYPO3_CONF_VARS']['FE']['versionNumberInFilename'] = 'embed';

// DISABLE CACHES
$GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] = true;
$GLOBALS['TYPO3_CONF_VARS']['FE']['debug'] = true;
$GLOBALS['TYPO3_CONF_VARS']['BE']['sessionTimeout'] = 86400; // 1 year
$GLOBALS['TYPO3_CONF_VARS']['FE']['sessionTimeout'] = 86400; // 1 year
$GLOBALS['TYPO3_CONF_VARS']['SYS']['devIPmask'] = '*';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['displayErrors'] = 1;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['belogErrorReporting'] = 30037;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['errorHandlerErrors'] = 29952;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['exceptionalErrors'] = 28672;
$GLOBALS['TYPO3_CONF_VARS']['FE']['exposeRedirectInformation'] = true;

// $GLOBALS['TYPO3_CONF_VARS']['BE']['languageDebug'] = true;

//$cacheConfigurationOverwrite = [
//    'pages' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class,
//    'pagesection' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
//];
//
//foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'] as $cacheName => $cacheConfiguration) {
//    if (isset($cacheConfigurationOverwrite[$cacheName])) {
//        $backend = $cacheConfigurationOverwrite[$cacheName];
//    }  else {
//        $backend = $cacheConfiguration['backend'];
//    }
//
//    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cacheName]['backend'] = $backend;
//}

putenv("TYPO3_CONFIG_YAML=Production.Staging");

