import { Controller } from "stimulus";

export default class extends Controller {
	static targets = ["prev", "next"];
	connect() {
		this.prevTarget.classList.add("d-none");
		this.nextTarget.classList.add("d-none");
	}
}
