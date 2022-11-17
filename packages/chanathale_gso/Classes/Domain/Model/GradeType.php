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

/**
 * GradeType
 */
class GradeType extends \Chanathale\ChanathaleBase\Domain\Model\AbstractEntity {

    /**
     * @var string
     */
    protected string $title = '';

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
}