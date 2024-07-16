function openMenu(){
   document.getElementById("nav-col-links").classList.toggle("nav-col-links");
}

// Slider JS

   // Binding of init has to be before intialization!
   $('.slick-slider').on('init', (event, slick, currentSlide) => {
      let slideIndex = slick.currentSlide;
      let slidesLength = slick.slideCount;
    })
    
    // Initialise.
    $('.slick-slider').slick({
      slidesToShow: 1,
      dots: true,
      //centerMode: true,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    })