<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Context\LanguageAspectFactory;
use TYPO3\CMS\Core\Context\TypoScriptAspect;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Error\Http\InternalServerErrorException;
use TYPO3\CMS\Core\Error\Http\ServiceUnavailableException;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

/**
 * TSFEUtility
 */
class TSFEUtility
{
    /**
     * @var array $cacheTSFE
     */
    public static array $cacheTSFE = [];

    /**
     * initFE
     *
     * https://github.com/TYPO3-Solr/ext-solr/blob/master/Classes/FrontendEnvironment/Tsfe.php
     *
     * @param int $pageUid
     * @param int $languageUid
     * @return void
     * @throws AspectNotFoundException
     * @throws InternalServerErrorException
     * @throws ServiceUnavailableException
     * @throws SiteNotFoundException|\TYPO3\CMS\Core\Authentication\Mfa\MfaRequiredException
     */
    public static function initFE(int $pageUid, int $languageUid): void
    {
        // reset
        unset($GLOBALS['TSFE']);

        $cacheId = $pageUid . '|' . $languageUid;

        /** @var Context $context */
        $context = GeneralUtility::makeInstance(Context::class);
        self::changeLanguageContext($pageUid, $languageUid);

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageUid);
        $siteLanguage = $site->getLanguageById($languageUid);

        $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST'] ?? GeneralUtility::makeInstance(ServerRequest::class)
                ->withAttribute('site', $site)
                ->withAttribute('language', $siteLanguage)
                ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
                ->withUri($site->getBase());

        if (!isset(self::$cacheTSFE[$cacheId])) {
            /** @var FrontendUserAuthentication $feUser */
            $feUser = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
            // init feuser
            $feUser->start($GLOBALS['TYPO3_REQUEST']);

            $pageArguments = GeneralUtility::makeInstance(PageArguments::class, $pageUid, '0', []);

            /** @var TypoScriptFrontendController $GLOBALS ['TSFE'] */
            $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
                TypoScriptFrontendController::class,
                $context,
                $site,
                $siteLanguage,
                $pageArguments,
                $feUser
            );

            $GLOBALS['TSFE']->id = $pageUid;
            $GLOBALS['TSFE']->type = 0;

            // for certain situations we need to trick TSFE into granting us
            // access to the page in any case to make getPageAndRootline() work
            // see http://forge.typo3.org/issues/42122
            $pageRecord = BackendUtility::getRecord('pages', $pageUid, 'fe_group');
            $userGroups = [0, -1];
            if (!empty($pageRecord['fe_group'])) {
                $userGroups = array_unique(array_merge($userGroups, explode(',', $pageRecord['fe_group'])));
            }
            $context->setAspect('frontend.user', GeneralUtility::makeInstance(UserAspect::class, $feUser, $userGroups));

            $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class);
            $GLOBALS['TSFE']->determineId();
            $GLOBALS['TSFE']->tmpl = GeneralUtility::makeInstance(TemplateService::class, $context);
            $context->setAspect('typoscript', GeneralUtility::makeInstance(TypoScriptAspect::class, true));
            $GLOBALS['TSFE']->no_cache = true;
            $GLOBALS['TSFE']->tmpl->start($GLOBALS['TSFE']->rootLine);
            $GLOBALS['TSFE']->no_cache = false;
            $GLOBALS['TSFE']->getConfigArray();
            $GLOBALS['TSFE']->newCObj();
            $GLOBALS['TSFE']->absRefPrefix = self::getAbsRefPrefixFromTSFE($GLOBALS['TSFE']);
            $GLOBALS['TSFE']->calculateLinkVars([]);

            self::$cacheTSFE[$cacheId] = $GLOBALS['TSFE'];
        }

        $GLOBALS['TSFE'] = self::$cacheTSFE[$cacheId];
        Locales::setSystemLocaleFromSiteLanguage($siteLanguage);
        self::changeLanguageContext($pageUid, $languageUid);
    }

    /**
     * changeLanguageContext
     *
     * @param int $pageUid
     * @param int $languageUid
     * @return void
     * @throws AspectNotFoundException
     */
    public static function changeLanguageContext(int $pageUid, int $languageUid): void
    {
        /* @var Context $context */
        $context = GeneralUtility::makeInstance(Context::class);
        if ($context->hasAspect('language')) {
            $hasRightLanguageId = $context->getPropertyFromAspect('language', 'id') === $languageUid;
            $hasRightContentLanguageId = $context->getPropertyFromAspect('language', 'contentId') === $languageUid;

            if ($hasRightLanguageId && $hasRightContentLanguageId) {
                return;
            }
        }

        /* @var $siteFinder SiteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        try {
            $site = $siteFinder->getSiteByPageId($pageUid);
            $languageAspect = LanguageAspectFactory::createFromSiteLanguage($site->getLanguageById($languageUid));
            $context->setAspect('language', $languageAspect);
        } catch (SiteNotFoundException) {

        }
    }

    /**
     * getAbsRefPrefixFromTSFE
     *
     * Resolves the configured absRefPrefix to a valid value and resolved if absRefPrefix
     * is set to "auto".
     *
     * @param TypoScriptFrontendController $TSFE
     * @return string
     */
    public static function getAbsRefPrefixFromTSFE(TypoScriptFrontendController $TSFE): string
    {
        $absRefPrefix = '';
        if (empty($TSFE->config['config']['absRefPrefix'])) {
            return $absRefPrefix;
        }

        $absRefPrefix = trim($TSFE->config['config']['absRefPrefix']);
        if ($absRefPrefix === 'auto') {
            $absRefPrefix = GeneralUtility::getIndpEnv('TYPO3_SITE_PATH');
        }

        return $absRefPrefix;
    }
}
