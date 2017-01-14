<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");

    start_session();
	
	if(isset($_POST["usertagid"]) && isset($_POST["permissions"])){
		if(usertags::user_has_permission(accounts::get_current_usertags(), "updatepermissions")){
			logs::add_log("usertags", "$1 updated permissions for [usertag:{$_POST["usertagid"]}]", 4);
			if($_POST["permissions"] == ""){
				usertags::edit_permissions($_POST["usertagid"], json_encode([]));
			}else{
				usertags::edit_permissions($_POST["usertagid"], json_encode($_POST["permissions"]));
			}
			echo "success";
		}else{
			echo "No permission!";
		}
	}
?>
