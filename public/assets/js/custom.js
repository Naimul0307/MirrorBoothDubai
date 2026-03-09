$(document).ready(function(){
    $('.services-slider').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        prevArrow: "<a href='javascript:void(0);' type='button' class='slick-prev pull-left'><i class='fa fa-angle-left' aria-hidden='true'></i></a>",
        nextArrow: "<a href='javascript:void(0);' type='button' class='slick-next pull-right'><i class='fa fa-angle-right' aria-hidden='true'></i></a>",
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});
