import { Controller } from 'stimulus';
// import $ from 'jquery';
var $  = require( 'jquery' );
require('bootstrap');

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
  connect() {

    $(".leftArrow").on('click', function () {
      console.log('works')
      const leftPos = $('.show').scrollLeft();
      $(".show").animate({ scrollLeft: leftPos - 300 }, 100);
    });
          
    $(".rightArrow").on('click', function () {
      const leftPos = $('.show').scrollLeft();
      $('.show').scrollLeft(leftPos + 150);
    });
      
    $('.modalbtn').on('click', function () {
      console.log("modal")
      $('#horairesModal').modal('show')
    });
    $('.backButton').on('click', function () {
      console.log('back');
      window.history.back();
    })

    function replaceClass(el, oldClass, newClass) {
      const element = $(`.${el}`);
      if (element.hasClass(oldClass)) {
        element.removeClass(oldClass)
      }
      element.addClass(newClass)
    }
      

    $('.prev').on('click', function (e) {
      e.preventDefault();
      replaceClass("first-part", "d-none", "d-block")
      replaceClass("second-part", "d-block", "d-none")
      replaceClass("prev", "visible", "invisible")
      $('.next').removeClass('d-none')
      replaceClass("next", "invisible", "visible")
      replaceClass("envoi", 'd-block', 'd-none')
      replaceClass("continue", 'd-block', 'd-none')
    })

    $('.next').on('click', function (e) {
      e.preventDefault();
      replaceClass("first-part", "d-block", "d-none")
      replaceClass("second-part", "d-none", "d-block")
      replaceClass("prev", "invisible", "visible")
      replaceClass("next", 'visible', 'invisible')
      if ($('.biblio-select select option:selected').val() == "oui") {
        replaceClass("continue", 'd-none', 'd-block')
        replaceClass("next", 'invisible', 'd-none')
      } else if ($('.biblio-select select option:selected').val() == "non") {
        replaceClass("envoi", 'd-none', 'd-block')
        replaceClass("next", 'invisible', 'd-none')
      }
    })
    
   
    $('.biblio-select select').on('change', function () {
      if ($('.biblio-select select option:selected').val() == "oui") {
        replaceClass("continue", 'd-none', 'd-block')
        replaceClass("envoi", 'd-block', 'd-none')
        replaceClass("next", 'invisible', 'd-none')
          
        if ($('#adhesion_form_email').val().length < 1) {
          replaceClass("email-obligatoire", 'd-none', 'd-block')
          $('#adhesion_form_email').addClass('is-invalid').attr("required", "true")
          replaceClass("continue", 'd-block', 'invisible')

          $('#adhesion_form_email').on('change', function () {
            if ($('#adhesion_form_email').val().length > 1) {
              $('#adhesion_form_email').removeClass('is-invalid')
              replaceClass("email-obligatoire", 'd-block', 'd-none')
              replaceClass("continue", 'invisible', 'd-block')
            } else if ($('#adhesion_form_email').val().length < 1) {
              replaceClass("continue", 'd-block', 'invisible')
              $('#adhesion_form_email').addClass('is-invalid').attr("required", "true")
              replaceClass("email-obligatoire", 'd-none', 'd-block')

            }
          })
        }
            
      } else if ($('.biblio-select select option:selected').val() == "non") {
        replaceClass("envoi", 'd-none', 'd-block')
        replaceClass("continue", 'd-block', 'd-none')
        replaceClass("next", 'invisible', 'd-none')
        replaceClass("email-obligatoire", 'd-block', 'd-none')
        $('#adhesion_form_email').removeClass('is-invalid').attr("required", false)
            
      } else {
        replaceClass("next", 'd-none', 'invisible')
        replaceClass("envoi", 'd-block', 'd-none')
        replaceClass("continue", 'd-block', 'd-none')
        replaceClass("continue", 'padding-footer', 's-padding')

          
      }
    })
  
    $('.proprio-select select').on('change', function () {
      if ($('.proprio-select select option:selected').val() == "assoc") {
        replaceClass("search-adherent", 'd-block', 'd-none')
      } else {
        replaceClass("search-adherent", 'd-none', 'd-block')

      }
    })


    function selectOption(selected, res, url) {
      $.ajax({
       
        data: { 'data': selected },
        dataType: 'json',
        type: 'POST',
        url: url
      }).done(function (json) {
        // console.log(json)
        res(json)
      }).fail(function (jqXHR, textStatus, errorThrown) {
     
        console.log(textStatus + ': ' + errorThrown);
      });
    }

    $('#search-adherent').on('click', function (e) {
      e.preventDefault()
      $('#search-results-adherent').empty();
      const searched = $('.search-adherent').find('#search_form_nom').val();
      const url = 'new/adh'
      const res = (json) => {
        if (json.adherent.length > 0 && json.admin.length > 0) {
          appendRes(json.adherent)
          appendRes(json.admin)
        } else if (json.admin.length > 0) {
          appendRes(json.admin)
        } else if (json.adherent.length > 0) {
          appendRes(json.adherent)
        } else {
          $('#search-results-adherent').append("<tr><td class='text-center' colspan='4'>Pas d'adhérent ou de super-admin trouvé</td></tr>")
       }
        function appendRes(item) {
          item.map(el => $('#search-results-adherent').append(`<tr><td class='text-center'>${el.nom}</td><td class='text-center'>${el.prenom}</td><td class='text-center'>${el.email}</td><td class="text-center">
        <div class="form-check adh-check">
         <input name="adherent-select" class="text-center form-check-input" type="radio" value=${el.id}>
      </div>
     </td></tr>`))
        }
      }

      selectOption(searched, res, url)
    })
    
    $('#select-adherent').on('click', function (e) {
      e.preventDefault()
      $('#selected-adherent').empty()
        const selected = $('input:radio[name="adherent-select"]:checked').val()
      $('#hidden-adh').val($.trim(selected))
      $('#hidden-btn').empty()
      const res = (json) => {
        if (json.param === 'objets' || json.param === 'adherents' || json.param === 'emprunts' || json.param === 'adherent-reinscription') {
          function appendSel(item, titre) {
            $('#selected-adherent').append(`<div class="row font-raleway form-control select-height width-auto ml-1" ><p class="p-2"> ${titre} : ${$.trim(item.prenom)} ${$.trim(item.nom)} </p></div>`)
           
            $('#hidden-btn').append(`<button type="submit" class='btn btn-danger p-3'>Modifier ${$.trim(item.prenom)} ${$.trim(item.nom)}</button>`)
          
          }
          json.adherent ? appendSel(json.adherent, "Adhérent") : appendSel(json.admin, "Super-admin")
          
        } else if (json.param === "adherent-changement-fourmi") {
          const fourmis = { "verte": 'Fourmi Verte', "bleue": 'Fourmi Bleue', "dorée": 'Fourmi Dorée' }
          const selFourmi = json.biblio.categorie_fourmi
          
          $('#fourmi_form_categorie_fourmi').empty()
          $('.modif-message').append(`Modifier le Statut Fourmi de ${json.adherent.prenom} ${json.adherent.nom}`)
          Object.keys(fourmis).forEach(fourmi => {
            replaceClass('fourmi-form', 'd-none', 'd-block')
            $('#fourmi_form_categorie_fourmi').append(`<option value=${fourmi} ${fourmi === selFourmi ? 'selected' : ''} >${fourmis[fourmi]}</option>`)
          }
          )
          replaceClass('next', 'invisible', 'd-none')
        } else if (json.param === "adherent-passage-admin") {

          if (json.adherent == null) {
            $('.admin-warning').append("<div class='alert-danger text-center p-2'>Ne fonctionne pas avec les utilisateurs ayant des droits de'Super Admin'</div>")
            
          } else {
           $('.admin-warning').empty()
          json.roles.splice(json.roles.indexOf("ROLE_USER"), 1);
          
            replaceClass("admin-select", "d-none", "d-block")
          $('#admin-form').append(`<option value="ROLE_ADMIN" ${json.roles.length > 0 ? 'selected': '' }>Oui</option><option value="ROLE_USER"  ${json.roles.length === 0 ? 'selected': '' }>Non</option>`)

          }

         

        }
        

        }
       
      const url = 'new/sel';
      selectOption(selected, res, url);
    })

    $('#search-objet').on('click', function (e) {
      e.preventDefault()
      $('#search-results-objet').empty();
      const searched = $('.search-objet').find('#search_form_nom').val();
      const url = 'new/obj'
      const res = (json) => {
   
          if (json.length > 0) {
        $.each(json, function (index, value) {
          $('#search-results-objet').append(`<tr><td class='text-center'>${value.denomination}</td><td class='text-center'>${value.marque}</td><td class='text-center'>${value.statut}</td>
          <td class='text-center'>xxx</td>
           <td class="text-center">
            <div class="form-check obj-check">
              <input name="objet-select" class="text-center form-check-input" type="radio" value=${value.id}>
            </div>
          </td></tr>`) });
        } else {
          $('#search-results-objet').append("<tr><td class='text-center' colspan='4'>Pas d'objet trouvé</td></tr>")
        }
      }

      selectOption(searched, res, url)
    })
    
    $('#select-objet').on('click', function (e) {
      e.preventDefault()
      $('#selected-objet').empty()
        const selected = $('input:radio[name="objet-select"]:checked').val()
      $('#hidden-obj').val($.trim(selected))

      const res = (json) =>
    
        $('#selected-objet').append(`<div class="row font-raleway form-control select-height width-auto ml-1" ><p class="p-2">Objet : ${$.trim(json.denomination)} ${$.trim(json.marque)} </p></div>`);
      
      const url = 'new/selobj';
      selectOption(selected, res, url);
    })


    $('.categorie-select select').on('change', function () {
      $('#ss-cat-select').empty()
  const selCat = $('.categorie-select select option:selected').val()
      const res = (json) =>
        $.each(json, function (index, val) {
   $('#ss-cat-select').append(`<option value=${val.id}>${val.nom_ss_categorie}</option>`)
        })
      
     const url = 'new/cat'
      selectOption(selCat, res, url)
    })
    $('#ss-cat-select').on('change', function () {
      const selectedCat = $('#ss-cat-select option:selected').val()
      $('#hidden-cat').val(selectedCat)
    })

    $('.biblio-select-edit select').on('change', function () {
       if ($('.biblio-select-edit select option:selected').val() == "oui") {
      replaceClass("biblio-part", 'd-none', 'd-block')
    } else if ($('.biblio-select-edit select option:selected').val() == "non") {
      replaceClass("biblio-part", 'd-block', 'd-none')

    }

    })
   
    $('.proprio-select select').on('change', function () {
      if ($('.proprio-select select option:selected').val() === "adherent") {
        replaceClass("search-module", 'd-none', 'd-block')
       
      } else if ($('.proprio-select select option:selected').val() === "association"){
        replaceClass("search-module", 'd-block', 'd-none')
     }
     
    })
    
    $('#modal-paiement').on('show.bs.modal', function (event) {
      console.log("modal")
      const button = $(event.relatedTarget)
      const objet = button.data('objet') 
      const prix = button.data('prix') 
      const emprunt = button.data('emprunt') 
      const modal = $(this)
      modal.find('.modal-title').text(`Valider le départ de : ${objet}`)
      modal.find('.modal-body').text(`L'emprunt n° ${emprunt} d'une valeur de ${prix} € a-t'il été payé ce jour ?` )
    })

  }
}



