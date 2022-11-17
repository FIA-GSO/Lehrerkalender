"use-strict";

export class Validation {

    /**
     * setError
     * @param {HTMLElement} mainElement
     * @param {string} propertyName
     * @param {string} errorMessage
     */
    static setError(mainElement, propertyName, errorMessage) {
        let formElement = mainElement.querySelector('[data-property="' + propertyName + '"]');
        let invalidElement = mainElement.querySelector('[data-invalid="' + propertyName + '"]');

        if (formElement !== null && invalidElement !== null) {
            invalidElement.innerHTML = errorMessage;

            if (!(formElement.classList.contains('is-invalid'))) {
                formElement.classList.add('is-invalid');
            }

            if (!(invalidElement.classList.contains('d-block'))) {
                invalidElement.classList.add('d-block');
            }
        }
    }

    /**
     * removeError
     * @param {HTMLElement} mainElement
     * @param {string} propertyName
     */
    static removeError(mainElement, propertyName) {
        let formElement = mainElement.querySelector('[data-property="' + propertyName + '"]');
        let invalidElement = mainElement.querySelector('[data-invalid="' + propertyName + '"]');

        if (formElement !== null && invalidElement !== null) {
            invalidElement.innerHTML = '';

            if ((formElement.classList.contains('is-invalid'))) {
                formElement.classList.remove('is-invalid');
            }

            if ((invalidElement.classList.contains('d-block'))) {
                invalidElement.classList.remove('d-block');
            }
        }
    }

    /**
     * setValidations
     * @param {HTMLElement} mainElement
     * @param {any} validationResults
     */
    static setValidations(mainElement, validationResults) {
        for (let index in validationResults) {
            let validation = validationResults[index];
            let result = validation.result;
            let errorMessage = validation.errorMessage;
            let propertyName = validation.propertyName;

            if (result) {
                Validation.removeError(mainElement, propertyName);
            } else {
                Validation.setError(mainElement, propertyName, errorMessage);
            }
        }
    }
}