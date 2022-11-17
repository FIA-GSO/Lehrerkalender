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

use Doctrine\DBAL\DBALException;
use Chanathale\ChanathaleCommand\Utility\DatabaseUtility;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\RouteNotFoundException;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * RedirectImportCommand
 */
class RedirectImportCommand extends Command
{
    const TYPOLINK_PAGE = 't3://page?uid=';

    /**
     * @var Connection
     */
    protected $databaseConnection;

    /**
     * @var SiteFinder
     */
    protected $siteFinder;

    /**
     * @var SiteMatcher
     */
    protected $siteMatcher;

    /**
     * @var array
     */
    protected static $siteCache = [];

    protected function configure(): void
    {
        $this->setDescription('Import redirects via csv file');
        $this->setHelp('Pass a CSV file with the format "old url;new url" (310) and "url;" (410)');

        $this->addOption('redirects', ['r'], InputOption::VALUE_REQUIRED, 'CSV file containing the redirects to import');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->databaseConnection = DatabaseUtility::getConnection();
        $this->siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $this->siteMatcher = GeneralUtility::makeInstance(SiteMatcher::class, $this->siteFinder);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $redirectsCsvFilePath = $input->getOption('redirects');

        if (true === is_string($redirectsCsvFilePath)) {
            if (
                false === realpath($redirectsCsvFilePath) ||
                false === is_file($redirectsCsvFilePath)
            ) {
                $output->writeln('The file "' . $redirectsCsvFilePath . '" does not exist');
            } else {
                try {
                    $this->processImportRedirects(
                        $this->readImportCsvFile(
                            realpath($redirectsCsvFilePath),
                            $output
                        ),
                        $output
                    );

                    return Command::SUCCESS;
                } catch (Exception $exception) {
                    $output->writeln('Erro parsing your redirects import: "' . $exception->getMessage() . '"');
                }
            }
        } else {
            $output->writeln('Missing redirects file to import');
        }

        return Command::FAILURE;
    }

    /**
     * @param string $redirectsCsvFilePath
     * @param OutputInterface $output
     * @return array
     * @throws Exception
     */
    protected function readImportCsvFile(string $redirectsCsvFilePath, OutputInterface $output): array
    {
        $importRedirects = [];
        $fileHandle = fopen($redirectsCsvFilePath, 'r');

        if (false === is_resource($fileHandle)) {
            throw new Exception('Can\'t open file "' . $redirectsCsvFilePath . '"');
        }

        $line = 1;

        while (($data = fgetcsv($fileHandle, 0, ';')) !== false) {
            $source = trim($data[0]);
            $statusCode = 301;

            if ($data[2]) {
                if (is_numeric(trim($data[2]))) {
                    $statusCode = (int)trim($data[2]);
                } else {
                    $output->writeln('Warning: statuscode of line ' . $line . ' can not be determined. Take default 301');
                }
            }
            $target = trim($data[1]);

            if ($target === '') {
                $statusCode = 410;
            }

            if (
                (
                    0 === stripos($source, '/') ||
                    false !== filter_var($source, FILTER_VALIDATE_URL)
                ) &&
                (
                    $statusCode === 410 ||
                    false !== filter_var($target, FILTER_VALIDATE_URL) ||
                    true === is_numeric($target)
                )
            ) {
                $importRedirects[] = [
                    $line,
                    $source,
                    true === is_numeric($target)
                        ? (int)$target
                        : $target,
                    $statusCode
                ];
            } else {
                $output->writeln('Warning: skipped line ' . $line . ' due to invalid data');
            }

            $line++;
        }

        fclose($fileHandle);

        return $importRedirects;
    }

