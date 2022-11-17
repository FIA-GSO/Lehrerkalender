<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_command.
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

namespace Chanathale\ChanathaleCommand\Command;

use Chanathale\ChanathaleBase\Utility\LinkUtility;
use Chanathale\ChanathaleBase\Utility\TypoScriptUtility;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Error\Http\InternalServerErrorException;
use TYPO3\CMS\Core\Error\Http\ServiceUnavailableException;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;

/**
 * WarmupCommand
 */
class WarmupCommand extends AbstractCommand
{
    /**
     * configure
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Warmup command helper to warmup cache for pages and extension detail pages');
    }

    /**
     * execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        // add code here
        // $this->warmup();

        return Command::SUCCESS;
    }

    /**
     * warmup
     *
     * @return void
     * @throws AspectNotFoundException
     * @throws Exception
     */
    protected function warmup(): void
    {
        $pluginSignature = 'tx_myextension_myplugin';
        $pluginKey = 'tx_myextension';
        $settings = TypoScriptUtility::getSetupPlugin($pluginKey);

        try {
            $url = LinkUtility::getUrlTypoLink(
                $settings['settings']['pids']['products'],
                [
                    $pluginSignature => [
                        'record' => $record->getUid()
                    ]
                ],
                true
            );
        } catch (InternalServerErrorException | ServiceUnavailableException | SiteNotFoundException $e) {
            $url = '';
        }

        try {
            LinkUtility::warmupUrl($url);

            $this->output->writeln(sprintf("warmup for url '%s'", $url));
        } catch (Exception $e) {
            $this->output->writeln(sprintf("warmup for url '%s' failed with error '%s'", $url, $e->getMessage()));
        }
    }
}
