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

/**
 * PerformanceSearch
 */
class PerformanceSearch extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * @var Subject|null $subject
     */
    protected ?Subject $subject = null;

    /**
     * @var GradeType|null $gradeType
     */
    protected ?GradeType $gradeType = null;

    /**
     * @var Pupil|null $pupil
     */
    protected ?Pupil $pupil = null;

    /**
     * @return Subject|null
     */
    public function getSubject (): ?Subject {
        return $this->subject;
    }

    /**
     * @param Subject|null $subject
     */
    public function setSubject (?Subject $subject): void {
        $this->subject = $subject;
    }

    /**
     * @return GradeType|null
     */
    public function getGradeType (): ?GradeType {
        return $this->gradeType;
    }

    /**
     * @param GradeType|null $gradeType
     */
    public function setGradeType (?GradeType $gradeType): void {
        $this->gradeType = $gradeType;
    }

    /**
     * @return Pupil|null
     */
    public function getPupil (): ?Pupil {
        return $this->pupil;
    }

    /**
     * @param Pupil|null $pupil
     */
    public function setPupil (?Pupil $pupil): void {
        $this->pupil = $pupil;
    }
}