<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\UserFunc;

/**
 * AbstractUserFunc
 */
class AbstractUserFunc
{

    /**
     * generateItemArray
     *
     * @param array $queryResult
     * @param bool $default
     * @return array
     */
    protected function generateItemArray(array $queryResult, bool $default = false): array
    {
        $items = [];

        foreach ($queryResult as $item) {
            $items[] = [$item['label'], $item['uid']];
        }

        return $items;

    }

}