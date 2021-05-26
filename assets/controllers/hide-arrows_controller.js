import { Controller } from "stimulus";

export default class extends Controller {
	static targets = ["prev", "next"];
	connect() {
		console.log(this.prevTarget);
		this.prevTarget.classList.add("invisible", "l-padding");
		this.nextTarget.classList.add("d-none");
	}
}
