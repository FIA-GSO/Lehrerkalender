<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_customer.
 *
 * (c) 2022 Aphisit Chanathale <chanathaleaphisit@gmail.com>, chanathale GmbH
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Chanathale\ChanathaleGso\Controller;

use Chanathale\ChanathaleGso\Domain\Model\Classroom;
use Chanathale\ChanathaleGso\Service\ClassroomService;
use Chanathale\ChanathaleGso\Service\PageTitleService;
use Chanathale\ChanathaleGso\Service\ValidationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * ClassroomController
 */
class ClassroomController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var ClassroomService|null
     */
    protected ?ClassroomService $classroomService = null;

    public function __construct () {
        /** @var ClassroomService classroomService */
        $this->classroomService = GeneralUtility::makeInstance(ClassroomService::class);
    }

    /**
     * listAction
     * @return ResponseInterface
     */
    public function listAction () : ResponseInterface {
        $this->view->assign('classrooms', $this->classroomService->findAllClassroomsByFrontendUser());
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        return $this->htmlResponse();
    }

    /**
     * showAction
     * @param Classroom $classroom
     * @return ResponseInterface
     */
    public function showAction (Classroom $classroom) : ResponseInterface {
        /** @var PageTitleService $pageTitleService */
        $pageTitleService = GeneralUtility::makeInstance(PageTitleService::class);
        $pageTitleService->setTitle($classroom->getTitle());

        $this->view->assign('classroom', $classroom);
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        return $this->htmlResponse();
    }

    /**
     * formAction
     * @return ResponseInterface
     */
    public function formAction () : ResponseInterface {
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);
        $this->view->assign('classroom', new Classroom());

        return $this->htmlResponse();
    }

    /**
     * saveAction
     * @param Classroom $classroom
     * @return ResponseInterface
     */
    public function saveAction (Classroom $classroom) : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'html' => ''
        ];
        $validationResults = ValidationService::validateEntity($this->settings, 'saveClassroom', $classroom);
        $response['validationResult'] = $validationResults;
        $savedClassroom = false;

        if ($validationResults['valid']) {
            $savedClassroom = $this->classroomService->saveClassroom($classroom);
        }

        if ($savedClassroom) {
            $response['statusCode'] = 200;

            $this->view->assign('classrooms', $this->classroomService->findAllClassroomsByFrontendUser());
            $this->view->assign('settings', $this->settings);

            $response['html'] = $this->view->render();
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}