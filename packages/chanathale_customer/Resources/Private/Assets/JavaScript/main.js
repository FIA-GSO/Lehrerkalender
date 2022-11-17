import LazyLoad from "vanilla-lazyload";
import {InView} from "./components/in-view/in-view.js";
import Offcanvas from "bootstrap/js/dist/offcanvas.js";
import Dropdown from "bootstrap/js/dist/dropdown.js";
import Modal from "bootstrap/js/dist/modal.js";
import Collapse from "bootstrap/js/dist/collapse.js"
import Tab from "bootstrap/js/dist/tab.js";
import {Calendar} from "@fullcalendar/core";
import {Navigation} from "./components/navigation/navigation.js";
import {GradeForm} from "./components/grade-form/grade-form.js";
import {PerformanceTool} from "./components/performance-tool/performance-tool.js";
import {Teachercalendar} from "./components/teachercalendar/teachercalendar.js";
import {EventForm} from "./components/event-form/event-form.js";
import {ClassroomForm} from "./components/classroom-form/classroom-form.js";
import {MeetingForm} from "./components/meeting-form/meeting-form.js";
import {ChModal} from "./components/ch-modal/ch-modal.js";
import {PupilDelete} from "./components/pupil-delete/pupil-delete.js";

function ready() {
    if (window.lazyload) {
        window.lazyload = new LazyLoad();
    }

    // InView
    InView.init();

    // Navigation
    Navigation.init();

    // GradeForm
    GradeForm.init();

    // PerformanceTool
    PerformanceTool.init();

    // Teachercalendar
    Teachercalendar.init();

    // EventForm
    EventForm.init();

    // ClassroomForm
    ClassroomForm.init();

    // MeetingForm
    MeetingForm.init();

    // ChModal
    ChModal.initElement();

    // PupilDelete
    PupilDelete.init();
}

if (window.addEventListener) {
    window.addEventListener("load", ready, false);
} else if (window.attachEvent) {
    window.attachEvent("onload", ready);
} else {
    window.onload = ready;
}