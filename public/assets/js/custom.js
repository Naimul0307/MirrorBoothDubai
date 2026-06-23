function fixSlickAccessibility(slider) {

    slider.find('.slick-slide[aria-hidden="true"]')
        .attr('tabindex', '-1')
        .find('a, button, input, select, textarea')
        .attr('tabindex', '-1');

    slider.find('.slick-slide[aria-hidden="false"]')
        .removeAttr('tabindex')
        .find('a, button, input, select, textarea')
        .removeAttr('tabindex');
}

$(document).ready(function () {

    const sliderOptions = {

        dots: false,
        infinite: true,
        speed: 500,

        slidesToShow: 4,
        slidesToScroll: 1,

        autoplay: true,
        autoplaySpeed: 2500,

        accessibility: true,

        prevArrow: `
            <button type="button" class="slick-prev" aria-label="Previous slide">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        `,

        nextArrow: `
            <button type="button" class="slick-next" aria-label="Next slide">
                <i class="fa-solid fa-angle-right"></i>
            </button>
        `,

        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    };

    $('.services-slider, .companies-slider, .reviews-slider')
        .on('init afterChange', function () {
            fixSlickAccessibility($(this));
        })
        .slick(sliderOptions);

});
