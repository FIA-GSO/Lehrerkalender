import {Element} from "../base/element/element.js";

/**
 * Navigation
 */
export class Navigation {

    static init() {

        if (Element.DROPDOWN_PARENT.length > 0) {

            Element.DROPDOWN_PARENT.forEach((parentElement) => {
                let span = parentElement.querySelector('span');

                if (span !== null) {
                    span.addEventListener('click', function (event) {
                        location.href = parentElement.getAttribute('href');
                    })
                }
            });
        }
    }
}