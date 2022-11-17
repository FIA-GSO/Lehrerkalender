<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

call_user_func(function ($extKey) {

    ExtensionUtility::registerPlugin(
        $extKey,
        'PupilsList',
        'Liste aller Schüler:innen'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'PupilForm',
        'Schüler:in-Formular'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'PupilDetail',
        'Schüler:in Detail'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'ClassroomList',
        'Liste aller Klassen'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'SubjectsList',
        'Liste aller Schulfächer'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'ClassroomForm',
        'Schulklassen - Formular'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'ClassroomDetail',
        'Klasse Detail'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'SubjectDetail',
        'Schulfach Detail'
    );

    ExtensionUtility::registerPlugin(
        $extKey,
        'Calendar',
        'Kalender'
    );

}, 'chanathale_gso');