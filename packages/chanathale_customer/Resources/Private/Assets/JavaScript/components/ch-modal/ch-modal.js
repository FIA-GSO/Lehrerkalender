"use-strict";

import Modal from "bootstrap/js/dist/modal.js";

/**
 * ChModal
 */
export class ChModal {

    /**
     * initElement
     */
    static initElement() {
        let triggerButtons = document.querySelectorAll('[data-modal="true"]');

        if (triggerButtons.length > 0) {

            triggerButtons.forEach((triggerButton) => {
                let modalId = triggerButton.getAttribute('data-modal-id') || '';
                let modalElement = document.querySelector(modalId);

                if (modalId !== '' && modalElement !== null) {
                    let cancelButton = modalElement.querySelector('[data-modal-cancel="true"]');
                    let confirmButton = modalElement.querySelector('[data-modal-confirm="true"]');
                    let bsModal = Modal.getOrCreateInstance(modalElement, {});

                    triggerButton.addEventListener('click', function () {
                        if (modalElement !== null) {
                            bsModal.show();
                        }
                    });

                    if (cancelButton !== null) {

                        cancelButton.addEventListener('click', function (event) {
                            bsModal.hide();
                        });
                    }
                }
            });
        }
    }
}