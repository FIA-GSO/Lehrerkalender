'use strict';

import {Element} from "../element/element.js";

/**
 * @class SmoothScroll
 */
export class SmoothScroll {

    /**
     * @type {int}
     */
    static OFFSET_TOP = 50;

    /**
     * @description init
     */
    static init() {
        SmoothScroll.registerEventListeners();
    }

    /**
     * @description registerEventListeners
     */
    static registerEventListeners() {
        if (Element.SMOOTH_SCROLL_LINKS.length > 0) {
            Element.SMOOTH_SCROLL_LINKS.forEach((item) => {
                let hash = item.getAttribute('href').split('#')[1];

                if (hash !== '') {
                    let elScrollTo = null;

                    try {
                        elScrollTo = document.querySelector(`#${hash}`);
                    } catch (e) {
                        elScrollTo = null;
                    }

                    if (elScrollTo !== null) {
                        item.addEventListener('click', (event) => {
                            event.preventDefault();

                            SmoothScroll.scrollTo(elScrollTo);
                        });
                    }
                }
            });
        }
    }

    /**
     * @description getScrollPositionY
     *
     * @returns {int}
     */
    static getScrollPositionY() {
        return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
    }

    /**
     * @description scrollTo
     *
     * @param {HTMLElement} elScrollTo
     */
    static scrollTo(elScrollTo) {
        // get element due to responsiveness of website
        Element.HEADER = document.getElementById('header');

        if (elScrollTo !== null && Element.HEADER !== null) {
            let heightOffset = Element.HEADER.offsetHeight + SmoothScroll.OFFSET_TOP;
            let scrollTop = elScrollTo.getBoundingClientRect().top + SmoothScroll.getScrollPositionY() - heightOffset;

            if ('scrollBehavior' in document.documentElement.style) {
                window.scrollTo({top: scrollTop, behavior: 'smooth'});
            } else {
                SmoothScroll.scrollToFallback(scrollTop);
            }
        }
    }

    /**
     * @description scrollToFallback
     *
     * @param {int} scrollTop
     */
    static scrollToFallback(scrollTop) {
        // dynamic imports: jquery
        import(/* webpackChunkName: "js.jquery" */ 'jquery').then((jquery) => {
            window.jQuery = jquery.default;
            window.$ = jquery.default;

            $('html, body').animate({
                scrollTop: scrollTop
            }, 500);
        });
    }
}