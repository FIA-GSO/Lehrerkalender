<?php

defined('TYPO3') || die('Access denied.');

// DB
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'typo3_teachercalendar'; # §§§DEVELOPMENT_DB_NAME§§§
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'localhost'; # §§§DEVELOPMENT_DB_HOSTNAME_OR_IP§§§
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'websites'; # §§§DEVELOPMENT_DB_PASSWORD§§§
$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'websites'; # §§§DEVELOPMENT_DB_USERNAME§§§

// MAIL
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromAddress'] = 'chanathaleaphisit@gmail.com';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailReplyToAddress'] = 'chanathaleaphisit@gmail.com';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailFromName'] = 'Aphisit Chanathale';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['defaultMailReplyToName'] = 'Aphisit Chanathale';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport'] = 'smtp';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_server'] = 'localhost:1025';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_encrypt'] = '';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_password'] = '';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_username'] = '';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_smtp_password'] = '';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['transport_sendmail_command'] = '';

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

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['chanathale_environment']['redirectEmails'] = 'chanathaleaphisit@gmail.com';
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

putenv("TYPO3_CONFIG_YAML=Development");

