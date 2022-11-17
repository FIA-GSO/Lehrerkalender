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

namespace Chanathale\ChanathaleGso\Service;

use Chanathale\ChanathaleBase\Domain\Model\AbstractEntity;

/**
 * ValidationService
 */
class ValidationService implements \TYPO3\CMS\Core\SingletonInterface {

    /**
     * notEmpty
     * @param mixed $value
     * @return array
     */
    public static function notEmpty (mixed $value) : array {
        $result = [
            'errorCode' => 0,
            'result' => false
        ];

        if (!empty($value)) {
            $result['result'] = true;
        }

        return $result;
    }

    /**
     * isInteger
     * @param mixed $value
     * @return array
     */
    public static function isInteger (mixed $value) : array {
        $result = [
            'errorCode' => 0,
            'result' => false
        ];

        if (ctype_digit(strval($value))) {
            $result['result'] = true;
        }

        return $result;
    }

    /**
     * isFloat
     * @param mixed $value
     * @return array
     */
    public static function isFloat (mixed $value) : array {
        $result = [
            'errorCode' => 0,
            'result' => false
        ];

        if (is_numeric($value)) {
            $result['result'] = true;
        }

        return $result;
    }

    /**
     * isEmail
     * @param mixed $value
     * @return array
     */
    public static function isEmail (mixed $value) : array {
        $status = [
            'errorCode' => 0,
            'result' => false,
        ];

        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            $status['result'] = true;
        }

        return $status;
    }

    /**
     * isNotNumber
     * @param mixed $value
     * @return array
     */
    public static function isNotNumber (mixed $value) : array {
        $result = [
            'errorCode' => 0,
            'result' => false
        ];

        if (!(is_numeric($value))) {
            $result['result'] = true;
        }

        return $result;
    }

    /**
     * validateValue
     * @param string $validationType
     * @param $value
     * @return array
     */
    public static function validateValue (string $validationType, $value) : array {
        $result = [];

        switch ($validationType) {
            case 'notEmpty':
                $result = ValidationService::notEmpty($value);
                break;
            case 'isInteger':
                $result = ValidationService::isInteger($value);
                break;
            case 'isFloat':
                $result = ValidationService::isFloat($value);
                break;
            case 'isNotNumber':
                $result = ValidationService::isNotNumber($value);
                break;
            case 'email':
                $result = ValidationService::isEmail($value);
                break;
        }

        return $result;
    }

    /**
     * validateEntity
     * @param array $typoScriptSettings
     * @param string $formType
     * @param AbstractEntity|\TYPO3\CMS\Extbase\DomainObject\AbstractEntity $abstractEntity
     * @return false[]
     */
    public static function validateEntity (array $typoScriptSettings, string $formType, AbstractEntity|\TYPO3\CMS\Extbase\DomainObject\AbstractEntity $abstractEntity) : array {
        $results = [
            'valid' => false,
            'validations' => [],
        ];
        // Holt sich die Pflichtfelder, die im TypoScript definiert sind für das Entity.
        $validationFields = $typoScriptSettings['forms'][$formType]['validations'] ?? [];
        $currentValidFields = 0;

        // propertyName ist die Enittiy-Eigenschaft
        foreach ($validationFields as $propertyName => $validationSetting) {
            $validationType = $validationSetting['validationType'] ?? 'notEmpty';
            // Holt sich die eingegeben Wert
            $propertyValue = $abstractEntity->_getProperty($propertyName);

            // Prüft den Wert anhand der ValidierungsTyp.
            $validationResult = ValidationService::validateValue($validationType, $propertyValue);
            $validationResult['propertyName'] = $propertyName;

            if ($validationResult['result']) {
                $currentValidFields++;
                $validationResult['errorMessage'] = '';
            } else {
                // Setzt entsprechende Fehlermeldungen
                $validationResult['errorMessage'] = $typoScriptSettings['forms'][$formType]['validations'][$propertyName]['errors'][$validationResult['errorCode']] ?? '';
            }

            $results['validations'][] = $validationResult;
        }

        // Falls alles valid ist.
        if ($currentValidFields === count($validationFields)) {
            $results['valid'] = true;
        }

        return $results;
    }
}