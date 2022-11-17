<?php
return [
    'BE' => [
        'debug' => true,
        'explicitADmode' => 'explicitAllow',
        'installToolPassword' => '$argon2i$v=19$m=65536,t=16,p=1$dmVka1VmODl4WDhNU2tZSQ$M3AnZy+HNYtZuEC8IlSSZin0sYEHoZn4tLZ1v5imSUg',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8mb4',
                'dbname' => 'typo3_mai',
                'driver' => 'mysqli',
                'host' => '127.0.0.1',
                'password' => 'websites',
                'port' => 3306,
                'tableoptions' => [
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                ],
                'user' => 'websites',
            ],
        ],
    ],
    'EXTCONF' => [
        'lang' => [
            'availableLanguages' => [
                'de',
            ],
        ],
    ],
    'EXTENSIONS' => [
        'backend' => [
            'backendFavicon' => '',
            'backendLogo' => '',
            'loginBackgroundImage' => 'EXT:chanathale_customer/Resources/Public/school.jpeg',
            'loginFootnote' => '',
            'loginHighlightColor' => '#5b9bd5',
            'loginLogo' => 'EXT:chanathale_customer/Resources/Public/gso.png',
            'loginLogoAlt' => '',
        ],
        'chanathale_environment' => [
            'fallbackContext' => '',
            'forceContext' => '',
        ],
        'extension_builder' => [
            'backupDir' => 'var/tx_extensionbuilder/backups',
            'backupExtension' => '1',
            'enableRoundtrip' => '1',
        ],
        'extensionmanager' => [
            'automaticInstallation' => '1',
            'offlineMode' => '0',
        ],
        'mask' => [
            'backend' => 'EXT:chanathale_customer/Resources/Private/Templates/Ext/Mask/Backend',
            'backend_layouts_folder' => 'EXT:chanathale_customer/Configuration/Mask/BackendLayouts',
            'backendlayout_pids' => '0',
            'content' => 'EXT:chanathale_customer/Resources/Private/Templates/Ext/Mask/Frontend',
            'content_elements_folder' => 'EXT:chanathale_customer/Configuration/Mask/ContentElements',
            'json' => '',
            'layouts' => 'EXT:chanathale_customer/Resources/Private/Layouts/',
            'layouts_backend' => 'EXT:chanathale_customer/Resources/Private/Layouts/Ext/Mask/Backend',
            'loader_identifier' => 'json-split',
            'partials' => 'EXT:chanathale_customer/Resources/Private/Partials/',
            'partials_backend' => 'EXT:chanathale_customer/Resources/Private/Partials/Ext/Mask/Backend',
            'preview' => 'EXT:chanathale_customer/Resources/Public/Mask/',
        ],
        'mindshape_seo' => [
            'enableGoneRedirects' => '0',
        ],
        'scheduler' => [
            'maxLifetime' => '1440',
            'showSampleTasks' => '1',
        ],
    ],
    'FE' => [
        'debug' => true,
        'disableNoCacheParameter' => true,
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'LOG' => [
        'TYPO3' => [
            'CMS' => [
                'deprecations' => [
                    'writerConfiguration' => [
                        'notice' => [
                            'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                                'disabled' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'MAIL' => [
        'transport' => 'sendmail',
        'transport_sendmail_command' => '/usr/sbin/sendmail -S mail:1025',
        'transport_smtp_encrypt' => '',
        'transport_smtp_password' => '',
        'transport_smtp_server' => '',
        'transport_smtp_username' => '',
    ],
    'SYS' => [
        'caching' => [
            'cacheConfigurations' => [
                'hash' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                ],
                'imagesizes' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pages' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pagesection' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'rootline' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
            ],
        ],
        'devIPmask' => '*',
        'displayErrors' => 1,
        'encryptionKey' => '069e9dd0d716c38bd725328b072d205e1b3bb643a65391eee978511e3349bdaac02df42e6393c25e306c74b0be725451',
        'exceptionalErrors' => 12290,
        'features' => [
            'unifiedPageTranslationHandling' => true,
            'yamlImportsFollowDeclarationOrder' => true,
        ],
        'sitename' => 'Georg Simon Ohm - Lehrerkalender',
        'systemMaintainers' => [
            1,
        ],
    ],
];