    /**
     * @param array $importRedirects
     * @param OutputInterface $output
     * @throws Exception|DBALException
     */
    protected function processImportRedirects(array $importRedirects, OutputInterface $output): void
    {
        foreach ($importRedirects as [$line, $source, $target, $statuscode]) {
            $host = '*';

            if ('http' === substr($source, 0, 4)) {
                $host = parse_url($source, PHP_URL_HOST);
                $path = parse_url($source, PHP_URL_PATH);
                $query = parse_url($source, PHP_URL_QUERY);

                if (false === is_string($path)) {
                    throw new Exception('Error in line ' . $line . ': "' . $source . '" is not a valid URL');
                }

                $source = $path;

                if (true === is_string($query)) {
                    $source .= '?' . $query;
                }
            }

            if (true === is_int($target)) {
                try {
                    $site = $this->siteFinder->getSiteByPageId($target);
                } catch (SiteNotFoundException $exception) {
                    $output->writeln('Error in line ' . $line . ': Page UID cannot be associated with any site');
                    continue;
                }

                $target = self::TYPOLINK_PAGE . $target;
                $host = $site->getBase()->getHost();
            } elseif ('http' === substr($target, 0, 4)) {
                $targetHost = parse_url($target, PHP_URL_HOST);

                if (false === $this->checkIsExternalTargetHost($targetHost)) {
                    $targetPageUid = $this->determineUriUid($target);

                    if (true === is_int($targetPageUid)) {
                        $target = self::TYPOLINK_PAGE . $targetPageUid;
                        $host = '*';
                    }
                }
            }

            $redirect = [
                'source_host' => $host,
                'source_path' => $source,
                'target_statuscode' => $statuscode,
            ];

            $queryBuilder = $this->databaseConnection->createQueryBuilder();

            $existingRedirects = $queryBuilder
                ->select('uid')
                ->from('sys_redirect')
                ->where(
                    $queryBuilder->expr()->eq(
                        'source_path',
                        $queryBuilder->createNamedParameter($source)
                    )
                )
                ->execute()
                ->fetchAll();

            if (0 < count($existingRedirects)) {
                $queryBuilder = $this->databaseConnection->createQueryBuilder();
                $queryBuilder
                    ->update('sys_redirect')
                    ->set('deleted', 1, true, PDO::PARAM_INT);

                $existingUids = [];

                foreach ($existingRedirects as $existingRedirect) {
                    $existingUids[] = $queryBuilder->createNamedParameter($existingRedirect['uid'], PDO::PARAM_INT);
                }

                $queryBuilder->where(
                    $queryBuilder->expr()->in('uid', $existingUids)
                );

                $queryBuilder->execute();
            }

            $redirect['updatedon'] = time();
            $redirect['createdon'] = time();
            $redirect['target'] = $target;

            $this->databaseConnection->insert('sys_redirect', $redirect);

            $output->writeln('Redirect added: ' . $source . ' => ' . $target . ' with status code ' . $statuscode . ' for host ' . $host);
        }
    }

    /**
     * @param string $uri
     * @return int|null
     */
    protected function determineUriUid(string $uri): ?int
    {
        try {
            $tmp = new Uri($uri);
            if (self::$siteCache[$tmp->getHost()] instanceof Site) {
                $currentBaseVariant = self::$siteCache[$tmp->getHost()]->getBase();
                $uri = (string)$tmp->withHost($currentBaseVariant->getHost());
            }
        } catch (\InvalidArgumentException $e) {
        }
        $pseudoRequest = new ServerRequest($uri, 'GET');

        try {
            /** @var \TYPO3\CMS\Core\Routing\SiteRouteResult $siteRouteResult */
            $siteRouteResult = $this->siteMatcher->matchRequest($pseudoRequest);
            $site = $siteRouteResult->getSite();

            if ($site instanceof Site) {
                $routeResult = $site->getRouter()->matchRequest($pseudoRequest, $siteRouteResult);

                if (
                    $routeResult instanceof PageArguments &&
                    0 === count($routeResult->getArguments())
                ) {
                    return $routeResult->getPageId();
                }
            }
        } catch (RouteNotFoundException $exception) {
            return null;
        }

        return null;
    }

    /**
     * @param string $host
     * @return bool
     */
    protected function checkIsExternalTargetHost(string $host): bool
    {
        if ($this->getSiteByHost($host) instanceof Site) {
            return false;
        }

        return true;
    }

    /**
     * @param string $host
     * @return \TYPO3\CMS\Core\Site\Entity\Site|null
     */
    protected function getSiteByHost(string $host): ?Site
    {
        if (true === array_key_exists($host, self::$siteCache)) {
            return self::$siteCache[$host];
        }

        foreach ($this->siteFinder->getAllSites() as $site) {
            $siteConfiguration = $site->getConfiguration();
            $baseVariants = array_merge([['base' => $siteConfiguration['base']]], $siteConfiguration['baseVariants']);
            foreach ($baseVariants as $baseVariant) {
                try {
                    $baseVariantUri = new Uri($baseVariant['base']);
                    if ($host === $baseVariantUri->getHost() ?? '') {
                        self::$siteCache[$host] = $site;

                        return $site;
                    }
                } catch (\InvalidArgumentException $e) {
                }
            }
        }

        return null;
    }
}
