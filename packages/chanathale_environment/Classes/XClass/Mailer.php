<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension chanathale_environment.
 *
 * (c) 2021 Said Sulaiman Zaheby <zaheby@chanathale.de>, chanathale GmbH
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

namespace Chanathale\ChanathaleEnvironment\XClass;

use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;
use TYPO3\CMS\Core\Utility\MailUtility;

class Mailer extends \TYPO3\CMS\Core\Mail\Mailer
{
    /**
     * @inheritdoc
     */
    public function send(RawMessage $message, Envelope $envelope = null): void
    {
        if ($message instanceof Email) {
            // Ensure to always have a From: header set
            if (empty($message->getFrom())) {
                $address = MailUtility::getSystemFromAddress();
                if ($address) {
                    $name = MailUtility::getSystemFromName();
                    if ($name) {
                        $from = new Address($address, $name);
                    } else {
                        $from = new Address($address);
                    }
                    $message->from($from);
                }
            }
            if (empty($message->getReplyTo())) {
                $replyTo = MailUtility::getSystemReplyTo();
                if (!empty($replyTo)) {
                    $address = key($replyTo);
                    if ($address === 0) {
                        $replyTo = new Address($replyTo[$address]);
                    } else {
                        $replyTo = new Address($address, reset($replyTo));
                    }
                    $message->replyTo($replyTo);
                }
            }
            $message->getHeaders()->addTextHeader('X-Mailer', $this->mailerHeader);
        }

        // MSH start
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['chanathale_environment']['redirectEmails'])) {
            $emails = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['chanathale_environment']['redirectEmails'];
            $emails = explode(',', $emails);

            if (!empty($emails)) {
                $message->to(...$emails);

                if (!empty($message->getCc())) {
                    $message->cc(...$emails);
                }

                if (!empty($message->getBcc())) {
                    $message->bcc(...$emails);
                }
            }

        }

        // skip error: Peer certificate CN does not match ...
        $this->transport->getStream()->setStreamOptions(
            [
                "ssl" => [
                    "allow_self_signed" => true,
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ]
        );
        // MSH end

        $this->sentMessage = $this->transport->send($message, $envelope);
    }
}
