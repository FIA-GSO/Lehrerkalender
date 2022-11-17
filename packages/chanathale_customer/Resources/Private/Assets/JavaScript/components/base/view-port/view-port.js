// ViewPort
'use strict';

import {Element} from '../element/element.js'
import Event from '../event/event.js';

/**
 * @class ViewPort
 */
export class ViewPort {

    /**
     * @type {array}
     */
    static BREAKPOINTS = {
        'xs': 0,
        'sm': 576,
        'md': 768,
        'lg': 992,
        'xl': 1200,
        'xxl': 1400,
    };

    /**
     * @type {array}
     */
    static BREAKPOINTS_MOBILE = ['xs', 'sm', 'md'];

    /**
     * @type {{width: number, height: number}}
     */
    static SIZE = {
        'width': 0,
        'height': 0,
    };

    /**
     * @type {string}
     */
    static CSS_CLASS = '';

    /**
     * @description init
     */
    static init() {
        ViewPort.updateValues();
        ViewPort.registerEventListeners();
    }

    /**
     * @description setValues
     */
    static updateValues() {
        if (Element.HTML !== null) {
            ViewPort.setSize();
            ViewPort.setCSSClass();
        }
    }

    /**
     * @description registerEventListeners
     */
    static registerEventListeners() {
        if (Element.HTML !== null) {
            new Event().registerEventListenerResize(ViewPort, 'resize');
        }
    }

    /**
     * @description setSize
     */
    static setSize() {
        ViewPort.SIZE['width'] = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);
        ViewPort.SIZE['height'] = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
    }

    /**
     * @description setCSSClass
     */
    static setCSSClass() {
        for (let key in ViewPort.BREAKPOINTS) {
            if (Element.HTML.classList.contains(key)) {
                Element.HTML.classList.remove(key);
            }

            if (ViewPort.SIZE['width'] >= ViewPort.BREAKPOINTS[key]) {
                ViewPort.CSS_CLASS = key;
            }
        }

        if (ViewPort.CSS_CLASS !== '') {
            Element.HTML.classList.add(ViewPort.CSS_CLASS);
        }
    }

    /**
     * @description resize
     *
     * @param {Event} event
     */
    static resize(event) {
        ViewPort.updateValues();
    }

    /**
     * @description isMobileViewport
     *
     * @returns {boolean}
     */
    static isMobileViewport() {
        return ViewPort.BREAKPOINTS_MOBILE.includes(ViewPort.CSS_CLASS);
    }

    /**
     * @description isViewport
     *
     * @param {String} $viewPort
     * @returns {boolean}
     */
    static isViewport($viewPort) {
        return (ViewPort.CSS_CLASS === $viewPort);
    }
}