import { Controller } from "stimulus";

export default class extends Controller {
	static targets = [
		"envoi",
		"firstPart",
		"secondPart",
		"firstTitle",
		"secondTitle",
	];
	connect() {
		this.secondPartTarget.classList.add("d-none");
		this.secondTitleTarget.classList.add("d-none");
		this.envoiTarget.style.display = "none";
	}

	continue(event) {
		event.preventDefault();
		this.firstPartTarget.classList.add("d-none");
		this.firstTitleTarget.classList.add("d-none");
		this.secondPartTarget.classList.add("d-block");
		this.secondTitleTarget.classList.add("d-block");
	}
	envoi(event) {
		event.preventDefault();
		console.log("envoyé");
	}

	toggleEnvoi(event) {
		if (event.currentTarget.value === "oui") {
			this.envoiTarget.style.display = "block";
		} else {
			this.envoiTarget.style.display = "none";
		}
	}
}

// function replaceClass(el, oldClass, newClass) {
// 	const element = $(`.${el}`);
// 	if (element.hasClass(oldClass)) {
// 		element.removeClass(oldClass);
// 	}
// 	element.addClass(newClass);
// }

// $(".prev").on("click", function (e) {
// 	e.preventDefault();
// 	replaceClass("first-part", "d-none", "d-block");
// 	replaceClass("second-part", "d-block", "d-none");
// 	replaceClass("prev", "visible", "invisible");
// 	$(".next").removeClass("d-none");
// 	replaceClass("next", "invisible", "visible");
// 	replaceClass("envoi", "d-block", "d-none");
// 	replaceClass("continue", "d-block", "d-none");
// });

// $(".next").on("click", function (e) {
// 	e.preventDefault();
// 	replaceClass("first-part", "d-block", "d-none");
// 	replaceClass("second-part", "d-none", "d-block");
// 	replaceClass("prev", "invisible", "visible");
// 	replaceClass("next", "visible", "invisible");
// 	if ($(".biblio-select select option:selected").val() == "oui") {
// 		replaceClass("continue", "d-none", "d-block");
// 		replaceClass("next", "invisible", "d-none");
// 	} else if ($(".biblio-select select option:selected").val() == "non") {
// 		replaceClass("envoi", "d-none", "d-block");
// 		replaceClass("next", "invisible", "d-none");
// 	}
// });
