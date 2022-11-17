import {Element} from "../base/element/element.js";
import {Ajax} from "../base/ajax/ajax.js";
import Datepicker from "js-datepicker/src/datepicker.js";
import Modal from "bootstrap/js/dist/modal.js";
import {Validation} from "../base/validation/validation.js";

"use-strict";

export class EventForm {

    /**
     * init
     */
    static init() {

        if (Element.EVENT_FORM.length > 0) {
            Element.EVENT_FORM.forEach((element) => {
                EventForm.initElement(element);
            });

            EventForm.initModalEvents();
        }
    }

    /**
     * initModalEvents
     */
    static initModalEvents() {
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {
        let formElement = element.querySelector('form');
        let datePickerFormElement = element.querySelector('[data-selector="form-element-datepicker"]');
        let createDateFormElement = element.querySelector('[data-selector="form-element-date"]');
        let showClassListButton = element.querySelector('[data-selector="show-classlist"]');
        let classSelectElement = element.querySelector('[data-selector="class-select"]');
        let deleteButton = element.querySelector('[data-selector="event-delete"]');

        if (deleteButton !== null) {
            deleteButton.addEventListener('click', function (event) {
                event.preventDefault();
                let ajaxUrl = element.getAttribute('data-delete-ajax-url') || '';
                let uid = deleteButton.getAttribute('data-json-key') || '0';
                let formData = new FormData();
                formData.append('jsonKey', uid);

                if (ajaxUrl !== '') {

                    Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                        return response.json();
                    }).then((response) => {

                        if (response.statusCode === 200) {
                            location.reload();
                        }
                    });
                }
            });
        }

        if (formElement !== null && datePickerFormElement !== null && createDateFormElement !== null) {
            let submitButton = formElement.querySelector('[type="submit"]');

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

            if (submitButton !== null) {
                submitButton.addEventListener('click', function (event) {
                    event.preventDefault();

                    let formData = new FormData(formElement);
                    let ajaxUrl = formElement.getAttribute('data-ajax') || '';

                    if (ajaxUrl) {
                        EventForm.sendAjax(element, ajaxUrl, formData);
                    }
                })
            }
        }

        if (showClassListButton !== null && classSelectElement !== null) {
            classSelectElement.addEventListener('change', function (event) {
                event.preventDefault();

                classSelectElement.setAttribute('data-classuid', classSelectElement.value);
                let modalId = '#classlist-modal-' + classSelectElement.getAttribute('data-classuid') || '0';
                let modal = document.querySelector(modalId);
                showClassListButton.setAttribute('data-bs-target', modalId);

                if (modal !== null) {
                    let backButton = modal.querySelector('[data-selector="back-to-edit-form"]');

                    if (backButton !== null) {
                        backButton.setAttribute('data-bs-target', ('#' + element.id));
                    }
                }
            });
        }
    }

    /**
     *
     * @param {HTMLElement} mainElement
     * @param {string} ajaxUrl
     * @param {FormData} formData
     */
    static sendAjax(mainElement, ajaxUrl, formData) {
        Ajax.fetchItems(formData, ajaxUrl).then((response) => {
            return response.json();
        }).then((response) => {
            let validationResults = response.validationResult;
            Validation.setValidations(mainElement, validationResults.validations);

            if (response.statusCode === 200) {
                if (window.teacherCalendar) {
                    window.teacherCalendar.addEvent({
                        allDay: false,
                        title: response.eventObject.title,
                        start: response.eventObject.start,
                        end: response.eventObject.end,
                        backgroundColor: response.eventObject.backgroundColor,
                        borderColor: response.eventObject.backgroundColor,
                        content: response.eventObject.content,
                        extendedProps: {
                            type: 'event',
                            jsonKey: response.eventObject.jsonKey,
                            content: response.eventObject.content,
                        },
                    });
                    window.teacherCalendar.render();
                }

                Ajax.loaderHide();

                if (response.validationResult.valid) {
                    location.reload();
                    let bsModal = Modal.getOrCreateInstance(mainElement);
                    let newFormType = mainElement.getAttribute('data-form') || 'edit';

                    bsModal.hide();

                    if (newFormType === 'new') {
                        EventForm.resetModalForm(mainElement);

                        if (response.html !== '') {
                            let eventContainer = document.querySelector('[data-selector="events-modal-container"]');

                            if (eventContainer !== null) {
                                eventContainer.insertAdjacentHTML('beforeend', response.html);
                                let newModal = eventContainer.querySelector('[data-selector="#event-modal-' + response.eventObject.jsonKey + '"]');

                                if (newModal !== null) {
                                    let bsModal = Modal.getOrCreateInstance(newModal, {});
                                }
                            }
                        }
                    } else {
                        location.reload();
                    }
                }
            }
        }).catch((error) => {
            alert(error);
            console.log(error);
            Ajax.loaderHide()
        });
    }

    /**
     * resetModalForm
     * @param {HTMLElement} mainElement
     */
    static resetModalForm(mainElement) {
        let formElementsToReset = mainElement.querySelectorAll('[data-reset-on-submit="true"]');

        if (formElementsToReset.length > 0) {

            formElementsToReset.forEach((element) => {

                if (element.tagName.toLowerCase() === 'select') {
                    element.value = 0;
                } else {
                    element.value = '';
                }
            })
        }
    }
}