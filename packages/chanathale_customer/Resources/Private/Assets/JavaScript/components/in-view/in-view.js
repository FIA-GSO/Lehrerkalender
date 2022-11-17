import LazyLoad from "vanilla-lazyload";

const THRESHOLD_IN_PX = 60;
const LAZY_FUNCTIONS = {
    testLazyloadingDiv: function (element) {
        //@todo show loading banner
        //(async () => {
        //const {testXhr} = await import(/* webpackChunkName: "test-xhr" */ "../test/test-xhr");
        //testXhr();
        //})();
        //@todo remove loading banner
    },
    gallery: function (element) {
        (async () => {
            let {Gallery} = await import(/* webpackChunkName: 'js.gallery' */ '../gallery/gallery.js');
            Gallery.initElement(element);
        })();
    },
    pupilform: function (element) {
        (async () => {
            let {PupilForm} = await import(/* webpackChunkName: 'js.gallery' */ '../pupil-form/pupil-form.js');
            PupilForm.initElement(element);
        })();
    },
    classroomform: function (element) {
        (async () => {
            let {ClassroomForm} = await import(/* webpackChunkName: 'js.gallery' */ '../classroom-form/classroom-form.js');
            ClassroomForm.initElement(element);
        })();
    }
};

let instance = null;

/**
 * InView
 */
export class InView {

    static init() {
        if (instance === null) {
            instance = new LazyLoad({
                elements_selector: ".lazy-element",
                threshold: THRESHOLD_IN_PX,
                unobserve_entered: true,
                callback_enter: InView.executeLazyFunction
            });
        }
    }

    static update() {
        if (instance === null) {
            InView.init();
        } else {
            instance.update();
        }
    }

    static executeLazyFunction(element) {
        let lazyFunctionName = element.getAttribute("data-lazy-function");
        if (LAZY_FUNCTIONS[lazyFunctionName] !== undefined) {
            let lazyFunction = LAZY_FUNCTIONS[lazyFunctionName];
            if (!lazyFunction) return;
            lazyFunction(element);
        }
    }
}