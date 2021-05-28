import { Controller } from "stimulus";
import { useDebounce } from "stimulus-use";

export default class extends Controller {
	static values = {
		url: String,
		objetId: Number,
		adherentId: Number,
		adherentNom: String,
		adherentPrenom: String,
	};
	static targets = [
		"selectedAdh",
		"selectedObj",
		"objet",
		"adherent",
		"adhResult",
		"objResult",
		"btn",
	];
	static debounces = ["search"];

	connect() {
		this.btnTarget.classList.add("d-none");
		useDebounce(this);
	}

	onSearchInput(event) {
		this.search(event.currentTarget.value, event.currentTarget.name);
	}

	async search(query, name) {
		if (name === "obj") {
			const params = new URLSearchParams({
				obj: query,
				previewobj: 1,
			});
			const response = await fetch(`${this.urlValue}?${params.toString()}`);
			this.objResultTarget.innerHTML = await response.text();
		} else if (name === "adh") {
			const params = new URLSearchParams({
				adh: query,
				previewadh: 1,
			});
			const response = await fetch(`${this.urlValue}?${params.toString()}`);
			this.adhResultTarget.innerHTML = await response.text();
			console.log("target => ", this.adhResultTarget.innerHTML);
		}
	}

	highlight(item, items) {
		const cell = item.parentElement;
		const row = cell.parentElement;
		items.map((targ) => {
			const cell = targ.parentElement;
			const row = cell.parentElement;
			row.classList.remove("text-success");
		});
		row.classList.add("text-success");
	}

	selectAdh(event) {
		const adherent = event.currentTarget;
		const adherents = this.selectedAdhTargets;
		const selAdherentId = event.currentTarget.dataset.adherentId;
		this.adherentTarget.value = selAdherentId;
		this.highlight(adherent, adherents);
		if (this.btnTarget) {
			this.btnTarget.classList.remove("d-none");
			this.btnTarget.textContent = `Modifier les infos de ${adherent.dataset.adherentPrenom} ${adherent.dataset.adherentNom}`;
		}
	}

	selectObj(event) {
		const objet = event.currentTarget;
		const objets = this.selectedObjTargets;
		const selObjetId = event.currentTarget.dataset.objetId;
		this.objetTarget.value = selObjetId;
		this.highlight(objet, objets);
	}
}
