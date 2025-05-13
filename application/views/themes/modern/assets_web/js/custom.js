$(document).ready(function () {
	"use strict";

	/*------------------------------------
	 Datepicker
	 ------------------------------------- */
	$('.datepicker').datepicker({
		autoclose: true,
		startDate: new Date(),
		format: "yyyy-m-d"
	});
	
	var input = $('#time, #reservation_time').clockpicker({
        placement: 'bottom',
        align: 'right',
        autoclose: true,
        'default': 'now'
    });

	$('.auto-fit').theiaStickySidebar();

	$(".openMobileNav").click(function () {
		$(".mobileNav").addClass("active");
	});
	
	$(".closeMobileNav").click(function () {
		$(".mobileNav").removeClass("active");
	});
	
	$(".gotoLogin").on("click", function () {
        $('.nav-pills > .nav-item').find('#pills-login-tab').trigger('click');
    });
	
	$(".gotoReg").on("click", function () {
        $('.nav-pills > .nav-item').find('#pills-reg-tab').trigger('click');
    });

});

const nav = document.querySelector('.rightSidebar_inner');
let navTop = nav.offsetTop;

function fixedNav() {
	if (window.scrollY >= navTop) {
		nav.classList.add('full_height');
	} else {
		nav.classList.remove('full_height');
	}
}

window.addEventListener('scroll', fixedNav);
