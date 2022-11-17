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
 * Classroom
 */
class Classroom extends \Chanathale\ChanathaleBase\Domain\Model\AbstractEntity {

    /**
     * @var string $title
     */
    protected string $title = '';

    /**
     * @var string $identifier
     */
    protected string $identifier = '';

    /**
     * @var ObjectStorage<Pupil>|null
     */
    protected ?ObjectStorage $pupils = null;

    /**
     * @var string $users
     */
    protected string $users = '';

    /**
     * @var string $urlSlug
     */
    protected string $urlSlug = '';

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
     * @return ObjectStorage<Pupil>|null
     */
    public function getPupils () : ?ObjectStorage {
        return $this->pupils;
    }

    /**
     * @param ObjectStorage<Pupil>|null $pupils
     */
    public function setPupils (?ObjectStorage $pupils) : void {
        $this->pupils = $pupils;
    }

    /**
     * @param Pupil $pupil
     * @return bool
     */
    public function addPupil (Pupil $pupil) : bool {

        if (!$this->pupils->contains($pupil)) {
            $this->pupils->attach($pupil);

            return true;
        }

        return false;
    }

    /**
     * @param Pupil $pupil
     * @return bool
     */
    public function removePupil (Pupil $pupil) : bool {

        if ($this->pupils->contains($pupil)) {
            $this->pupils->detach($pupil);

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getUsers (): string {
        return $this->users;
    }

    /**
     * @param string $users
     */
    public function setUsers (string $users): void {
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
     * @return string
     */
    public function getIdentifier () : string {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier (string $identifier) : void {
        $this->identifier = $identifier;
    }
}