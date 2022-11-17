<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\ViewHelpers\Assets;

use Closure;
use Chanathale\ChanathaleBase\Service\AssetsService;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Localization\Exception\FileNotFoundException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * CssViewHelper
 */
class CssViewHelper extends AbstractAssetsViewHelper
{

    /**
     * initializeArguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();

        $this->registerArgument('inlineWithTagNoScript', 'boolean', 'add style inline and wrap in <noscript>', false, false);
    }

    /**
     * renderStatic
     *
     * @param array $arguments
     * @param Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return void
     * @throws Exception
     * @throws FileNotFoundException
     * @throws SiteNotFoundException
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): void
    {
        AssetsService::handleCss($arguments);
    }
}
