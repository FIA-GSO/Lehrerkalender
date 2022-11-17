<?php

use Chanathale\ChanathaleCustomer\Controller\FrontendLoginController;

defined('TYPO3') || die();

call_user_func(function ($extKey) {
    $GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['chanathale_customer'] = \Chanathale\ChanathaleCustomer\Hook\DatamapHook::class;

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        $extKey,
        'ShowStatus',
        [
            FrontendLoginController::class => 'showStatus',
        ],
        // non-cacheable actions
        [
            FrontendLoginController::class => '',
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    showstatus {
                        iconIdentifier = chanathale-gso-default
                        title = Login-Status
                        description = Zeigt den aktuellen Login-Status
                        tt_content_defValues {
                            CType = list
                            list_type = chanathalecustomer_showstatus
                        }
                    }              
                }
                show = *
            }
       }'
    );
}, 'chanathale_customer');
