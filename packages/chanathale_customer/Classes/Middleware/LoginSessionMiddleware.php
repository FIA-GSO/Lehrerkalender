<?php
declare(strict_types=1);

/***
 *
 * This file is part of the "chanathale Customer" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Aphisit Chanathale <chanathaleaphisit@gmail.com>, chanathale GmbH
 *
 ***/

namespace Chanathale\ChanathaleCustomer\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

/**
 * LoginSessionMiddleware
 */
class LoginSessionMiddleware implements \Psr\Http\Server\MiddlewareInterface {

    /**
     * @var UriBuilder|null
     */
    protected ?UriBuilder $uriBuilder = null;

    /**
     * @var UserAspect|null
     */
    protected ?UserAspect $userAspect = null;

    /**
     * injectUriBuilder
     * @param UriBuilder $uriBuilder
     * @return void
     */
    public function injectUriBuilder(UriBuilder $uriBuilder) : void {
        $this->uriBuilder = $uriBuilder;
    }

    /**
     * construct
     */
    public function __construct() {
        $this->userAspect = GeneralUtility::makeInstance(Context::class)->getAspect('frontend.user');
    }

    /**
     * process
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface {
        $pageUid = $GLOBALS['TSFE']->id;
        $page = GeneralUtility::makeInstance(PageRepository::class)->getPage($pageUid);
        $loginMaskPageUid = 31;
        $ajaxPageUid = 7;

        if ($pageUid !== $loginMaskPageUid && $pageUid !== $ajaxPageUid) {
            if (array_key_exists('fe_user_protected', $page)) {
                $protectionEnabled = $page['fe_user_protected'] ?? 0;
                $protectionEnabled = (int) $protectionEnabled;

                if ($protectionEnabled === 1) {

                    if ($this->userAspect->isLoggedIn()) {
                        return $handler->handle($request);
                    } else {
                        return $this->redirectToLoginMask($loginMaskPageUid, $pageUid);
                    }
                }
            }
        }

        if ($pageUid === $loginMaskPageUid && $this->userAspect->isLoggedIn() === true) {
            return $this->redirectToLoginMask(1, $pageUid);
        }

        return $handler->handle($request);
    }

    /**
     * redirectToLoginMask
     * @param int $loginMaksPageUid
     * @param int $refererPageUid
     * @return RedirectResponse
     */
    private function redirectToLoginMask(int $loginMaksPageUid, int $refererPageUid) : RedirectResponse {
        $uri = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($loginMaksPageUid)
            ->setCreateAbsoluteUri(true)->buildFrontendUri();

        return new RedirectResponse($uri, 401);
    }
}