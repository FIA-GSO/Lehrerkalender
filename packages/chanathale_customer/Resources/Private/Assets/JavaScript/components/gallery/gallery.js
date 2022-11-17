import {Element} from "../base/element/element.js";
import Swiper, {EffectCards, EffectCoverflow, EffectCube, EffectFade, Pagination, Navigation} from "swiper";

/**
 * Gallery
 */
export class Gallery {

    /**
     * init
     */
     static init () {

        if (Element.GALLERY.length > 0) {
            Element.GALLERY.forEach(element => {
                Gallery.initElement(element);
            });
        }
    }

    /**
     * initElement
     * @param {HTMLElement} element
     */
    static initElement (element) {
        if (element !== null) {
            let effect = element.getAttribute('data-effect') || '';

            if (effect !== '') {
                let swiperElement = element.querySelector('[data-selector="swiper-element"]');

                if (swiperElement !== null) {
                    let swiper = new Swiper(swiperElement, {
                        modules: [Pagination, EffectCube, EffectFade, EffectCoverflow, EffectCards, Navigation],
                        effect: effect,
                        grabCursor: true,
                        centeredSlides: true,
                        slidesPerView: 'auto',
                        cubeEffect: {
                            shadow: true,
                            slideShadows: true,
                            shadowOffset: 20,
                            shadowScale: 0.94,
                        },
                        coverflowEffect: {
                            rotate: 50,
                            stretch: 0,
                            depth: 100,
                            modifier: 1,
                            slideShadows: true,
                        },
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        }
                    });

                    swiper.update();
                }
            }
        }
    }
}