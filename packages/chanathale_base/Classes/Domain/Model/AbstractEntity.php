<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_base.
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

namespace Chanathale\ChanathaleBase\Domain\Model;

/**
 * Class AbstractEntity
 */
abstract class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * @var int $deleted
     */
    protected int $deleted = 0;

    /**
     * @var int $tstamp
     */
    protected int $tstamp = 0;

    /**
     * @var int $crdate
     */
    protected int $crdate = 0;

    /**
     * @return int
     */
    public function getDeleted(): int
    {
        return $this->deleted;
    }

    /**
     * @param int $deleted
     */
    public function setDeleted(int $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return int
     */
    public function getTstamp(): int
    {
        return $this->tstamp;
    }

    /**
     * @param int $tstamp
     */
    public function setTstamp(int $tstamp): void
    {
        $this->tstamp = $tstamp;
    }

    /**
     * @return int
     */
    public function getCrdate(): int
    {
        return $this->crdate;
    }

    /**
     * @param int $crdate
     */
    public function setCrdate(int $crdate): void
    {
        $this->crdate = $crdate;
    }
}