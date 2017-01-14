<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
	start_session();
	$currUsertags = [];
	if(accounts::is_logged_in()){
		$currAccount = accounts::get_current_account();
		$currUsertags = accounts::get_current_usertags();
	}
	$usertag = usertags::get_by_id($_GET["id"]);
?>

<style>
	#setDefault, #toggleStaff, #delete{
		margin-bottom: 14px;

	}
</style>

<div class="well">
	<?php if(usertags::user_has_permission($currUsertags, "updateusertag")){ ?>
	<form id="usertagForm">
		<div class="input-group form-group">
			<span class="input-group-addon">Name</span>
			<input class="form-control" placeholder="Name" id="nameInput" value="<?php echo $usertag->name ?>">
		</div>
		<div class="input-group form-group">
			<span class="input-group-addon">Listorder</span>
			<input class="form-control" placeholder="Listorder" id="listorderInput" value="<?php echo $usertag->listorder ?>">
		</div>
		<center><button type="submit" class="btn btn-default">Submit</button></center>
	</form>
	<br><br>
	<?php } ?>
	<center>
		<?php
			if(usertags::user_has_permission($currUsertags, "makeusertagdefault")){
				if($usertag->isdefault){
					echo "<button class='btn btn-primary' id='setDefault' disabled>This usertag is already default</button><br>";
				}else{
					echo "<button class='btn btn-primary' id='setDefault'>Set as default usertag</button><br>";
				}
			}
		?>
		<button class="btn btn-primary" id="toggleStaff"><?php echo ($usertag->isstaff) ? "Unmark as a staff tag" : "Mark as a staff tag" ?> <span class="label label-info" data-toggle="tooltip" title="Marking a usertag as a staff tag will make it visible on the staff list page, and will also grant them the ability to open this admin panel.">?</span></button><br>
		<?php
			if(usertags::user_has_permission($currUsertags, "addusertag")){
				if(!$usertag->isdefault){
					echo "<button class='btn btn-danger' id='delete'>Delete</button>";
				}
			}
		?>
	</center>
</div>

<script>
	$("[data-toggle='tooltip']").tooltip()

	$("#usertagForm").submit(function(){
		$.post("/admin/requests/updateusertag.php", {tagid: selectedUsertag, name: $("#nameInput").val(), listorder: $("#listorderInput").val()}, function(html){
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

	$("#setDefault").click(function(){
		$.post("/admin/requests/makeusertagdefault.php", {tagid: selectedUsertag}, function(html){
			if(html == "success"){
				getUsertagsPanel()
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
	})

	$("#toggleStaff").click(function(){
		$.post("/admin/requests/toggleusertagstaff.php", {tagid: selectedUsertag}, function(html){
			if(html == "success"){
				getUsertagsPanel()
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
	})

	$("#delete").click(function(){
		$.post("/admin/requests/deleteusertag.php", {tagid: selectedUsertag}, function(html){
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
	})
</script>
