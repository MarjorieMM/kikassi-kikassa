import { Controller } from "stimulus";
import { useDebounce } from "stimulus-use";

export default class extends Controller {
	static values = {
		url: String,
		objetId: Number,
		adherentId: Number,
		adherentNom: String,
		adherentPrenom: String,
		adherentAdmin: String,
	};
	static targets = [
		"selectedAdh",
		"selectedObj",
		"objet",
		"adherent",
		"adherentAdmin",
		"adhResult",
		"objResult",
		"btn",
		"adminSelect",
		"adminStatus",
		"biblioMessage",
	];
	static debounces = ["search"];

	connect() {
		if (this.hasBtnTarget) {
			this.btnTarget.classList.add("d-none");
		}
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
		if (this.hasAdherentTarget) {
			this.adherentTarget.value = selAdherentId;
		}
		if (this.hasAdherentAdminTarget) {
			this.adherentAdminTarget.value = selAdherentId;
		}
		this.highlight(adherent, adherents);
		if (this.hasBtnTarget) {
			this.btnTarget.classList.remove("d-none");
			this.btnTarget.textContent = `Modifier les infos de ${adherent.dataset.adherentPrenom} ${adherent.dataset.adherentNom}`;
		}
		if (this.hasAdminSelectTarget) {
			if (adherent.dataset.adherentAdmin === "biblio") {
				this.adminSelectTarget.classList.add("d-none");
				this.biblioMessageTarget.classList.remove("d-none");
				this.biblioMessageTarget.innerHTML =
					"Adhérent non inscrit à la Bibliothèque des Objets";
			} else {
				this.adminStatusTarget.value = adherent.dataset.adherentAdmin;
				this.biblioMessageTarget.classList.add("d-none");
				this.adminSelectTarget.classList.remove("d-none");
			}
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
