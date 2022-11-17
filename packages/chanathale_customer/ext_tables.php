<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die();

(static function () {

    ExtensionUtility::registerPlugin(
        'chanathale_customer',
        'ShowStatus',
        'Login - Status'
    );
})();
