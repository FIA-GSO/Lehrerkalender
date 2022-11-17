'use strict';

/**
 * Event
 */
export default class Event {
    /**
     * @type {array}
     */
    interactionEvents = ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart'];

    /**
     * debounce
     * @param {function} functionName
     * @returns {(function(*=): void)|*}
     */
    debounce(functionName) {
        let timer;
        return (event) => {
            if (timer) {
                clearTimeout(timer);
            }
            timer = setTimeout(functionName, 100, event);
        };
    }

    /**
     * registerEventListenerResize
     * @param {object} that
     * @param {string} functionName
     */
    registerEventListenerResize(that, functionName) {
        // trigger only on resize "end" event (using debounce function)
        window.addEventListener('resize', this.debounce((event) => {
            that[functionName](event);
        }), {passive: true});
    }

    /**
     * registerEventListenerInteraction
     * @param {object} that
     * @param {string} functionName
     */
    registerEventListenerInteraction(that, functionName) {
        const interactionEventListener = (event) => {
            this.interactionEvents.forEach((eventName) => {
                document.removeEventListener(eventName, interactionEventListener, {passive: true});
            });

            that[functionName]();
        };

        this.interactionEvents.forEach((eventName) => {
            document.addEventListener(eventName, interactionEventListener, {passive: true});
        });
    }
}