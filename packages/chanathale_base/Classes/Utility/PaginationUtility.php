<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleBase\Utility;

/**
 * PaginationUtility
 */
class PaginationUtility
{
    /**
     * getPagination
     *
     * @param int $currentPage
     * @param int $totalItems
     * @param int $itemsPerPage
     * @param int $steps
     * @param int $stepsVisible
     * @param int $totalPages
     * @return array
     */
    public static function getPagination(int $currentPage, int $totalItems, int $itemsPerPage, int $steps, int $stepsVisible, int $totalPages = 0): array
    {
        if ($totalPages === 0) {
            $totalPages = self::getTotalNumPages($totalItems, $itemsPerPage);
        }

        $nextPage = 1;
        $prevPage = 1;
        $isLastPage = false;
        $isFirstPage = false;

        if ($currentPage < $totalPages) {
            $nextPage = ($currentPage + 1);
        } else {
            $isLastPage = true;
        }

        if ($currentPage > 1) {
            $prevPage = ($currentPage - 1);
        } else {
            $isFirstPage = true;
        }

        return [
            'currentPage' => $currentPage,
            'itemsPerPage' => $itemsPerPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'nextPage' => $nextPage,
            'prevPage' => $prevPage,
            'isFirstPage' => $isFirstPage,
            'isLastPage' => $isLastPage,
            'steps' => $steps,
            'stepsVisible' => $stepsVisible,
        ];
    }

    /**
     * getTotalNumPages
     *
     * @param int $totalItems
     * @param int $itemsPerPage
     * @return int
     */
    public static function getTotalNumPages(int $totalItems, int $itemsPerPage): int
    {
        if ($itemsPerPage > 0) {
            $numPages = ceil($totalItems / $itemsPerPage);
        } else {
            $numPages = 0;
        }

        return (int)$numPages;
    }

    /**
     * getOffset
     *
     * @param int $page
     * @param int $itemsPerPage
     * @return int
     */
    public static function getOffset(int $page, int $itemsPerPage): int
    {
        if ($page > 0) {
            return ($page - 1) * $itemsPerPage;
        } else {
            return 0;
        }
    }
}
