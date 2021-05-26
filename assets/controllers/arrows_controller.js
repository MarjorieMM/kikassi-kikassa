import { Controller } from "stimulus";

export default class extends Controller {
	static targets = [
		"envoi",
		"firstPart",
		"secondPart",
		"firstTitle",
		"secondTitle",
		"prev",
		"next",
		"continue",
		"email",
		"emailObligatoire",
		"select",
	];
	connect() {
		this.prevTarget.classList.add("invisible");
		this.emailObligatoireTarget.classList.add("d-none");
		this.secondPartTarget.classList.add("d-none");
		this.secondTitleTarget.classList.add("d-none");
		this.envoiTarget.classList.add("d-none");
		this.continueTarget.classList.add("d-none");
	}
	nextPage(event) {
		event.preventDefault();
		this.prevTarget.classList.remove("invisible");
		this.nextTarget.classList.add("invisible");
		this.firstPartTarget.classList.add("d-none");
		this.firstTitleTarget.classList.add("d-none");
		this.secondPartTarget.classList.remove("d-none");
		this.secondTitleTarget.classList.remove("d-none");
	}

	toggleBiblio(event) {
		if (event.currentTarget.value === "oui") {
			this.envoiTarget.classList.add("d-none");
			this.nextTarget.classList.remove("d-none");
			if (this.emailTarget.value) {
				this.continueTarget.classList.remove("d-none");
				this.nextTarget.classList.add("d-none");
			} else {
				this.emailObligatoireTarget.classList.remove("d-none");
			}
		} else if (event.currentTarget.value === "non") {
			this.envoiTarget.classList.remove("d-none");
			this.continueTarget.classList.add("d-none");
			this.nextTarget.classList.add("d-none");
			this.emailObligatoireTarget.classList.add("d-none");
		}
	}
	emailChange(event) {
		this.emailObligatoireTarget.classList.add("d-none");
		if (this.selectTarget.value === "oui") {
			this.continueTarget.classList.remove("d-none");
			this.nextTarget.classList.add("d-none");
		}
		if (!event.currentTarget.value) {
			this.continueTarget.classList.add("d-none");
			this.emailObligatoireTarget.classList.remove("d-none");
			this.nextTarget.classList.remove("d-none");
		}
	}
}
