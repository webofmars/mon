function sortAndPageTable(a,b){$("#"+a).tablesorter();$("#"+a).tablesorterPager({container:$("#"+b)})}function manageUserActivation(c,b){var a=false;if(c.attr("class")=="alertRight on"){a=false}else{a=true}action="/app_dev.php/admin/user/active/"+b+"/"+a;$.ajax({url:action,cache:false,success:function(d){if(c.attr("class")=="alertRight on"){c.removeClass("on");c.addClass("off")}else{c.removeClass("off");c.addClass("on")}},error:function(d,f,e){alert(f)}});return false}function add_control_header(a){index=a.children().length;a.append($(a.attr("data-prototype").replace(/__name__/g,index)));a.append($('<a href="#" id="delete_control_header_'+index+'" class="btn btn-danger">Supprimer</a><br /><br />'));$("#delete_control_header_"+index).click(function(){$(this).prev().remove();$(this).remove();return false})}function add_validator(a){index=a.children().length;a.append($(a.attr("data-prototype").replace(/__name__/g,index)));a.append($('<a href="#" id="delete_validator_'+index+'" class="btn btn-danger">Supprimer</a><br /><br />'));$("#delete_validator_"+index).click(function(){$(this).prev().remove();$(this).remove();return false})}function showConfirmDeleteUser(a,b){$("#button_submit_cancel_user").attr("href",a);$("#p_username_to_delete").append(b);$("#myModal").modal("show")}function showConfirmDelete(a,b){$("#button_submit_cancel").attr("href",a);$("#p_name_to_delete").append(b);$("#myModal").modal("show")}$(function(){$("table#table_user_manage").tablesorter({sortList:[[0,0],[1,0]]});var b=$("#control_controlHeaders");var a=$("#control_validators");if(b.children().length==0){add_control_header(b)}if(a.children().length==0){add_validator(a)}$("#add_control_header").click(function(){add_control_header(b);return false});$("#add_validator").click(function(){add_validator(a);return false})});