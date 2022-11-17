export class Element {

    /**
     * @type {HTMLElement}
     */
    static HEADER = document.querySelector('#header');

    /**
     * @type {HTMLElement}
     */
    static LOADER = document.getElementById('loader');

    /**
     * @type {NodeList}
     */
    static SMOOTH_SCROLL_LINKS = document.querySelectorAll('a:not(.no-scroll-smooth)[href*="#"]');

    /**
     *
     * @type {NodeList}
     */
    static DROPDOWN_PARENT = document.querySelectorAll('[data-selector="dropdown-parent"]');

    /**
     *
     * @type {NodeList}
     */
    static GALLERY = document.querySelectorAll('[data-selector="mask-gallery"]');

    /**
     *
     * @type {NodeList}
     */
    static PUPIL_FORM = document.querySelectorAll('[data-selector="pupil-form"]');

    /**
     *
     * @type {NodeList}
     */
    static GRADE_FORM_MODAL = document.querySelectorAll('[data-selector="grade-form-modal"]');

    /**
     *
     * @type {NodeList}
     */
    static PERFORMANCE_TOOL = document.querySelectorAll('[data-selector="performance-container"]');

    /**
     *
     * @type {NodeList}
     */
    static CALENDAR = document.querySelectorAll('[data-selector="calendar"]');

    /**
     *
     * @type {NodeList}
     */
    static EVENT_FORM = document.querySelectorAll('[data-selector="event-modal"]');

    /**
     *
     * @type {NodeList}
     */
    static EVENT_EDIT_FORM = document.querySelectorAll('[data-selector="event-edit-modal"]');
}