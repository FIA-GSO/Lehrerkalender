import {Ajax} from "../base/ajax/ajax.js";

"use-strict";

import {Element} from "../base/element/element.js";
import {Validation} from "../base/validation/validation.js";
import Modal from "bootstrap/js/dist/modal.js";
import Toast from "bootstrap/js/dist/toast.js";


/**
 * PupilForm
 */
export class PupilForm {

    static init() {

        if (Element.PUPIL_FORM.length > 0) {

            Element.PUPIL_FORM.forEach((element) => {
                PupilForm.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {

        if (element !== null) {
            let button = element.querySelector('[type="submit"]');

            if (button !== null) {

                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    PupilForm.sendAjax(element);
                })
            }
        }
    }

    /**
     * sendAjax
     * @param {HTMLElement} element
     */
    static sendAjax(element) {
        Ajax.loaderShow();
        let ajaxUrl = element.getAttribute('data-ajax') || '';
        let formData = new FormData(element);
        let modalId = element.getAttribute('data-modal-id') || '';
        let pupilTables = document.querySelectorAll('[data-selector="pupils-list"]');
        let toastElement = document.querySelector('[data-toast-pupil="success"]');

        if (ajaxUrl) {
            Ajax.fetchItems(formData, ajaxUrl).then((response) => {
                return response.json();
            }).then((response) => {
                let validationResults = response.validationResult;

                Validation.setValidations(element, validationResults.validations);
                Ajax.loaderHide();

                if (modalId !== '' && validationResults.valid === true) {
                    let modalElement = document.querySelector(modalId);

                    if (modalElement !== null) {
                        let bsModal = Modal.getOrCreateInstance(modalElement, {autohide: true, delay: 1000});
                        bsModal.hide();

                        if (toastElement !== null) {
                            let bsToast = Toast.getOrCreateInstance(toastElement, {});
                            bsToast.show();
                        }
                    }
                }

                if (pupilTables.length > 0) {

                    pupilTables.forEach((tables) => {
                        let uid = response.pupil.uid;
                        let pupilRow = tables.querySelector('[data-pupil-uid="' + uid + '"]');

                        if (pupilRow !== null) {
                            console.log(response.pupil);
                            let firstNameElement = pupilRow.querySelector('[data-property="firstname"]');
                            let lastNameElement = pupilRow.querySelector('[data-property="lastname"]');
                            let emailElement = pupilRow.querySelector('[data-property="email"]');
                            let pupilNumberElement = pupilRow.querySelector('[data-property="pupilNumber"]');
                            let classroomElement = pupilRow.querySelector('[data-property="classroom"]');

                            if (firstNameElement !== null) {
                                firstNameElement.innerHTML = response.pupil.firstname;
                            }

                            if (lastNameElement !== null) {
                                lastNameElement.innerHTML = response.pupil.lastname;
                            }

                            if (emailElement !== null) {
                                emailElement.innerHTML = response.pupil.email;
                            }

                            if (pupilNumberElement !== null) {
                                pupilNumberElement.innerHTML = response.pupil.pupilNumber;
                            }

                            if (classroomElement !== null) {
                                classroomElement.innerHTML = response.classroom;
                            }
                        }
                    });
                }
            }).catch((error) => {
                alert(error);
                console.log(error);
                Ajax.loaderHide()
            });
        }
    }

}