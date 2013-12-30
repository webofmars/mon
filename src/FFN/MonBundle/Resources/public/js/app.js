function sortTable(name) {
    $("#"+name).tablesorter(); 
}

function manageUserActivation(button, user_id){
  var statut = false;
  if(button.attr('class') == "alertRight on"){
    statut = false;
  } else {
    statut = true;
  }
  action = '/app_dev.php/admin/user/active/'+user_id+'/'+statut;
  $.ajax({
    url: action,
    cache: false,
    success: function(html){
      if(button.attr('class') == "alertRight on"){
        button.removeClass('on');
        button.addClass('off');
      } else {
        button.removeClass('off');
        button.addClass('on');
      }
    },
    error: function(XMLHttpRequest, textStatus, errorThrown){
      alert(textStatus)
    }
  });
  return false;
}

// On définit une fonction qui va ajouter un champ
function add_control_header(container) {
  // On définit le numéro du champ (en comptant le nombre de champs déjà ajoutés)
  index = container.children().length;

  // On ajoute à la fin de la balise <div> le contenu de l'attribut « data-prototype »
  // Après avoir remplacé la variable __name__ qu'il contient par le numéro du champ
  container.append($(container.attr('data-prototype').replace(/__name__/g, index)));

  // On ajoute également un bouton pour pouvoir supprimer la catégorie
  container.append($('<a href="#" id="delete_control_header_' + index + '" class="btn btn-danger">Supprimer</a><br /><br />'));

  // On supprime le champ à chaque clic sur le lien de suppression
  $('#delete_control_header_' + index).click(function() {
    $(this).prev().remove();  // $(this).prev() est le template ajouté
    $(this).remove();         // $(this) est le lien de suppression
    return false;
  });
}

// On définit une fonction qui va ajouter un champ
function add_validator(container) {
  // On définit le numéro du champ (en comptant le nombre de champs déjà ajoutés)
  index = container.children().length;

  // On ajoute à la fin de la balise <div> le contenu de l'attribut « data-prototype »
  // Après avoir remplacé la variable __name__ qu'il contient par le numéro du champ
  container.append($(container.attr('data-prototype').replace(/__name__/g, index)));

  // On ajoute également un bouton pour pouvoir supprimer la catégorie
  container.append($('<a href="#" id="delete_validator_' + index + '" class="btn btn-danger">Supprimer</a><br /><br />'));

  // On supprime le champ à chaque clic sur le lien de suppression
  $('#delete_validator_' + index).click(function() {
    $(this).prev().remove();  // $(this).prev() est le template ajouté
    $(this).remove();         // $(this) est le lien de suppression
    return false;
  });
}

function showConfirmDeleteUser(route, wording){
  $('#button_submit_cancel_user').attr('href', route);
  $('#p_username_to_delete').append(wording);
  $('#myModal').modal('show');
}

function showConfirmDelete(route, wording){
  $('#button_submit_cancel').attr('href', route);
  $('#p_name_to_delete').append(wording);
  $('#myModal').modal('show');
}

$(function() {
  $('table#table_user_manage').tablesorter( {sortList: [[0,0], [1,0]]} );

// On récupère la balise <div> en question qui contient l'attribut « data-prototype » qui nous intéresse
  var $container_controlHeader = $('#control_controlHeaders');
  var $container_validator = $('#control_validators');



  // On ajoute un premier champ directement s'il n'en existe pas déjà un (cas d'un nouvel article par exemple)
  if($container_controlHeader.children().length == 0) {
    add_control_header($container_controlHeader);
  }
  if($container_validator.children().length == 0) {
    add_validator($container_validator);
  }

  // On ajoute un nouveau champ à chaque clic sur le lien d'ajout
  $('#add_control_header').click(function() {
    add_control_header($container_controlHeader);
    return false;
  });

  $('#add_validator').click(function() {
    add_validator($container_validator);
    return false;
  });
});