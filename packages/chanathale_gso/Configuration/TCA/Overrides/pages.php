<?php
declare(strict_types=1);

defined('TYPO3_MODE') || die();

call_user_func(
    function () {

        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'pages',
            'doktype',
            [
                'Schüler-Detail',
                110,
                'content-menu-categorized'
            ],
            '1',
            'after'
        );

        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
            'pages',
            'doktype',
            [
                'Klasse-Detail',
                111,
                'content-menu-categorized'
            ],
            '1',
            'after'
        );

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA']['pages'],
            [
                // add icon for new page type:
                'ctrl' => [
                    'editlock' => 'editlock',
                    'typeicon_classes' => [
                        110 => 'mimetypes-x-content-login',
                    ],
                ],
                // add all page standard fields and tabs to your new page type
                'types' => [
                    '110' => [
                        'showitem' => $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT]['showitem']
                    ]
                ]
            ]
        );

        \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TCA']['pages'],
            [
                // add icon for new page type:
                'ctrl' => [
                    'editlock' => 'editlock',
                    'typeicon_classes' => [
                        111 => 'mimetypes-x-content-login',
                    ],
                ],
                // add all page standard fields and tabs to your new page type
                'types' => [
                    '111' => [
                        'showitem' => $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_DEFAULT]['showitem']
                    ]
                ]
            ]
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
            'pages',
            [
                'editlock' => [
                    'exclude' => true,
                    'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:editlock',
                    'config' => [
                        'type' => 'check',
                        'renderType' => 'checkboxToggle',
                        'items' => [
                            [
                                0 => '',
                                1 => '',
                            ]
                        ],
                    ]
                ],
            ]
        );

        $GLOBALS['TCA']['pages']['palettes']['chanathale_gso_palette_pupildetails'] = array(
            'showitem' => '
             editlock, --linebreak--,
        ',
        );

        $GLOBALS['TCA']['pages']['palettes']['chanathale_gso_career_palette_classroomdetails'] = array(
            'showitem' => '
             editlock, --linebreak--,
        ',
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'pages',
            '--palette--;;chanathale_gso_palette_pupildetails',
            110,
            'after:nav_hide'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'pages',
            '--palette--;;chanathale_gso_career_palette_classroomdetails',
            111,
            'after:nav_hide'
        );

    }
);