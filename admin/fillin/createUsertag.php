<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
	start_session();
	$currUsertags = [];
	if(accounts::is_logged_in()){
		$currAccount = accounts::get_current_account();
		$currUsertags = accounts::get_current_usertags();
	}
?>

<style>
	#setDefault, #toggleStaff, #delete{
		margin-bottom: 14px;

	}
</style>

<div class="well">
	<form id="usertagForm">
		<div class="input-group form-group">
			<span class="input-group-addon">Name</span>
			<input class="form-control" placeholder="Name" id="nameInput">
		</div>
		<div class="input-group form-group">
			<span class="input-group-addon">Listorder</span>
			<input class="form-control" placeholder="Listorder" id="listorderInput" value="1">
		</div>
		<center><button type="submit" class="btn btn-default">Create usertag</button></center>
	</form>
</div>

<script>
	$("[data-toggle='tooltip']").tooltip()

	$("#usertagForm").submit(function(){
		$.post("/admin/requests/createusertag.php", {name: $("#nameInput").val(), listorder: $("#listorderInput").val()}, function(html){
			if(html == "success"){
				location.reload()
			}else{
				$.notify({
                    message: html,
                },{
                    type: "danger",
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
			}
		})
		return false
	})
</script>
