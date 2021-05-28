import { Controller } from "stimulus";
import { useDebounce } from "stimulus-use";

export default class extends Controller {
	static values = {
		url: String,
		objetId: Number,
	};
	static targets = ["result", "selected", "objet"];
	static debounces = ["search"];

	connect() {
		useDebounce(this);
	}

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
	select(event) {
		// console.log(event.currentTarget.dataset.objetId);
		const selectedId = event.currentTarget.dataset.objetId;
		this.objetTarget.innerHTML = selectedId;

		const cell = event.currentTarget.parentElement;
		const row = cell.parentElement;
		this.selectedTargets.map((targ) => {
			const cell = targ.parentElement;
			const row = cell.parentElement;
			row.classList.remove("text-success");
		});

		row.classList.add("text-success");
	}
}
