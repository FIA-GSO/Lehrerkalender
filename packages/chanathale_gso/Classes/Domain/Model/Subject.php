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

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Subject
 */
class Subject extends \Chanathale\ChanathaleBase\Domain\Model\AbstractEntity {

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string $users
     */
    protected string $users = '';

    /**
     * @var ObjectStorage<Classroom>|null
     */
    protected ?ObjectStorage $classrooms = null;

    /**
     * @var string $color
     */
    protected string $color = '';

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
     * @return ObjectStorage<Classroom>|null
     */
    public function getClassrooms () : ?ObjectStorage {
        return $this->classrooms;
    }

    /**
     * @param ObjectStorage<Classroom>|null $classrooms
     */
    public function setClassrooms (?ObjectStorage $classrooms) : void {
        $this->classrooms = $classrooms;
    }

    /**
     * @param Classroom $classroom
     * @return bool
     */
    public function addClassroom (Classroom $classroom) : bool {
        if (!($this->classrooms->contains($classroom))) {
            $this->classrooms->attach($classroom);
            return true;
        }

        return false;
    }

    /**
     * @param Classroom $classroom
     * @return bool
     */
    public function removeClassroom (Classroom $classroom) : bool {
        if (($this->classrooms->contains($classroom))) {
            $this->classrooms->detach($classroom);
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getColor () : string {
        return $this->color;
    }

    /**
     * @param string $color
     */
    public function setColor (string $color) : void {
        $this->color = $color;
    }
}