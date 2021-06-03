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
		adherentFourmi: String,
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
		"adminForm",
		"adminStatus",
		"fourmiForm",
		"modifMessage",
		"fourmiStatus",
		"btnFourmi",
		"adminStatus",
		"envoi",
		"proprio",
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
	toggleProprio(event) {
		if (event.currentTarget.value === "adherent") {
			this.proprioTarget.classList.remove("d-none");
		} else {
			this.proprioTarget.classList.add("d-none");
		}
	}

	selectAdh(event) {
		const adherent = event.currentTarget;
		const adherents = this.selectedAdhTargets;
		const selAdherentId = event.currentTarget.dataset.adherentId;
		if (this.hasAdherentTarget) {
			this.adherentTarget.value = selAdherentId;
		}
		if (this.hasFourmiFormTarget) {
			this.fourmiFormTarget.classList.remove("d-none");
			this.modifMessageTarget.innerHTML = `Modifier le statut fourmi de ${adherent.dataset.adherentPrenom} ${adherent.dataset.adherentNom}`;
			this.fourmiStatusTarget.value = adherent.dataset.adherentFourmi;
			this.btnFourmiTarget.classList.remove("d-none");
		}
		this.highlight(adherent, adherents);
		if (this.hasBtnTarget) {
			this.btnTarget.classList.remove("d-none");
			this.btnTarget.textContent = `Modifier les infos de ${adherent.dataset.adherentPrenom} ${adherent.dataset.adherentNom}`;
		}
		if (this.hasAdminFormTarget) {
			this.adminStatusTarget.value = adherent.dataset.adherentAdmin;
			this.modifMessageTarget.innerHTML = `Modifier le statut Administrateur de ${adherent.dataset.adherentPrenom} ${adherent.dataset.adherentNom}`;
			this.adminFormTarget.classList.remove("d-none");
			this.envoiTarget.classList.remove("d-none");
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
