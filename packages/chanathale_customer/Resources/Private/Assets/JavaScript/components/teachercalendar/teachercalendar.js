import {Element} from "../base/element/element.js";
import {Calendar} from "@fullcalendar/core";
import timeGridPlugin from '@fullcalendar/timegrid';
import bootstrap5Plugin from "@fullcalendar/bootstrap5";
import deLocale from '@fullcalendar/core/locales/de.js';
import Modal from "bootstrap/js/dist/modal.js";
import {EventForm} from "../event-form/event-form.js";
import {ChModal} from "../ch-modal/ch-modal.js";

"use-strict";

window.teacherCalendar = null;

export class Teachercalendar {

    /**
     * init
     */
    static init() {

        if (Element.CALENDAR.length > 0) {
            Element.CALENDAR.forEach((element) => {
                Teachercalendar.initElement(element);
            });
        }

        if (Element.EVENT_EDIT_FORM.length > 0) {
            Element.EVENT_EDIT_FORM.forEach((element) => {
                EventForm.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement(element) {
        if (window.teacherCalendar === null) {
            let events = element.getAttribute('data-events') || null;
            let meetings = element.getAttribute('data-meetings') || null;

            window.teacherCalendarElement = element;
            window.teacherCalendar = new Calendar(element, {
                plugins: [timeGridPlugin, bootstrap5Plugin],
                themeSystem: 'bootstrap5',
                locale: 'de',
                timeZone: 'Europe/Berlin',
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                    month: 'long',
                    year: 'numeric',
                    day: '2-digit',
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                buttonText: {
                    today: 'heute',
                    month: 'Monatsansicht',
                    week: 'Wochenansicht',
                    day: 'Tagesansicht',
                    list: 'Events'
                },
                slotDuration: '00:45:00',
                slotMinTime: '07:00:00',
                firstDay: 1,
                nowIndicator: true,
                expandRows: true,
                weekNumbers: true,
                eventDidMount: function (data) {
                    data.el.setAttribute('data-selector', (data.event.extendedProps.type + '-modal-trigger'));
                    data.el.setAttribute('data-bs-toggle', 'modal');
                    data.el.setAttribute('data-fc-' + (data.event.extendedProps.type) + '-selector', (data.event.extendedProps.jsonKey));
                    data.el.setAttribute('data-bs-target', ('#' + data.event.extendedProps.type + '-modal-' + data.event.extendedProps.jsonKey));
                },
                eventClick: function (info) {
                    let event = info.event.extendedProps;

                    if (event.type === "event") {
                        let classModals = document.querySelectorAll('[data-selector="classlist-modal"]');

                        if (classModals.length > 0) {

                            classModals.forEach((modalElement) => {
                                let backToButton = modalElement.querySelector('[data-selector="back-to-edit-form"]');

                                if (backToButton !== null) {
                                    backToButton.setAttribute('data-bs-target', ('#event-modal-' + event.jsonKey));
                                }
                            });
                        }
                    }
                }
            });

            if (events !== "null" && events !== null) {
                events = JSON.parse(events);

                Object.keys(events).forEach((key) => {
                    window.teacherCalendar.addEvent({
                        id: 'event-' + key,
                        allDay: false,
                        title: events[key].title,
                        start: events[key].start,
                        end: events[key].end,
                        backgroundColor: events[key].backgroundColor,
                        borderColor: events[key].backgroundColor,
                        extendedProps: {
                            type: 'event',
                            jsonKey: key,
                            content: events[key].content,
                        },
                    });
                });
            }

            if (meetings !== "null" && meetings !== null) {
                meetings = JSON.parse(meetings);

                Object.keys(meetings).forEach((key) => {
                    window.teacherCalendar.addEvent({
                        id: 'meeting-' + meetings[key].uid,
                        allDay: false,
                        title: meetings[key].title,
                        start: meetings[key].start,
                        end: meetings[key].end,
                        extendedProps: {
                            type: 'meeting',
                            jsonKey: meetings[key].uid,
                            content: meetings[key].content,
                        },
                    });
                });
            }

            window.teacherCalendar.render();
        }

    }
}