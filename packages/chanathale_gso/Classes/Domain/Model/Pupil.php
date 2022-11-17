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

namespace Chanathale\ChanathaleGso\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Pupil
 */
class Pupil extends \Chanathale\ChanathaleBase\Domain\Model\AbstractEntity {

    /**
     * @var string $firstname
     */
    protected string $firstname = '';

    /**
     * @var string $lastname
     */
    protected string $lastname = '';

    /**
     * @var string $email
     */
    protected string $email = '';

    /**
     * @var string $pupilNumber
     */
    protected string $pupilNumber = '';

    /**
     * @var string $urlSlug
     */
    protected string $urlSlug = '';

    /**
     * @var Classroom|null $classroom
     */
    protected ?Classroom $classroom = null;

    /**
     * @var ObjectStorage<Performance>|null
     */
    protected ?ObjectStorage $grades = null;

    /**
     * @var string $users
     */
    protected string $users = '';

    /**
     * @return string
     */
    public function getFirstname () : string {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname (string $firstname): void {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname (): string {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname (string $lastname): void {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getEmail (): string {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail (string $email): void {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPupilNumber (): string {
        return $this->pupilNumber;
    }

    /**
     * @param string $pupilNumber
     */
    public function setPupilNumber (string $pupilNumber): void {
        $this->pupilNumber = $pupilNumber;
    }

    /**
     * @return int
     */
    public function getDeleted (): int {
        return $this->deleted;
    }

    /**
     * @param int $deleted
     */
    public function setDeleted (int $deleted): void {
        $this->deleted = $deleted;
    }

    /**
     * @return int
     */
    public function getTstamp (): int {
        return $this->tstamp;
    }

    /**
     * @param int $tstamp
     */
    public function setTstamp (int $tstamp): void {
        $this->tstamp = $tstamp;
    }

    /**
     * @return int
     */
    public function getCrdate () : int {
        return $this->crdate;
    }

    /**
     * @param int $crdate
     */
    public function setCrdate (int $crdate) : void {
        $this->crdate = $crdate;
    }

    /**
     * @return Classroom|null
     */
    public function getClassroom () : ?Classroom {
        return $this->classroom;
    }

    /**
     * @param Classroom|null $classroom
     */
    public function setClassroom (?Classroom $classroom) : void {
        $this->classroom = $classroom;
    }

    /**
     * @return string
     */
    public function getUsers () : string {
        return $this->users;
    }

    /**
     * @param string $users
     */
    public function setUsers (string $users) : void {
        $this->users = $users;
    }

    /**
     * @return string
     */
    public function getUrlSlug () : string {
        return $this->urlSlug;
    }

    /**
     * @param string $urlSlug
     */
    public function setUrlSlug (string $urlSlug) : void {
        $this->urlSlug = $urlSlug;
    }

    /**
     * @return ObjectStorage<Performance>|null
     */
    public function getGrades () : ?ObjectStorage {
        return $this->grades;
    }

    /**
     * @param ObjectStorage<Performance>|null $grades
     */
    public function setGrades (?ObjectStorage $grades) : void {
        $this->grades = $grades;
    }

    /**
     * addPerformance
     * @param Performance $performance
     * @return bool
     */
    public function addGrade (Performance $performance) : bool {
        if (!$this->grades->contains($performance)) {
            $this->grades->attach($performance);
            return true;
        }

        return false;
    }

    /**
     * removePerformance
     * @param Performance $performance
     * @return bool
     */
    public function removePerformance (Performance $performance) : bool {
        if ($this->grades->contains($performance)) {
            $this->grades->detach($performance);
            return true;
        }

        return false;
    }
}