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

use Chanathale\ChanathaleBase\Domain\Model\AbstractEntity;

/**
 * Meeting
 */
class Meeting extends AbstractEntity {

    /**
     * @var int $id
     */
    protected int $id = 0;

    /**
     * @var string $topic
     */
    protected string $topic = '';

    /**
     * @var string $createDate
     */
    protected string $createDate = '';

    /**
     * @var int $duration
     */
    protected int $duration = 0;

    /**
     * @var string $startTime
     */
    protected string $startTime = '';

    /**
     * @var Room|null $room
     */
    protected ?Room $room = null;

    /**
     * @var int $author
     */
    protected int $author = 0;

    /**
     * @var string $fullCalendarStart
     */
    protected string $fullCalendarStart = '';

    /**
     * @var string $fullCalendarEnd
     */
    protected string $fullCalendarEnd = '';

    /**
     * @return string
     */
    public function getTopic () : string {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic (string $topic) : void {
        $this->topic = $topic;
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

    /**
     * @return string
     */
    public function getDate () : string {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate (string $date) : void {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getDuration () : int {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration (int $duration) : void {
        $this->duration = $duration;
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
    public function getCreateDate () : string {
        return $this->createDate;
    }

    /**
     * @param string $createDate
     */
    public function setCreateDate (string $createDate) : void {
        $this->createDate = $createDate;
    }

    /**
     * @return string
     */
    public function getStartTime () : string {
        return $this->startTime;
    }

    /**
     * @param string $startTime
     */
    public function setStartTime (string $startTime) : void {
        $this->startTime = $startTime;
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
     * @return string
     */
    public function getFullCalendarStart () : string {
        return $this->fullCalendarStart;
    }

    /**
     * @param string $fullCalendarStart
     */
    public function setFullCalendarStart (string $fullCalendarStart) : void {
        $this->fullCalendarStart = $fullCalendarStart;
    }

    /**
     * @return string
     */
    public function getFullCalendarEnd () : string {
        return $this->fullCalendarEnd;
    }

    /**
     * @param string $fullCalendarEnd
     */
    public function setFullCalendarEnd (string $fullCalendarEnd) : void {
        $this->fullCalendarEnd = $fullCalendarEnd;
    }
}