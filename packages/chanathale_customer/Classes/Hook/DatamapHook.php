<?php
declare(strict_types=1);

namespace Chanathale\ChanathaleCustomer\Hook;

use Chanathale\ChanathaleGame\Domain\Model\Game;
use Chanathale\ChanathaleTeam\Domain\Model\Team;
use Doctrine\DBAL\DBALException;
use TYPO3\CMS\Core\DataHandling\DataHandler;

class DatamapHook
{
    /**
     * @param $status
     * @param $table
     * @param $id
     * @param array $fieldArray
     * @param DataHandler $pObj
     * @throws DBALException
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, DataHandler &$pObj)
    {
        $pageTypes = [
        ];

        if ($table === 'pages' && array_key_exists('doktype', $fieldArray)) {
            foreach ($pageTypes as $pageTypeDokType => $backendLayoutsData) {

                if (($status === 'new' && str_starts_with($id, 'NEW')) || $status === 'update') {
                    $dokType = (int)$fieldArray['doktype'] ?? 0;
                    $backendLayout = '';
                    $backendLayoutNextLevel = '';

                    if ($dokType === $pageTypeDokType) {
                        $backendLayout = $backendLayoutsData['backendLayout'];
                        $backendLayoutNextLevel = $backendLayoutsData['backendLayoutNext'];
                    }

                    if (!empty($backendLayout)) {
                        $fieldArray['backend_layout'] = $backendLayout;
                    }

                    if (!empty($backendLayoutNextLevel)) {
                        $fieldArray['backend_layout_next_level'] = $backendLayoutNextLevel;
                    }

                }
            }
        }
    }

}