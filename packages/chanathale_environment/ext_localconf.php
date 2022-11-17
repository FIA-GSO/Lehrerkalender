<?php

defined('TYPO3') || die('Access denied.');

call_user_func(function ($extKey) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Mail\Mailer::class] = [
        'className' => \Chanathale\ChanathaleEnvironment\XClass\Mailer::class
    ];
    // add TSConfig Page
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:' . $extKey . '/Configuration/TsConfig/Page" extensions="tsconfig">'
    );
    // add TSConfig User
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="DIR:EXT:' . $extKey . '/Configuration/TsConfig/User" extensions="tsconfig">'
    );

    $GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets'][$extKey] = 'EXT:' . $extKey. '/Configuration/RTE/Config.yaml';

}, 'chanathale_environment');

