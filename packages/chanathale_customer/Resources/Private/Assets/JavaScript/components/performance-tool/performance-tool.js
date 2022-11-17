import {Element} from "../base/element/element.js";
import {Ajax} from "../base/ajax/ajax.js";
import Datepicker from "js-datepicker/src/datepicker.js";

"use-strict";

/**
 * PerformanceTool
 */
export class PerformanceTool {

    static init () {

        if (Element.PERFORMANCE_TOOL.length > 0) {
            Element.PERFORMANCE_TOOL.forEach((element) => {
                PerformanceTool.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement (element) {
        let filterFormElement = element.querySelector('form');

        if (filterFormElement !== null) {
            let submitButton = filterFormElement.querySelector('[type="submit"]');
            let formsElement = element.querySelectorAll('[data-selector="edit-performance-modal"]');
            let deleteButtons = element.querySelectorAll('[data-selector="performance-delete"]');

            if (formsElement.length > 0) {
                formsElement.forEach((modalElement) => {
                    PerformanceTool.initModalElement(element, modalElement);
                });
            }

            if (submitButton !== null && deleteButtons.length > 0) {

                if (deleteButtons.length > 0) {

                    deleteButtons.forEach((deleteButton) => {

                        deleteButton.addEventListener('click', function (event) {
                            event.preventDefault();
                            let ajaxUrl = element.getAttribute('data-delete-ajax-url') || '';
                            let uid = deleteButton.getAttribute('data-uid') || '0';
                            let formData = new FormData();
                            formData.append('uid', uid);

                            if (ajaxUrl !== '') {
                                Ajax.loaderShow();

                                Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                                    return response.json();
                                }).then((response) => {

                                    if (response.statusCode === 200) {
                                        let performanceCard = element.querySelector('[data-performance-card="' + uid + '"]');

                                        if (performanceCard !== null) {
                                            performanceCard.remove();
                                        }
                                    }

                                    Ajax.loaderHide();
                                }).catch((response) => {
                                    Ajax.loaderHide();
                                });
                            }
                        });
                    });
                }

                submitButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    PerformanceTool.sendFilterAjax(element, filterFormElement);
                })
            }
        }
    }

    /**
     * initModalElement
     * @param {HTMLElement} mainElement
     * @param {HTMLElement} modalElement
     */
    static initModalElement (mainElement, modalElement) {
        let formElement = modalElement.querySelector('form');

        if (formElement !== null) {
            let submitButton = formElement.querySelector('[type="submit"]');

            if (submitButton !== null) {
                submitButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    PerformanceTool.sendSaveAjax(mainElement, formElement);
                })
            }
        }
    }

    /**
     * sendSaveAjax
     * @param {HTMLElement} mainElement
     * @param {HTMLElement} formElement
     */
    static sendSaveAjax (mainElement, formElement) {
        Ajax.loaderShow();
        let formData = new FormData(formElement);
        let ajaxUrl = formElement.getAttribute('data-ajax') || '';

        PerformanceTool.doAjax(mainElement, ajaxUrl, formData);
    }

    /**
     * sendFilterAjax
     * @param {HTMLElement} mainElement
     * @param {HTMLElement} formElement
     */
    static sendFilterAjax (mainElement, formElement) {
        Ajax.loaderShow();
        let formData = new FormData(formElement);
        let ajaxUrl = formElement.getAttribute('data-ajax') || '';

        PerformanceTool.doAjax(mainElement, ajaxUrl, formData);
    }

    /**
     * @param {HTMLElement} mainElement
     * @param {string} ajaxUrl
     * @param {FormData} formData
     */
    static doAjax (mainElement, ajaxUrl, formData) {
        if (ajaxUrl) {
            Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                return response.json();
            }).then((response) => {

                if (response.statusCode === 200) {

                    if (response.uid) {
                        let cardElement = document.querySelector('[data-performance-card="' + response.uid + '"]');

                        if (cardElement !== null) {
                            cardElement.innerHTML = response.html;
                        }
                    } else {
                        PerformanceTool.reloadPerformanceList(mainElement, response.html);
                    }

                    Ajax.loaderHide();
                }
            }).catch((error) => {
                alert(error);
                console.log(error);
                Ajax.loaderHide()
            });
        }
    }

    /**
     * reloadPerformanceList
     * @param {HTMLElement} mainElement
     * @param {any} newContent
     */
    static reloadPerformanceList (mainElement, newContent) {
        let listElement = mainElement.querySelector('[data-selector="performance-list"]');

        if (listElement !== null) {
            listElement.innerHTML = newContent;
            PerformanceTool.init();
        }
    }
}