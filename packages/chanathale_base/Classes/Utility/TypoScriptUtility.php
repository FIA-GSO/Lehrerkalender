<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Utility;

use Exception;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

/**
 * SettingsUtility
 */
class TypoScriptUtility
{
    /**
     * $settings
     *
     * @var array
     */
    protected static array $settings = [];

    /**
     * $constants
     *
     * @var array
     */
    protected static array $constants = [];

    /**
     * getSetupFull
     *
     * @return array
     */
    public static function getSetupFull(): array
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            return $configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
        } catch (InvalidConfigurationTypeException) {
            // nothing
        }

        return [];
    }

    /**
     * getSetupPlugin
     *
     * @param string $pluginKey
     * @return array
     * @throws Exception
     */
    public static function getSetupPlugin(string $pluginKey): array
    {
        if (!isset(self::$settings[$pluginKey])) {
            $settings = self::getSetupFull();
            $settings = GeneralUtility::removeDotsFromTS($settings);

            if (false === array_key_exists($pluginKey, $settings['plugin'])) {
                return [];
            }

            self::$settings[$pluginKey] = $settings['plugin'][$pluginKey];
        }

        return self::$settings[$pluginKey];
    }

    /**
     * getConstants
     *
     * @param string $key
     * @return array
     * @throws Exception
     */
    public static function getConstants(string $key): array
    {
        if (!isset(self::$constants[$key])) {
            $constants = [];
            $constantsString = '';
            $prefix = $key . '.';

            if ($GLOBALS['TSFE']->tmpl->flatSetup === null
                || !is_array($GLOBALS['TSFE']->tmpl->flatSetup)
                || count($GLOBALS['TSFE']->tmpl->flatSetup) === 0) {
                $GLOBALS['TSFE']->tmpl->generateConfig();
            }

            foreach ($GLOBALS['TSFE']->tmpl->flatSetup as $constant => $value) {
                if (str_starts_with($constant, $prefix)) {
                    $constantsString .= substr($constant, strlen($prefix)) . ' = ' . $value . PHP_EOL;
                }
            }

            if ($constantsString !== '') {
                $typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
                $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

                $typoScriptParser->parse($constantsString);
                $typoScriptArray = $typoScriptParser->setup;
                $constants = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptArray);
            }

            self::$constants[$key] = $constants;
        }

        return self::$constants[$key];
    }
}
