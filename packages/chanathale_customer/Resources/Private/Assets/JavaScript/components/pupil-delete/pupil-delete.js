import {Ajax} from "../base/ajax/ajax.js";
import Toast from "bootstrap/js/dist/toast.js";

"use-strict";

export class PupilDelete {

    static init() {
        let deleteButtons = document.querySelectorAll('[data-selector="pupil-delete"]');

        if (deleteButtons.length > 0) {

            deleteButtons.forEach((element) => {
                PupilDelete.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {

        element.addEventListener('click', function (event) {
            event.preventDefault();

            let ajaxUrl = element.getAttribute('data-ajax-delete') || '';
            let uid = parseInt(element.getAttribute('data-uid') || '0');

            if (ajaxUrl !== '' && uid > 0) {
                let formData = new FormData();
                formData.append('pupilUid', uid.toString());

                PupilDelete.doAjax(formData, ajaxUrl);
            }
        });
    }

    /**
     *
     * @param {FormData} formData
     * @param {string} ajaxUrl
     */
    static doAjax(formData, ajaxUrl) {
        let toastElement = document.querySelector('[data-toast-pupil="delete"]');
        Ajax.loaderShow()

        Ajax.fetchItems(formData, ajaxUrl).then((response) => {
            return response.json();
        }).then((response) => {
            Ajax.loaderHide();

            if (toastElement !== null) {
                let bsToast = Toast.getOrCreateInstance(toastElement, {autohide: true, delay: 1500});
                bsToast.show();
            }
        }).catch((error) => {
            Ajax.loaderHide();
        });
    }
}