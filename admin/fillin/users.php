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
	#usersActions{
		border-bottom: rgba(0,0,0,0.5) 1px solid;
		padding: 6px;
	}
	.userTags{
		padding: 8px;
		margin-top: -32px;
	}
	.usersTable > tbody > tr:hover{
		background: rgba(0, 90, 255, 0.25);
		cursor: pointer;
	}
	.listItemActive{
		background: rgba(0, 90, 255, 0.25);
	}
	.users_list_row_banned{
		background: rgba(255, 0, 0, 0.28);
	}
</style>

<div class="modal fade" id="setDisplayName">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Set display name</h4>
			</div>
			<form id="setDisplayNameForm">
				<div class="modal-body">
					<div class="form-group">
						<label>New display name</label>
						<input type="text" class="form-control" id="setDisplayNameInput" placeholder="New display name">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Set Display Name</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="warnUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Issue warning</h4>
			</div>
			<form id="issueWarningForm">
				<div class="modal-body">
					<div class="form-group">
						<textarea required class="form-control" id="issueWarningInput" style="resize:vertical;"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Issue Warning</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="unBanUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Remove ban</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" data-dismiss="modal" id="unbanUserModalBtn">Remove Ban</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="banUser">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Issue Ban On User</h4>
			</div>
			<form id="banUserForm">
				<div class="modal-body">
					<div class="input-group form-group">
						<span class="input-group-addon">Ban time</span>
						<select class="form-control" id="banUserTimeType">
							<option>Minutes</option>
							<option>Hours</option>
							<option>Days</option>
							<option>Weeks</option>
							<option>Months</option>
							<option>Years</option>
							<option>Permanent</option>
						</select>
						<input type="number" class="form-control" value="1" id="banUserTime" required>
					</div>
					<div class="input-group form-group">
						<span class="input-group-addon">Ban reason</span>
						<input class="form-control" placeholder="Ban reason" id="banUserReason" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Issue Ban</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="viewWarnings">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">View warnings</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="viewIPHistory">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">View IP history</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="manageUsertagsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
				<h4 class="modal-title">Manage usertags</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<table class="table table-hover table-stripped usersTable">
	<thead>
		<tr>
			<th>Username</th>
			<th>Display name</th>
			<th>Posts</th>
			<th>Last active</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach(array_filter(accounts::get_by_usertag($usertag->id)) as $value){
			$confirmBan = accounts::confirm_ban($value->id);
			$isBanned = "";
			if($confirmBan){
				$isBanned = "users_list_row_banned";
			}
		?>
		<tr data-id="<?php echo $value->id ?>" class="users_list_row <?php echo $isBanned ?>" data-username="<?php echo $value->username ?>" data-displayname="<?php echo $value->displayname ?>" data-banned="<?php echo ($confirmBan) ? "true" : "false"; ?>">
			<td>
				<?php echo $value->username ?><br>
				<?php foreach(accounts::get_user_tags($value->id) as $value2){ ?>
					<span class="label label-default"><?php echo usertags::get_by_id($value2)->name ?></span>
				<?php } ?>
			</td>
			<td><?php echo $value->displayname ?></td>
			<td><?php echo $value->posts ?></td>
			<td><?php echo timestamp_to_date($value->lastactive, true) ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<script>
	//Search user in user list
	$("#search_user_in").bind("change keyup", function(){
		var searchTerm = $(this).val().toLowerCase()
		if(searchTerm === ""){
			$(".users_list_row").css("display", "table-row")
		}else{
			$(".users_list_row").each(function(i,v){
				if($(v).data("username").toLowerCase().indexOf(searchTerm) > -1 || $(v).data("displayname").toLowerCase().indexOf(searchTerm) > -1){
					$(v).css("display", "table-row")
				}else{
					$(v).css("display", "none")
				}
			})
		}
	})

	//get selected user
	var selectedUser = 0
	$(".usersTable > tbody > tr").click(function(){
		selectedUser = $(this).data("id")
		$(".usersTable > tbody > tr").removeClass("listItemActive")
		$(this).addClass("listItemActive")
		$("#usersActions > div > button").removeAttr("disabled")
		$("#usersActions > div > input").removeAttr("disabled")
		if($(this).data("banned")){
			$("#banUserBtn").css("display", "none")
			$("#unbanUserBtn").css("display", "inline-block")
		}else{
			$("#banUserBtn").css("display", "inline-block")
			$("#unbanUserBtn").css("display", "none")
		}
	})

	//get manage usertags div
	function getManageUsertags(){
		//$.get("/adminF/manageUsertags/" + selectedUser, function(html){
		$.get("/admin/fillin/manageUsertags.php", {id: selectedUser}, function(html){
			$("#manageUsertagsModal > div > div > .modal-body").html(html)
		})
	}
	$("button[data-target='#manageUsertagsModal']").click(function(){
		getManageUsertags()
	})
	//get ip history div
	function getIPHistory(id){
		//$.get("/adminF/getIPHistory/" + id, function(html){
		$.get("/admin/fillin/getIPHistory.php", {id: id}, function(html){
			$("#viewIPHistory > div > div > .modal-body").html(html)
		})
	}
	$("button[data-target='#viewIPHistory']").click(function(){
		getIPHistory(selectedUser)
	})
	//get warnings div
	function getWarnings(){
		//$.get("/adminF/getWarnings/" + selectedUser, function(html){
		$.get("/admin/fillin/getWarnings.php", {id: selectedUser}, function(html){
			$("#viewWarnings > div > div > .modal-body").html(html)
		})
	}
	$("button[data-target='#viewWarnings']").click(function(){
		getWarnings()
	})

	//send request to change displayname
	$("#setDisplayNameForm").submit(function(){
		$.post("/admin/requests/setdisplayname.php", {userid: selectedUser, newname: $("#setDisplayNameInput").val()}, function(html){
			if(html == "success"){
				getUsers()
				$("#warnUser").modal("toggle")
				$("body").removeClass("modal-open")
				$(".modal-backdrop").remove();
            }else{
                $.notify({
                    message: html,
                },{
                    type: "danger",
                    z_index: 103001,
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
			}
		})
		return false
	})
	$("#issueWarningForm").submit(function(){
		$.post("/admin/requests/issuewarning.php", {userid: selectedUser, reason: $("#issueWarningInput").val()}, function(html){
			if(html == "success"){
				getUsers()
				$("#warnUser").modal("toggle")
				$("body").removeClass("modal-open")
				$(".modal-backdrop").remove();
            }else{
                $.notify({
                    message: html,
                },{
                    type: "danger",
                    z_index: 103001,
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
			}
		})
		return false
	})

	//ban user
	$("#banUserForm").submit(function(){
		var time = $("#banUserTime").val()
		var timetype = $("#banUserTimeType").val()
		var finaltime = 1
		if(timetype == "Minutes"){
			finaltime = time * 60
		}
		if(timetype == "Hours"){
			finaltime = time * 60 * 60
		}
		if(timetype == "Days"){
			finaltime = time * 60 * 60 * 24
		}
		if(timetype == "Weeks"){
			finaltime = time * 60 * 60 * 24 * 7
		}
		if(timetype == "Months"){
			finaltime = time * 60 * 60 * 24 * 7 * 4
		}
		if(timetype == "Years"){
			finaltime = time * 60 * 60 * 24 * 7 * 4 * 12
		}
		if(timetype == "Permanent"){
			finaltime = 9999999999
		}

		$.post("/admin/requests/banuser.php", {userid: selectedUser, reason: $("#banUserReason").val(), time: finaltime}, function(html){
			console.log(html)
			if(html == "success"){
				$("#banUser").modal("hide")
				getUsers()
            }else{
                $.notify({
                    message: html,
                },{
                    type: "danger",
                    z_index: 103001,
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
			}
		})

		return false
	})

	$("#unbanUserBtn").click(function(){
		//$.get("/adminF/unbanUser/" + selectedUser, function(html){
		$.get("/admin/fillin/unbanUser.php", {id: selectedUser}, function(html){
			$("#unBanUser > div > div > .modal-body").html(html)
		})
	})
	$("#unbanUserModalBtn").click(function(){
		$.post("/admin/requests/unbanuser.php", {userid: selectedUser}, function(html){
			if(html == "success"){
				getUsers()
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
