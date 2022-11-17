import {Element} from "../element/element.js";

/**
 * Ajax
 */
export class Ajax {

    /**
     * @description fetchItems
     *
     * @param {object} data
     * @param {string} url
     * @param {string} method
     * @param {object} headers
     * @return {Promise}
     */
    static async fetchItems(data, url, method = 'POST', headers = {}) {
        return await fetch(url, {
            method: method,
            headers: headers,
            body: data,
            mode: 'cors',
            cache: 'no-cache',
            credentials: 'same-origin',
            redirect: 'follow',
            referrerPolicy: 'no-referrer'
        });
    }

    /**
     * @description loaderShow
     */
    static loaderShow() {
        if (Element.LOADER !== null) {
            Element.LOADER.classList.add('show');
        }
    }

    /**
     * @description loaderHide
     */
    static loaderHide() {
        if (Element.LOADER !== null) {
            Element.LOADER.classList.remove('show');
        }
    }
}