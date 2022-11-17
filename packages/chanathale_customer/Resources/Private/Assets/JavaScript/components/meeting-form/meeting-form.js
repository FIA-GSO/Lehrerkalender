import {Ajax} from "../base/ajax/ajax.js";
import Datepicker from "js-datepicker/src/datepicker.js";
import {Validation} from "../base/validation/validation.js";
import Modal from "bootstrap/js/dist/modal.js";

"use-strict";

/**
 * MeetingForm
 */
export class MeetingForm {

    /**
     * init
     */
    static init() {
        let elements = document.querySelectorAll('[data-selector="meeting-modal"]');

        if (elements.length > 0) {

            elements.forEach((element) => {
                MeetingForm.initElement(element);
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
            let createDateFormElement = element.querySelector('[data-selector="form-element-date"]');
            let submitButton = element.querySelector('[type="submit"]');
            let deleteButton = element.querySelector('[data-selector="meeting-delete"]');

            if (deleteButton !== null) {
                deleteButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    let ajaxUrl = element.getAttribute('data-delete-ajax-url') || '';
                    let uid = deleteButton.getAttribute('data-uid') || '0';
                    let formData = new FormData();
                    formData.append('uid', uid);

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

            if (submitButton !== null && datePickerFormElement !== null && createDateFormElement !== null) {
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

                submitButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    let formElement = element.querySelector('form');

                    if (formElement !== null) {
                        let formData = new FormData(formElement);
                        let ajaxUrl = formElement.getAttribute('data-ajax') || '';

                        MeetingForm.doAjax(element, ajaxUrl, formData);
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

        if (ajaxUrl !== '') {

            Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                return response.json();
            }).then((response) => {
                let validationResult = response.validationResult;
                let newFormType = mainElement.getAttribute('data-form') || 'edit';
                Validation.setValidations(mainElement, validationResult.validations);

                if (response.validationResult.valid) {
                    let bsModal = Modal.getOrCreateInstance(mainElement, {});
                    let meeting = response.meeting;
                    bsModal.hide();

                    if (newFormType === 'new') {
                        MeetingForm.resetModalForm(mainElement);

                        window.teacherCalendar.addEvent({
                            id: 'meeting-' + meeting.uid,
                            allDay: false,
                            title: meeting.title,
                            start: meeting.start,
                            end: meeting.end,
                            extendedProps: {
                                type: 'meeting',
                                jsonKey: meeting.uid,
                                content: meeting.content,
                            },
                        });

                    } else {
                        location.reload();
                    }
                }
            });
        }
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