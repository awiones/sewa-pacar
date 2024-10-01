$(document).ready(function() {
    // Preload images to ensure no delays during transition
    function preloadImages() {
      const images = [
        'img/banner1.jpg',
        'img/banner2.jpg',
        'img/banner3.jpg'
      ];
  
      images.forEach(function(image) {
        $('<img/>')[0].src = image;  // Preload images
      });
    }
  
    preloadImages();
  
    // Auto-play the carousel every 5 seconds
    $('#carouselExampleCaptions').carousel({
      interval: 5000
    });
    
    // Pause carousel on hover
    $('.carousel').hover(
      function() {
        $(this).carousel('pause');
      },
      function() {
        $(this).carousel('cycle');
      }
    );
  });
  
  