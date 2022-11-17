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

use Chanathale\ChanathaleGso\Domain\Model\Grade;
use Chanathale\ChanathaleGso\Domain\Model\Performance;
use Chanathale\ChanathaleGso\Domain\Model\PerformanceSearch;
use Chanathale\ChanathaleGso\Domain\Model\Pupil;
use Chanathale\ChanathaleGso\Service\ClassroomService;
use Chanathale\ChanathaleGso\Service\GradeTypeService;
use Chanathale\ChanathaleGso\Service\PupilService;
use Chanathale\ChanathaleGso\Service\SubjectService;
use Chanathale\ChanathaleGso\Service\ValidationService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

/**
 * PupilController
 */
class PupilController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var PupilService|null
     */
    private ?PupilService $pupilService = null;

    /**
     * @var ClassroomService|null $classroomService
     */
    private ?ClassroomService $classroomService = null;

    /**
     * @var SubjectService|null
     */
    private ?SubjectService $subjectService = null;

    /**
     * PupilController constructor.
     */
    public function __construct () {
        /** @var PupilService pupilService */
        $this->pupilService = GeneralUtility::makeInstance(PupilService::class);

        /** @var ClassroomService classroomService */
        $this->classroomService = GeneralUtility::makeInstance(ClassroomService::class);

        /** @var SubjectService subjectService */
        $this->subjectService = GeneralUtility::makeInstance(SubjectService::class);
    }

    /**
     * listAction
     * @return ResponseInterface
     */
    public function listAction () : ResponseInterface {
        $this->view->assign('classrooms', ClassroomService::findAllClassrooms());
        $this->view->assign('pupils', $this->pupilService->findAllPupilsByFrontendUser());
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        return $this->htmlResponse();
    }

    /**
     * formAction
     * @return ResponseInterface
     */
    public function formAction () : ResponseInterface {
        $pupil = new Pupil();
        $this->view->assign('classrooms', $this->classroomService->findAllClassroomsByFrontendUser());
        $this->view->assign('pupil', $pupil);
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        return $this->htmlResponse();
    }

    /**
     * showAction
     * @param Pupil $pupil
     * @return ResponseInterface
     */
    public function showAction (Pupil $pupil) : ResponseInterface {
        $performance = new Performance();
        $commentPerformance = new Performance();
        $commentPerformance->setGradeType(GradeTypeService::getCommentGradeType());
        $commentPerformance->setPupil($pupil);
        $commentPerformance->setSubject(null);
        $performanceSearch = new PerformanceSearch();
        $performanceSearch->setPupil($pupil);
        $performanceSearch->setGradeType(null);
        $performanceSearch->setSubject(null);
        $performance->setPupil($pupil);

        $this->view->assign('pupil', $pupil);
        $this->view->assign('gradeTypes', GradeTypeService::findAllGradeTypes());
        $this->view->assign('allGradeTypes', GradeTypeService::findAllGradeTypes());
        $this->view->assign('allSubjects', $this->subjectService->findAllSubjectByFrontendUser());
        $this->view->assign('performance', $performance);
        $this->view->assign('commentPerformance', $commentPerformance);
        $this->view->assign('grades', $pupil->getGrades());
        $this->view->assign('hasPerformances', GradeTypeService::hasPerformances(GradeTypeService::findAllGradeTypes(), $pupil->getGrades()));
        $this->view->assign('performanceSearch', $performanceSearch);
        $this->view->assign('subjects', $this->subjectService->findAllSubjectByFrontendUser());
        $this->view->assign('data', $this->configurationManager->getContentObject()->data);

        return $this->htmlResponse();
    }

    /**
     * saveAction
     * @param Pupil $pupil
     * @return ResponseInterface
     */
    public function saveAction (Pupil $pupil) : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'html' => '',
            'pupil' => ($pupil->_getProperties()),
            'classroom' => (empty($pupil->getClassroom()) ? '' : $pupil->getClassroom()->getTitle())
        ];
        $validationResults = ValidationService::validateEntity($this->settings, 'savePupil', $pupil);
        $response['validationResult'] = $validationResults;
        $savedPupil = false;

        if ($validationResults['valid']) {
            $savedPupil = $this->pupilService->savePupil($pupil);
            $addedToClassroomList = false;

            if ($savedPupil) {
                $addedToClassroomList = $this->classroomService->addPupilToClassroom($pupil);
            }

            if ($savedPupil && $addedToClassroomList) {
                $response['statusCode'] = 200;
            }
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }

    /**
     * deleteAction
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction () : ResponseInterface {
        $pupilUid = (int) (GeneralUtility::_POST('pupilUid') ?? '0');
        $response = [
            'statusCode' => 404,
            'html' => ''
        ];
        $pupil = $this->pupilService->findOneByUid($pupilUid);
        $this->pupilService->deletePupil($pupil);

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}