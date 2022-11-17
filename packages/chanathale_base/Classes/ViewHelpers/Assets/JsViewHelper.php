<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\ViewHelpers\Assets;

use Closure;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use Chanathale\ChanathaleBase\Service\AssetsService;

/**
 * JsViewHelper
 */
class JsViewHelper extends AbstractAssetsViewHelper
{

    /**
     * initializeArguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
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
        AssetsService::handleJs($arguments);
    }
}
