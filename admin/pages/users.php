<style>
	#usertagsSelection{
		padding: 7px 0px;
		border: rgba(0, 0, 0, 0.5) 1px solid;
		width: 300px;
		height: calc(100% - 88px);
		position: fixed;
	}
	#usersActions{
		padding: 6px;
		width: calc(100% - 300px);
		float: right;
	}
	#usersDiv{
		padding: 6px;
		width: calc(100% - 300px);
		float: right;
	}
	.usertagListItem{
		text-align: center;
		cursor: pointer;
		padding: 6px;
	}
	.usertagListItem:hover{
		background: rgba(0, 90, 255, 0.25);
	}
</style>

<div id="usertagsSelection">
	<center><h2>Usertags:</h2></center>
	<?php foreach(usertags::get_all_limited() as $value){ if($value){ ?>
		<div class="usertagListItem" data-id="<?php echo $value->id ?>">
			<?php echo $value->name ?> <span class="label label-default"><?php echo count(accounts::get_by_usertag($value->id))-1 ?></span>
		</div>
	<?php } } ?>
</div>
<div id="usersActions">
	<div class="btn-group">
		<?php if(usertags::user_has_permission($currUsertags, "manageusertags", false)){ ?>
			<button type="button" class="btn btn-default" disabled data-toggle="modal" data-target="#manageUsertagsModal">Manage usertags</button>
		<?php }if(usertags::user_has_permission($currUsertags, "viewwarnings", false)){ ?>
		<button type="button" class="btn btn-default" disabled data-toggle="modal" data-target="#viewWarnings">View warnings</button>
		<?php }if(usertags::user_has_permission($currUsertags, "viewiphistory", false)){ ?>
		<button type="button" class="btn btn-default" disabled data-toggle="modal" data-target="#viewIPHistory">View IP history</button>
		<?php }if(usertags::user_has_permission($currUsertags, "warnuser", false)){ ?>
		<button type="button" class="btn btn-warning" disabled data-toggle="modal" data-target="#warnUser">Warn user</button>
		<?php }if(usertags::user_has_permission($currUsertags, "banuser", false)){ ?>
		<button type="button" class="btn btn-danger" disabled id="banUserBtn" data-toggle="modal" data-target="#banUser">Ban user</button>
		<?php }if(usertags::user_has_permission($currUsertags, "banuser", false)){ ?>
		<button type="button" class="btn btn-danger" disabled id="unbanUserBtn" style="display:none;" data-toggle="modal" data-target="#unBanUser">Unban user</button>
		<?php }if(usertags::user_has_permission($currUsertags, "setdisplayname", false)){ ?>
		<button type="button" class="btn btn-primary" disabled data-toggle="modal" data-target="#setDisplayName">Set display name</button>
		<?php }if(usertags::user_has_permission($currUsertags, "setpostscount", false)){ ?>
		<button type="button" class="btn btn-primary" disabled>Set posts count</button>
		<?php }if(usertags::user_has_permission($currUsertags, "deleteallposts", false)){ ?>
		<button type="button" class="btn btn-primary" disabled>Delete all posts</button>
		<?php } ?>
	</div>
	<div class="input-group" style="margin-top:6px;width:400px;">
		<span class="input-group-addon">Search</span>
		<input type="text" class="form-control" placeholder="Username or display name" id="search_user_in" disabled>
	</div>
</div>
<div id="usersDiv">

</div>

<script>
	var selectedUsertag = 0
	function getUsers(){
		//$.get("/adminF/users/" + selectedUsertag, function(html){
		$.get("/admin/fillin/users.php", {id: selectedUsertag}, function(html){
			$("#usersDiv").html(html)

			$("#usersActions > div > button").attr("disabled", "")
			$("#usersActions > div > input").removeAttr("disabled")
		})
	}

	$(".usertagListItem").click(function(){
		selectedUsertag = $(this).data("id")

		getUsers()
	})
</script>
