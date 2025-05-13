 $(".pos-sidebar_toggler").click(function () {
      $(".pos-sidebar").toggleClass("show");
    });
    $(".close-btn").click(function () {
      $(".pos-sidebar").removeClass("show");
    });


  



    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function () {
      $('.js-basic-single').select2();
    });
