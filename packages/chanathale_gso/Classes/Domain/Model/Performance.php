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

namespace Chanathale\ChanathaleGso\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\FrontendLogin\Service\UserService;

/**
 * Performance
 */
class Performance extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
    /**
     * @var GradeType|null
     */
    protected ?GradeType $gradeType = null;

    /**
     * @var string $grade
     */
    protected string $grade = '';

    /**
     * @var string $comment
     */
    protected string $comment = '';

    /**
     * @var string $title
     */
    protected string $title = '';

    /**
     * @var int $author
     */
    protected int $author = 0;

    /**
     * @var Pupil|null
     */
    protected ?Pupil $pupil = null;

    /**
     * @var int $createDate
     */
    protected int $createDate = 0;

    /**
     * @var Subject|null
     */
    protected ?Subject $subject = null;

    /**
     * @return GradeType|null
     */
    public function getGradeType () : ?GradeType {
        return $this->gradeType;
    }

    /**
     * @param GradeType|null $gradeType
     */
    public function setGradeType (?GradeType $gradeType) : void {
        $this->gradeType = $gradeType;
    }

    /**
     * @return string
     */
    public function getGrade () : string {
        return $this->grade;
    }

    /**
     * @param string $grade
     */
    public function setGrade (string $grade) : void {
        $this->grade = $grade;
    }

    /**
     * @return string
     */
    public function getComment () : string {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment (string $comment) : void {
        $this->comment = $comment;
    }

    /**
     * @return string
     */
    public function getTitle () : string {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle (string $title) : void {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getAuthor () : int {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor (int $author) : void {
        $this->author = $author;
    }

    /**
     * getAuthorData
     * @return array
     */
    public function getAuthorData () : array {
        /** @var UserService userService */
        $userService = GeneralUtility::makeInstance(UserService::class);
        return $userService->getFeUserData();
    }

    /**
     * @return Pupil|null
     */
    public function getPupil () : ?Pupil {
        return $this->pupil;
    }

    /**
     * @param Pupil|null $pupil
     */
    public function setPupil (?Pupil $pupil) : void {
        $this->pupil = $pupil;
    }

    /**
     * @return Subject|null
     */
    public function getSubject () : ?Subject {
        return $this->subject;
    }

    /**
     * @param Subject|null $subject
     */
    public function setSubject (?Subject $subject) : void {
        $this->subject = $subject;
    }

    /**
     * @return int
     */
    public function getCreateDate () : int {
        return $this->createDate;
    }

    /**
     * @param int $createDate
     */
    public function setCreateDate (int $createDate) : void {
        $this->createDate = $createDate;
    }
}