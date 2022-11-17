<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension mindshape_xxx.
 *
 * (c) 2022 Aphisit Chanathale <chanathale@mindshape.de>, mindshape GmbH
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
 * Event
 */
class Event extends \Chanathale\ChanathaleBase\Domain\Model\AbstractEntity {

    /**
     * @var Classroom|null $classroom
     */
    protected ?Classroom $classroom = null;

    /**
     * @var Subject|null $subject
     */
    protected ?Subject $subject = null;

    /**
     * @var Room|null $room
     */
    protected ?Room $room = null;

    /**
     * @var int $dateTime
     */
    protected int $dateTime = 0;

    /**
     * @var int $date
     */
    protected int $date = 0;

    /**
     * @var string $start
     */
    protected string $start = '';

    /**
     * @var string $end
     */
    protected string $end = '';

    /**
     * @var int $feUser
     */
    protected int $feUser = 0;

    /**
     * @var int $id
     */
    protected int $id = 0;

    /**
     * @var string $content
     */
    protected string $content = '';

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
     * @return string
     */
    public function getStart () : string {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart (string $start) : void {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getEnd () : string {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd (string $end) : void {
        $this->end = $end;
    }

    /**
     * @return int
     */
    public function getFeUser () : int {
        return $this->feUser;
    }

    /**
     * @param int $feUser
     */
    public function setFeUser (int $feUser) : void {
        $this->feUser = $feUser;
    }

    /**
     * @return Room|null
     */
    public function getRoom () : ?Room {
        return $this->room;
    }

    /**
     * @param Room|null $room
     */
    public function setRoom (?Room $room) : void {
        $this->room = $room;
    }

    /**
     * @return int
     */
    public function getDate () : int {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate (int $date) : void {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getId () : int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId (int $id) : void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getContent () : string {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent (string $content) : void {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getDateTime () : int {
        return $this->dateTime;
    }

    /**
     * @param int $dateTime
     */
    public function setDateTime (int $dateTime) : void {
        $this->dateTime = $dateTime;
    }
}