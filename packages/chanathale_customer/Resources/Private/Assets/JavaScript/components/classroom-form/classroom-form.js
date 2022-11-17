import {Ajax} from "../base/ajax/ajax.js";
import {Validation} from "../base/validation/validation.js";

"use-strict";

/**
 * ClassroomForm
 */
export class ClassroomForm {

    static init() {
        let elements = document.querySelectorAll('[data-selector="classroom-form"]');

        if (elements.length > 0) {
            elements.forEach((element) => {
                ClassroomForm.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {

        if (element !== null) {
            let submitButton = element.querySelector('[type="submit"]');

            if (submitButton !== null) {

                submitButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    let ajaxUrl = element.getAttribute('data-ajax') || '';
                    let formData = new FormData(element);

                    if (ajaxUrl !== '') {
                        ClassroomForm.doAjax(element, ajaxUrl, formData);
                    }
                })
            }
        }
    }

    /**
     * doAjax
     * @param {HTMLElement} mainElement
     * @param {string} ajaxUrl
     * @param {FormData} formData
     */
    static doAjax(mainElement, ajaxUrl, formData) {
        Ajax.loaderShow();

        Ajax.fetchItems(formData, ajaxUrl).then((response) => {
            return response.json();
        }).then((response) => {
            let validationResults = response.validationResult;

            Validation.setValidations(mainElement, validationResults.validations);

            if (response.statusCode === 200) {
                let classlistContainers = document.querySelectorAll('[data-selector="classlist-container"]');

                if (classlistContainers.length > 0) {

                    classlistContainers.forEach((listElement) => {
                        listElement.innerHTML = response.html;
                    });
                }

                Ajax.loaderHide();
            }
        }).catch((error) => {
            Ajax.loaderHide();
        });
    }
}
