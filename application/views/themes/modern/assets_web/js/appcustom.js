jQuery(document).on('ready', function ($) {
    "use strict";

    
    // Hide Loading Box (Preloader)
    $('.preloader').delay(300).fadeOut('slow');
    $('body').delay(300).css({'overflow':'visible'});
	
// Slider
var swiper = new Swiper(".mySwiper", {
  loop: true,
  spaceBetween: 10,
  slidesPerView: 2.5,
  centeredSlides: false,
});



$('.payment-radio').on('click', function () {
  $('.payment-radio').removeClass("active");
  $(this).addClass("active");
});


// Wishlist count animation
$('.btn-add').on('click', function (a) {
  a.preventDefault();
  $('.wishlist-count').addClass('wishlist-count__animation');
});

$('.wishlist-count').on('webkitAnimationEnd ', function (a) {
  $('.wishlist-count').delay(200).removeClass('wishlist-count__animation');
});

    /*-----------------------------
        MAIN SLIDER (OWL CAROUSEL)
    ------------------------------*/
   
    
    // Main Slider Text Animation
   
    
    $(".table_chart_inner a").click(function(){
        $(this).find("img").css({
            '-webkit-filter':'grayscale(100%)',
        });
        $(this).parents().siblings().find("img").css({
            '-webkit-filter':'none',
        });
    });
    
    /*------------------------------------
     Mobile Menu
     -------------------------------------- */

    $('#dismiss, .overlay').on('click', function () {
        $('.sidebar-nav').removeClass('active');
        $('.overlay').fadeOut();
    });

    $('#sidebarCollapse').on('click', function () {
        $('.sidebar-nav').addClass('active');
        $('.overlay').fadeIn();
    });
    
    /*------------------------------------
     Datepicker
     -------------------------------------- */
    $('.datepicker').datepicker({
        autoclose: true,
		format: "yyyy-m-d"
    });
    $('.datepickerreserve').datepicker({
        autoclose: true,
		format: "yyyy-m-d",
		startDate: '-0d',
    });
    
    /*------------------------------
    Clock Picker
    -------------------------------*/
    var input = $('#time, #reservation_time').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });
    
    $("#search-icon").click(function(){
        $(".search_box").toggle();
    });

}(jQuery));
function resclose(){
      $('#closenotice').modal('show');
  }
  $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
  var target = $(e.target).attr("id");
  $(".dineinpay").attr('name','card_type2');
  $(".pickuppay").attr('name','card_type1');
  if(target=="home-tab"){
	  $(".dineinpay").attr('name','card_type');
  }
  if(target=="profile-tab"){
	  $(".pickuppay").attr('name','card_type');
  }
 
});
 /*$('.nav-tabs button').on('show.bs.tab', function(){
	  var target = $(e.target).data("bs-target") 
  alert('New tab will be visible now!');
});*/