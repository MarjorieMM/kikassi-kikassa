import { Controller } from "stimulus";

export default class extends Controller {
	static values = {
		url: String,
	};
	static targets = ["result"];

	onSearchInput(event) {
		this.search(event.currentTarget.value);
	}
	async search(query) {
		console.log(this.urlValue);
		const params = new URLSearchParams({
			q: query,
			preview: 1,
		});
		const response = await fetch(`${this.urlValue}?${params.toString()}`);
		this.resultTarget.innerHTML = await response.text();
	}
}
