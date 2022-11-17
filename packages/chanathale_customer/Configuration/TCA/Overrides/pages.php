<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'pages',
    [
        'fe_user_protected' => [
            'exclude' => true,
            'label' => 'Passwortschutz einschalten',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'labelChecked' => 'Enabled',
                        'labelUnchecked' => 'Disabled',
                        'invertStateDisplay' => false,
                    ],
                ],
            ],
        ]
    ]
);

$GLOBALS['TCA']['pages']['palettes']['fe_user_palette'] = array(
    'showitem' => '
            fe_user_protected, --linebreak--
            ',
);

ExtensionManagementUtility::addToAllTCAtypes(
    'pages',
    '--palette--;;fe_user_palette',
    1,
    'after:subtitle'
);