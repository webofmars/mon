function showConfirmDeleteUser(route, wording){
  $('#button_submit_cancel_user').attr('href', route);
  $('#p_username_to_delete').append(wording);
  $('#myModal').modal('show');
}

$(function() {
  $('table#table_user_manage').tablesorter( {sortList: [[0,0], [1,0]]} );

});