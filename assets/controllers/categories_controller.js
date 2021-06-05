import { Controller } from "stimulus";

export default class extends Controller {
	static targets = ["hiddenSsCat", "foundSsCat"];
	static values = {
		url: String,
	};

	selCategorie(event) {
		this.search(event.currentTarget.value);
	}

	selSsCategorie(event) {
		this.hiddenSsCatTarget.value = event.currentTarget.value;
	}

	async search(query) {
		const params = new URLSearchParams({
			cat: query,
			preview: 1,
		});
		const response = await fetch(`${this.urlValue}?${params.toString()}`);
		this.foundSsCatTarget.innerHTML = await response.text();
	}
}
