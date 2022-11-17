<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\DataProcessing;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class SiteLanguageProcessor implements DataProcessorInterface
{
    /**
     * constants
     */
    protected const AS = 'siteLanguage';

    /**
     * @var TypoScriptParser|null
     */
    protected ?TypoScriptParser $typoscriptParser = null;

    /**
     * @var TypoScriptService|null
     */
    protected ?TypoScriptService $typoscriptService = null;

    /**
     * ConstantsProcessor constructor.
     */
    public function __construct()
    {
        $this->typoscriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
        $this->typoscriptService = GeneralUtility::makeInstance(TypoScriptService::class);
    }

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, self::AS);

        // Set the target variable
        $processedData[$targetVariableName] = self::getCurrentSiteLanguage();

        return $processedData;
    }

    /**
     * Returns the currently configured "site language" if a site is configured (= resolved)
     * in the current request.
     *
     * @return SiteLanguage|null
     */
    protected static function getCurrentSiteLanguage(): ?SiteLanguage
    {
        if ($GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface) {
            return $GLOBALS['TYPO3_REQUEST']->getAttribute('language', null);
        }
        return null;
    }
}
