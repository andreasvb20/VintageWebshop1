$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:1
        },
        1000:{
            items:1
        }
    }
})

//top sale owl carousel
$("#top-sale.owl-carousel").owlCarousel({
    loop: true,
    nav: true,
    dots: false,
    responsive:{
        0:{
            item: 1
        },
        600:{
            items: 3
        },
        1000:{
            items: 5
        }
    }
})