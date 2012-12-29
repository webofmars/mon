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

function showConfirmDeleteUser(route, wording){
  $('#button_submit_cancel_user').attr('href', route);
  $('#p_username_to_delete').append(wording);
  $('#myModal').modal('show');
}

$(function() {
  $('table#table_user_manage').tablesorter( {sortList: [[0,0], [1,0]]} );

});