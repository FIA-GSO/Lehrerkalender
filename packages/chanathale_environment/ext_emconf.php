<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'chanathale Environment',
    'description' => 'chanathale Environment',
    'version' => '11.5.0',
    'category' => 'misc',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'php' => '8.0.0-8.0.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'state' => 'stable',
    'clearCacheOnLoad' => false,
    'author' => 'Aphisit Chanathale',
    'author_email' => 'chanathaleaphisit@gmail.com',
    'author_company' => 'cahanathale GmbH',
    'autoload' => [
        'psr-4' => [
            'Chanathale\\ChanathaleEnvironment\\' => 'Classes'
        ]
    ]
);
