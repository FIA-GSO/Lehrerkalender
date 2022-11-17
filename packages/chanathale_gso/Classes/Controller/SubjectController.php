<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_gso.
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

use Chanathale\ChanathaleGso\Domain\Model\Subject;
use Chanathale\ChanathaleGso\Service\ClassroomService;
use Chanathale\ChanathaleGso\Service\SubjectService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * SubjectController
 */
class SubjectController extends ActionController {

    /**
     * @var SubjectService|null
     */
    private ?SubjectService $subjectService = null;

    /**
     * @var ClassroomService|null
     */
    private ?ClassroomService $classroomService = null;

    /**
     * construct
     */
    public function __construct () {
        /** @var SubjectService subjectService */
        $this->subjectService = GeneralUtility::makeInstance(SubjectService::class);
        /** @var ClassroomService classroomService */
        $this->classroomService = GeneralUtility::makeInstance(ClassroomService::class);
    }

    /**
     * listAction
     * @return ResponseInterface
     */
    public function listAction () : ResponseInterface {
        $subjects = $this->subjectService->findAllSubjectByFrontendUser();

        $this->view->assign('subjects', $subjects);

        return $this->htmlResponse();
    }

    /**
     * @param Subject $subject
     * @return ResponseInterface
     */
    public function showAction (Subject $subject) : ResponseInterface {
        $classrooms = $this->classroomService->findAllByFrontendUserAndClassroomUids($subject->getClassrooms());
        $this->view->assign('subject', $subject);
        $this->view->assign('classrooms', $classrooms);

        return $this->htmlResponse();
    }
}