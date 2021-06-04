import { Controller } from "stimulus";

export default class extends Controller {
	static targets = ["selCat", "selSousCat", "hiddenSousCat"];
	// static values = {
	// 	sousCategories: Object,
	// };
	connect() {
		console.log(this.selCatTarget);
		console.log(this.selSousCat);
	}

	selCategorie(event) {
		console.log("val", this.selCatTarget.value);
		console.log(event);
	}
}
