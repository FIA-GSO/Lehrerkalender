<?php
defined('TYPO3') || die('Access denied.');

call_user_func(function ($extKey) {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        $extKey,
        'setup',
        "@import 'EXT:chanathale_gso/Configuration/TypoScript/setup.typoscript'"
    );

    // add pupil pagetype
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . 110 . ')'
    );

    // add classroom pagetype
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
        'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . 111 . ')'
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'PupilsList',
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'list',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => '',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'SubjectsList',
        [
            \Chanathale\ChanathaleGso\Controller\SubjectController::class => 'list',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\SubjectController::class => '',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'PupilForm',
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'form',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => '',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'PupilDetail',
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'show',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'show',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'ClassroomList',
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'list',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'list',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'ClassroomForm',
        [
           \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'form',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => '',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxSaveForm',
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'save',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'save',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxDeletePupil',
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'delete',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PupilController::class => 'delete',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxSaveClassroomForm',
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'save',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'save',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxSaveGradeForm',
        [
            \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'save',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'save',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            $extKey,
            'AjaxFilterPerformance',
            [
                \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'filter',
            ],
            // non-cacheable actions
            [
                \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'filter',
            ]
        );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'ClassroomDetail',
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => 'show',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\ClassroomController::class => '',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'SubjectDetail',
        [
            \Chanathale\ChanathaleGso\Controller\SubjectController::class => 'show',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\SubjectController::class => 'show',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'Calendar',
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'show',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'show',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxSaveEvent',
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'saveEvent',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'saveEvent',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxSaveMeeting',
        [
            \Chanathale\ChanathaleGso\Controller\MeetingController::class => 'save',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\MeetingController::class => 'save',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxDeleteMeeting',
        [
            \Chanathale\ChanathaleGso\Controller\MeetingController::class => 'delete',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\MeetingController::class => 'delete',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxDeleteEvent',
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'deleteEvent',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\CalendarController::class => 'deleteEvent',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'AjaxDeletePerformance',
        [
            \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'delete',
        ],
        // non-cacheable actions
        [
            \Chanathale\ChanathaleGso\Controller\PerformanceController::class => 'delete',
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    pupilslist {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilslist.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilslist.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_pupilslist
                        }
                    }
                    pupilform {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_pupilform
                        }
                    }
                    pupildetail {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_pupildetail
                        }
                    }
                    classroomlist {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_classroomlist
                        }
                    }
                    classroomform {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_classroomform
                        }
                    }
                    classroomdetail {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_classroomdetail
                        }
                    }
                    subjectslist {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_subjectslist
                        }
                    }
                    subjectdetail {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_subjectdetail
                        }
                    }
                    calendar {
                        iconIdentifier = chanathale-gso-default
                        title = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.name
                        description = LLL:EXT:chanathale_gso/Resources/Private/Language/locallang.xlf:plugin.chanathalegso_pupilform.description
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalegso_calendar
                        }
                    }
                }
                show = *
            }
       }'
    );
}, 'chanathale_gso');