import {Element} from "../base/element/element.js";
import {Ajax} from "../base/ajax/ajax.js";
import Datepicker from "js-datepicker/src/datepicker.js";
import Modal from "bootstrap/js/dist/modal.js";
import {Validation} from "../base/validation/validation.js";
import {PerformanceTool} from "../performance-tool/performance-tool.js";

"use-strict";

/**
 * GradeForm
 */
export class GradeForm {

    /**
     * init
     */
    static init() {
        if (Element.GRADE_FORM_MODAL.length > 0) {

            Element.GRADE_FORM_MODAL.forEach((element) => {
                GradeForm.initElement(element)
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {
        if (element !== null) {
            let datePickerFormElement = element.querySelector('[data-selector="form-element-datepicker"]');
            let createDateFormElement = element.querySelector('[data-selector="form-element-createDate"]');
            let button = element.querySelector('[type="submit"]');

            if (button !== null && createDateFormElement !== null && datePickerFormElement !== null) {
                let picker = new Datepicker(datePickerFormElement, {
                    formatter: (input, date, instance) => {
                        let dateString = date.getDate() + '.' + (date.getMonth() + 1) + '.' + date.getFullYear();
                        input.value = dateString;
                        let tstamp = Math.floor(date.getTime() / 1000);
                        createDateFormElement.value = tstamp;
                    },
                    noWeekends: true,
                    startDay: 1,
                    customDays: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'],
                    customMonths: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                });

                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    GradeForm.sendAjax(element);
                })
            }
        }
    }

    /**
     * sendAjax
     * @param {HTMLElement} element
     */
    static sendAjax(element) {
        let formElement = element.querySelector('[data-selector="grade-form"]');

        if (formElement !== null) {
            Ajax.loaderShow();
            let ajaxUrl = formElement.getAttribute('data-ajax') || '';
            let formData = new FormData(formElement);

            if (ajaxUrl) {
                Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                    return response.json();
                }).then((response) => {
                    let validationResults = response.validationResult;
                    Validation.setValidations(element, validationResults.validations);

                    if (response.statusCode === 200) {
                        GradeForm.resetForm(formElement);
                        PerformanceTool.reloadPerformanceList(document.querySelector('body'), response.html);
                    }
                    Ajax.loaderHide();
                }).catch((error) => {
                    alert(error);
                    console.log(error);
                    Ajax.loaderHide()
                });
            }
        }
    }

    /**
     * resetForm
     * @param {HTMLElement} formElement
     */
    static resetForm(formElement) {
        let inputs = formElement.querySelectorAll('[type="input"]');
        let textAreas = formElement.querySelectorAll('textarea');
        let selects = formElement.querySelectorAll('select');

        if (selects.length > 0) {
            selects.forEach((select) => {
                select.value = "0";
            })
        }

        if (textAreas.length > 0) {
            textAreas.forEach((textArea) => {
                textArea.value = "";
            })
        }

        if (inputs.length > 0) {
            inputs.forEach((input) => {
                input.value = "";
            })
        }
    }
}