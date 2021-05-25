import { Controller } from "stimulus";
// import $ from 'jquery';
var $ = require("jquery");
// require("bootstrap");

export default class extends Controller {
	connect() {
		$(".leftArrow").on("click", function () {
			console.log("works");
			const leftPos = $(".show").scrollLeft();
			$(".show").animate({ scrollLeft: leftPos - 300 }, 100);
		});

		$(".rightArrow").on("click", function () {
			const leftPos = $(".show").scrollLeft();
			$(".show").scrollLeft(leftPos + 150);
		});

		$(".modalbtn").on("click", function () {
			console.log("modal");
			$("#horairesModal").modal("show");
		});
		$(".backButton").on("click", function () {
			console.log("back");
			window.history.back();
		});

		function replaceClass(el, oldClass, newClass) {
			const element = $(`.${el}`);
			if (element.hasClass(oldClass)) {
				element.removeClass(oldClass);
			}
			element.addClass(newClass);
		}

		$(".prev").on("click", function (e) {
			e.preventDefault();
			replaceClass("first-part", "d-none", "d-block");
			replaceClass("second-part", "d-block", "d-none");
			replaceClass("prev", "visible", "invisible");
			$(".next").removeClass("d-none");
			replaceClass("next", "invisible", "visible");
			replaceClass("envoi", "d-block", "d-none");
			replaceClass("continue", "d-block", "d-none");
		});

		$(".next").on("click", function (e) {
			e.preventDefault();
			replaceClass("first-part", "d-block", "d-none");
			replaceClass("second-part", "d-none", "d-block");
			replaceClass("prev", "invisible", "visible");
			replaceClass("next", "visible", "invisible");
			if ($(".biblio-select select option:selected").val() == "oui") {
				replaceClass("continue", "d-none", "d-block");
				replaceClass("next", "invisible", "d-none");
			} else if ($(".biblio-select select option:selected").val() == "non") {
				replaceClass("envoi", "d-none", "d-block");
				replaceClass("next", "invisible", "d-none");
			}
		});

		$(".biblio-select select").on("change", function () {
			if ($(".biblio-select select option:selected").val() == "oui") {
				replaceClass("continue", "d-none", "d-block");
				replaceClass("envoi", "d-block", "d-none");
				replaceClass("next", "invisible", "d-none");

				if ($("#adhesion_form_email").val().length < 1) {
					replaceClass("email-obligatoire", "d-none", "d-block");
					$("#adhesion_form_email")
						.addClass("is-invalid")
						.attr("required", "true");
					replaceClass("continue", "d-block", "invisible");

					$("#adhesion_form_email").on("change", function () {
						if ($("#adhesion_form_email").val().length > 1) {
							$("#adhesion_form_email").removeClass("is-invalid");
							replaceClass("email-obligatoire", "d-block", "d-none");
							replaceClass("continue", "invisible", "d-block");
						} else if ($("#adhesion_form_email").val().length < 1) {
							replaceClass("continue", "d-block", "invisible");
							$("#adhesion_form_email")
								.addClass("is-invalid")
								.attr("required", "true");
							replaceClass("email-obligatoire", "d-none", "d-block");
						}
					});
				}
			} else if ($(".biblio-select select option:selected").val() == "non") {
				replaceClass("envoi", "d-none", "d-block");
				replaceClass("continue", "d-block", "d-none");
				replaceClass("next", "invisible", "d-none");
				replaceClass("email-obligatoire", "d-block", "d-none");
				$("#adhesion_form_email")
					.removeClass("is-invalid")
					.attr("required", false);
			} else {
				replaceClass("next", "d-none", "invisible");
				replaceClass("envoi", "d-block", "d-none");
				replaceClass("continue", "d-block", "d-none");
				replaceClass("continue", "padding-footer", "s-padding");
			}
		});

		$(".proprio-select select").on("change", function () {
			if ($(".proprio-select select option:selected").val() == "assoc") {
				replaceClass("search-adherent", "d-block", "d-none");
			} else {
				replaceClass("search-adherent", "d-none", "d-block");
			}
		});

		function selectOption(selected, res, url) {
			$.ajax({
				data: { data: selected },
				dataType: "json",
				type: "POST",
				url: url,
			})
				.done(function (json) {
					res(json);
				})
				.fail(function (jqXHR, textStatus, errorThrown) {
					console.log(textStatus + ": " + errorThrown);
				});
		}

		$("#search-adherent").on("click", function (e) {
			e.preventDefault();
			$("#search-results-adherent").empty();
			const searched = $(".search-adherent").find("#search_form_nom").val();
			const url = "new/adh";
			const res = (json) => {
				if (json.adherent.length > 0 && json.admin.length > 0) {
					appendRes(json.adherent);
					appendRes(json.admin);
				} else if (json.admin.length > 0) {
					appendRes(json.admin);
				} else if (json.adherent.length > 0) {
					appendRes(json.adherent);
				} else {
					$("#search-results-adherent").append(
						"<tr><td class='text-center' colspan='4'>Pas d'adhérent ou de super-admin trouvé</td></tr>"
					);
				}
				function appendRes(item) {
					console.log(item);
					item.map((el) =>
						$("#search-results-adherent")
							.append(`<tr><td class='text-center'>${el.nom}</td><td class='text-center'>${el.prenom}</td><td class='text-center'>${el.email}</td><td class="text-center">
        <div class="form-check adh-check">
         <input name="adherent-select" class="text-center form-check-input" type="radio" value=${el.id}>
      </div>
     </td></tr>`)
					);
				}
			};

			selectOption(searched, res, url);
		});

		$("#select-adherent").on("click", function (e) {
			e.preventDefault();
			$("#selected-adherent").empty();
			const selected = $('input:radio[name="adherent-select"]:checked').val();
			$("#hidden-adh").val($.trim(selected));
			$("#hidden-btn").empty();
			const res = (json) => {
				console.log(json);
				if (
					json.param === "objets" ||
					json.param === "adherents" ||
					json.param === "emprunts" ||
					json.param === "adherent-reinscription"
				) {
					function appendSel(item, titre) {
						$("#selected-adherent").append(
							`<div class="row font-raleway form-control select-height width-auto ml-1" ><p class="p-2"> ${titre} : ${$.trim(
								item.prenom
							)} ${$.trim(item.nom)} </p></div>`
						);

						$("#hidden-btn").append(
							`<button type="submit" class='btn btn-danger p-3'>Modifier ${$.trim(
								item.prenom
							)} ${$.trim(item.nom)}</button>`
						);
					}
					json.adherent
						? appendSel(json.adherent, "Adhérent")
						: appendSel(json.admin, "Super-admin");
				} else if (json.param === "adherent-changement-fourmi") {
					const fourmis = {
						verte: "Fourmi Verte",
						bleue: "Fourmi Bleue",
						dorée: "Fourmi Dorée",
					};
					const selFourmi = json.biblio.categorie_fourmi;

					$("#fourmi_form_categorie_fourmi").empty();
					$(".modif-message").append(
						`Modifier le Statut Fourmi de ${json.adherent.prenom} ${json.adherent.nom}`
					);
					Object.keys(fourmis).forEach((fourmi) => {
						replaceClass("fourmi-form", "d-none", "d-block");
						$("#fourmi_form_categorie_fourmi").append(
							`<option value=${fourmi} ${
								fourmi === selFourmi ? "selected" : ""
							} >${fourmis[fourmi]}</option>`
						);
					});
					replaceClass("next", "invisible", "d-none");
				} else if (json.param === "adherent-passage-admin") {
					if (json.adherent == null) {
						$(".admin-warning").append(
							"<div class='alert-danger text-center p-2'>Ne fonctionne pas avec les utilisateurs ayant des droits de'Super Admin'</div>"
						);
					} else {
						$(".admin-warning").empty();
						json.roles.splice(json.roles.indexOf("ROLE_USER"), 1);

						replaceClass("admin-select", "d-none", "d-block");
						$("#admin-form").append(
							`<option value="ROLE_ADMIN" ${
								json.roles.length > 0 ? "selected" : ""
							}>Oui</option><option value="ROLE_USER"  ${
								json.roles.length === 0 ? "selected" : ""
							}>Non</option>`
						);
					}
				}
			};

			const url = "new/sel";
			selectOption(selected, res, url);
		});

		$("#search-objet").on("click", function (e) {
			e.preventDefault();
			$("#search-results-objet").empty();
			const searched = $(".search-objet").find("#search_form_nom").val();
			const url = "new/obj";
			const res = (json) => {
				if (json.length > 0) {
					$.each(json, function (index, value) {
						$(
							"#search-results-objet"
						).append(`<tr><td class='text-center'>${value.denomination}</td><td class='text-center'>${value.marque}</td><td class='text-center'>${value.statut}</td>
          <td class='text-center'>xxx</td>
          <td class='text-center'>${value.emprunts.map((val) =>
						new Date(val.date_fin) > new Date()
							? `du ${new Date(
									val.date_debut
							  ).toLocaleDateString()} au ${new Date(
									val.date_fin
							  ).toLocaleDateString()} <br>`
							: "Pas d'emprunt prévu"
					)}</td>
           <td class="text-center">
            <div class="form-check obj-check">
              <input name="objet-select" class="text-center form-check-input" type="radio" value=${
								value.id
							}>
            </div>
          </td></tr>`);
					});
				} else {
					$("#search-results-objet").append(
						"<tr><td class='text-center' colspan='4'>Pas d'objet trouvé</td></tr>"
					);
				}
			};

			selectOption(searched, res, url);
		});

		$("#select-objet").on("click", function (e) {
			e.preventDefault();
			$("#selected-objet").empty();
			const selected = $('input:radio[name="objet-select"]:checked').val();
			$("#hidden-obj").val($.trim(selected));

			const res = (json) => {
				console.log(json);
				$("#selected-objet").append(
					`<div class="row font-raleway form-control select-height width-auto ml-1" ><p class="p-2">Objet : ${$.trim(
						json.denomination
					)} ${$.trim(json.marque)} </p></div>`
				);
				$("#reserved-objet").append(
					`<div class="row font-raleway text-center">L'objet ${$.trim(
						json.denomination
					)} ${$.trim(json.marque)} est déjà réservé du au </div>`
				);
			};

			const url = "new/selobj";
			selectOption(selected, res, url);
		});

		$(".categorie-select select").on("change", function () {
			$("#ss-cat-select").empty();
			const selCat = $(".categorie-select select option:selected").val();
			const res = (json) =>
				$.each(json, function (index, val) {
					$("#ss-cat-select").append(
						`<option value=${val.id}>${val.nom_ss_categorie}</option>`
					);
				});

			const url = "new/cat";
			selectOption(selCat, res, url);
		});
		$("#ss-cat-select").on("change", function () {
			const selectedCat = $("#ss-cat-select option:selected").val();
			$("#hidden-cat").val(selectedCat);
		});

		$(".biblio-select-edit select").on("change", function () {
			if ($(".biblio-select-edit select option:selected").val() == "oui") {
				replaceClass("biblio-part", "d-none", "d-block");
			} else if (
				$(".biblio-select-edit select option:selected").val() == "non"
			) {
				replaceClass("biblio-part", "d-block", "d-none");
			}
		});

		$(".proprio-select select").on("change", function () {
			if ($(".proprio-select select option:selected").val() === "adherent") {
				replaceClass("search-module", "d-none", "d-block");
			} else if (
				$(".proprio-select select option:selected").val() === "association"
			) {
				replaceClass("search-module", "d-block", "d-none");
			}
		});

		$("#modal-paiement").on("show.bs.modal", function (event) {
			console.log("modal");
			const button = $(event.relatedTarget);
			const objet = button.data("objet");
			const emprunt_id = button.data("emprunt-id");
			const emprunt = button.data("emprunt");
			const emprunt_regle = button.data("emprunt-regle");
			const penalites = button.data("penalites");
			const depotRajoute = button.data("depot");
			const modal = $(this);
			const depot =
				depotRajoute > 0
					? `<div><p class="text-warning font-raleway text-center"><i class="fas fa-exclamation-triangle"></i>L'adhérent a remis un chèque de caution pour cet emprunt</p></div>`
					: "";
			const ardoise = !emprunt_regle
				? `<div><p class="text-danger font-raleway text-center">L'adhérent doit la somme de ${emprunt} € pour cet emprunt</p></div>`
				: "";
			const penalitesDues =
				penalites > 0
					? `<div><p class="text-danger font-raleway text-center">L'adhérent doit la somme de ${penalites} € <br> de pénalités de retard pour cet emprunt</p></div>`
					: "";
			const paiementEmprunt = !emprunt_regle
				? `
 <label class="mr-5 py-3">Le paiement de l'emprunt ( ${emprunt} € ) a-t'il été effectué ce jour ?</label>
      <div class="custom-control custom-radio custom-control-inline">
      <input type="radio" id="paiement-radio1" name="paiement" class="custom-control-input mb-4" value=true required>
      <label class="custom-control-label" for="paiement-radio1">Oui</label>
    </div>
    <div class="custom-control custom-radio custom-control-inline">
      <input type="radio" id="paiement-radio2" name="paiement" class="custom-control-input mb-4" value=false required>
      <label class="custom-control-label" for="paiement-radio2">Non</label>
    </div>`
				: "";
			const paiementPenalites =
				penalites > 0
					? `
    <label class="mr-5">Le paiement des pénalités ( ${penalites} € ) a-t'il été effectué ce jour ?</label>
         <div class="custom-control custom-radio custom-control-inline">
         <input type="radio" id="penalites-radio1" name="penalites" class="custom-control-input mb-4" value=true  required>
         <label class="custom-control-label" for="penalites-radio1">Oui</label>
       </div>
       <div class="custom-control custom-radio custom-control-inline">
         <input type="radio" id="penalites-radio2" name="penalites" class="custom-control-input mb-4" value=false  required>
         <label class="custom-control-label" for="penalites-radio2">Non</label>
       </div>`
					: "";
			modal
				.find(".modal-title")
				.text(
					`Validation du retour de l'objet ${objet} de l'emprunt n°${emprunt_id}`
				);
			modal.find(".modal-body").html(`${ardoise} ${depot} ${penalitesDues}
     <div class="font-raleway">
      <div class="form-group">
      <input type="hidden" name="emprunt_id" value="${emprunt_id}">
        <label for="statut-objet">L' état de l'objet permet-il sa remise en ligne ? :</label>
        <select class="form-control select-height mb-4" id="statut-objet" name="dispo" required>
          <option value="">Choisir...</option>
          <option value='disponible'>Oui</option>
          <option value='en maintenance'>Non</option>
        </select>
      </div>
      <div class="form-group">
        <label for="obs-objet">Veuillez entrer une observation sur l'état de l'objet (celle-ci sera reprise sur le bon de retour)</label>
        <textarea name="obs" type="text" class="form-control mb-4" id="obs-objet" required></textarea>
      </div> ${paiementEmprunt} ${paiementPenalites}
      </div>`);
			modal
				.find("#button-submit")
				.html(
					`<button type="submit" class="btn btn-success btn-md btn-block" ><div class="font-raleway-small btn-text">Valider</div></button>`
				);
		});
	}
}
