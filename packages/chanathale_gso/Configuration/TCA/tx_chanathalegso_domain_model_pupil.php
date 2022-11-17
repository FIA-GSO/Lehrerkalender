<?php
declare(strict_types=1);
return [
    'ctrl' => [
        'title' => 'Schüler:in',
        'label' => 'lastname',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'lastname',
        'iconfile' => 'EXT:chanathale_customer/Resources/Public/Icons/Extension.png',
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_chanathalegso_domain_model_pupil',
                'foreign_table_where' => 'AND {#tx_chanathalegso_domain_model_pupil}.{#pid}=###CURRENT_PID### AND {#tx_chanathalegso_domain_model_pupil}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'deleted' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.deleted',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'firstname' => [
            'exclude' => true,
            'label' => 'Vorname',
            'config' => [
                'type' => 'input',
                'size' => '255',
                'eval' => 'trim',
            ],
        ],
        'lastname' => [
            'exclude' => true,
            'label' => 'Nachname',
            'config' => [
                'type' => 'input',
                'size' => '255',
                'eval' => 'trim',
            ],
        ],
        'pupil_number' => [
            'exclude' => true,
            'label' => 'Schülernummer',
            'config' => [
                'type' => 'input',
                'size' => '255',
                'eval' => 'trim',
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'E-Mail',
            'config' => [
                'type' => 'input',
                'size' => '255',
                'eval' => 'trim',
            ],
        ],
        'classroom' => [
            'exclude' => true,
            'label' => 'Klasse',
            'config' => [
                'readOnly' => true,
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_chanathalegso_domain_model_classroom'
            ],
        ],
        'users' => [
            'exclude' => true,
            'label' => 'Benutzer:in',
            'description' => 'eine Liste von Lehrer und Lehrerinnen, die diese:n Schüler:in in ihren Account gepflegt haben.',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'fe_users'
            ],
        ],
        'url_slug' => [
            'label' => 'URL',
            'exclude' => true,
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['pupil_number'],
                    'fieldSeparator' => '/',
                    'prefixParentPageSlug' => true,
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInSite',
                'default' => ''
            ],
        ],
        'grades' => [
            'exclude' => true,
            'label' => 'Leistungen',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_chanathalegso_domain_model_performance',
                'foreign_field' => 'pupil',
                'appearance' => [
                    'collapseAll' => 1,
                    'expandSingle' => 1,
                ],
            ],
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden',
    ],
    'types' => [
        '0' => ['showitem' => 'hidden, classroom, firstname, lastname, pupil_number, url_slug,  email, users, grades, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
];