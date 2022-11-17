<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_xxx.
 *
 * (c) 2022 Aphisit Chanathale <chanathale@chanathale.de>, chanathale GmbH
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
use Chanathale\ChanathaleGso\Domain\Model\GradeType;
use Chanathale\ChanathaleGso\Domain\Model\Performance;
use Chanathale\ChanathaleGso\Domain\Model\PerformanceSearch;
use Chanathale\ChanathaleGso\Domain\Model\Subject;
use Chanathale\ChanathaleGso\Service\GradeTypeService;
use Chanathale\ChanathaleGso\Service\PerformanceService;
use Chanathale\ChanathaleGso\Service\PupilService;
use Chanathale\ChanathaleGso\Service\SubjectService;
use Chanathale\ChanathaleGso\Service\ValidationService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;

/**
 * GradeController
 */
class PerformanceController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var PerformanceService|null
     */
    private ?PerformanceService $performanceService = null;

    /**
     * @var SubjectService|null $subjectService
     */
    protected ?SubjectService $subjectService = null;

    /**
     * @var PupilService|null $pupilService
     */
    protected ?PupilService $pupilService = null;

    public function __construct () {
        /** @var PerformanceService gradeService */
        $this->performanceService = GeneralUtility::makeInstance(PerformanceService::class);
        /** @var SubjectService subjectService */
        $this->subjectService = GeneralUtility::makeInstance(SubjectService::class);
        /** @var PupilService pupilService */
        $this->pupilService = GeneralUtility::makeInstance(PupilService::class);
    }

    /**
     * saveAction
     * @param Performance $performance
     * @return ResponseInterface
     */
    public function saveAction (Performance $performance) : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'html' => ''
        ];
        $savedGrade = false;
        $validationResults = ValidationService::validateEntity($this->settings, 'savePerformance', $performance);
        $response['validationResult'] = $validationResults;

        if ($validationResults['valid']) {
            if (!($this->performanceService->exist($performance))) {
                $savedGrade = $this->performanceService->addPerformanceToPupil($performance);
                $pupil = $this->pupilService->findOneByUid($performance->getPupil()->getUid());
                $performanceSearch = new PerformanceSearch();
                $performanceSearch->setPupil($pupil);
                $performanceSearch->setGradeType(null);
                $performanceSearch->setSubject(null);
                $gradeTypes = GradeTypeService::findAllGradeTypes();

                $this->view->assign('renderType', 'new');
                $this->view->assign('performanceSearch', $performanceSearch);
                $this->view->assign('gradeTypes', $gradeTypes);
                $this->view->assign('allGradeTypes', GradeTypeService::findAllGradeTypes());
                $this->view->assign('allSubjects', $this->subjectService->findAllSubjectByFrontendUser());
                $this->view->assign('grades', $pupil->getGrades());
                $this->view->assign('hasPerformances', GradeTypeService::hasPerformances(GradeTypeService::findAllGradeTypes(), $pupil->getGrades()));
            } else {
                $this->performanceService->savePerformance($performance);
                $this->view->assign('renderType', 'update');
                $response['uid'] = $performance->getUid() ?? 0;
                $this->view->assign('performance', $performance);
                $this->view->assign('settings', $this->settings);
                $savedGrade = true;
            }
        }

        if ($savedGrade) {
            $response['statusCode'] = 200;
        }

        $response['html'] = $this->view->render();

        return $this->jsonResponse(json_encode($response));
    }

    /**
     * filterAction
     * @param PerformanceSearch $performanceSearch
     * @return ResponseInterface
     * @throws Exception
     */
    public function filterAction (PerformanceSearch $performanceSearch) : ResponseInterface {
        $gradeTypes = GradeTypeService::findAllGradeTypes();
        $grades = $performanceSearch->getPupil()->getGrades();

        if ($performanceSearch->getGradeType() instanceof GradeType) {
            $gradeTypes = [];
            $gradeTypes[] = $performanceSearch->getGradeType();
            $grades = GradeTypeService::filterByGradeType($performanceSearch->getGradeType(), $grades);
        }

        if ($performanceSearch->getSubject() instanceof Subject) {
            $grades = GradeTypeService::filterBySubject($performanceSearch->getSubject(), $grades);
        }

        $response = [
            'statusCode' => 404,
            'html' => ''
        ];
        $this->view->assign('performanceSearch', $performanceSearch);
        $this->view->assign('gradeTypes', $gradeTypes);
        $this->view->assign('allGradeTypes', GradeTypeService::findAllGradeTypes());
        $this->view->assign('allSubjects', $this->subjectService->findAllSubjectByFrontendUser());
        $this->view->assign('grades', $grades);
        $this->view->assign('hasPerformances', GradeTypeService::hasPerformances(GradeTypeService::findAllGradeTypes(), $grades));
        $response['html'] = $this->view->render();
        $response['statusCode'] = 200;

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }

    /**
     * deleteAction
     * @return ResponseInterface
     * @throws IllegalObjectTypeException
     */
    public function deleteAction () : ResponseInterface {
        $response = [
            'statusCode' => 404,
            'deleted' => false,
            'uid' => 0,
        ];
        $uid = (int) (GeneralUtility::_POST('uid') ?? '0');
        $response['deleted'] = $this->performanceService->deleteByUid($uid);

        if ($response['deleted']) {
            $response['statusCode'] = 200;
            $response['uid'] = $uid;
        }

        return $this->jsonResponse(json_encode($response, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}