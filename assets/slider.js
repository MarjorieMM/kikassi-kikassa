import Splide from "@splidejs/splide";
document.addEventListener("DOMContentLoaded", function () {
  var secondarySlider = new Splide("#secondary-slider", {
    fixedWidth: 100,
    height: 60,
    gap: 10,
    cover: true,
    isNavigation: true,
    focus: "center",
    breakpoints: {
      600: {
        fixedWidth: 66,
        height: 40,
      },
    },
  }).mount();

  var primarySlider = new Splide("#primary-slider", {
    type: "fade",
    heightRatio: 0.5,
    pagination: false,
    arrows: false,
    cover: true,
  });

  primarySlider.sync(secondarySlider).mount();
});

document.addEventListener( 'DOMContentLoaded', function () {
	new Splide( '#third-slider', {
    fixedWidth : 200,
		height     : 200,
		gap        : 10,
		rewind     : true,
		cover      : true,
		pagination : false,
	} ).mount();
} );
	
			
document.addEventListener( 'DOMContentLoaded', function () {
	new Splide( '#fourth-slider', {
		fixedWidth : 200,
		height     : 200,
		gap        : 10,
		rewind     : true,
		cover      : true,
		pagination : false,
	} ).mount();
} );