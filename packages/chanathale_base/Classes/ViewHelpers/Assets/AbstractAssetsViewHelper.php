<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\ViewHelpers\Assets;

use Chanathale\ChanathaleBase\Service\AssetsService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * AbstractAssetsViewHelper
 */
class AbstractAssetsViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * initializeArguments
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('identifier', 'string', 'css and js identifier', false);
        $this->registerArgument('identifiers', 'array', 'multiple css and js identifiers', false);
        $this->registerArgument('crossorigin', 'string', 'crossorigin', false, AssetsService::$argumentsDefault['crossorigin']);
        $this->registerArgument('integrity', 'boolean', 'integrity', false, AssetsService::$argumentsDefault['integrity']);
    }
}
