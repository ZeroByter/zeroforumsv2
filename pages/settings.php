<style>
	h1, h2, h3, h4, h5, h6{
		margin-top: 0px;
	}
	.input-group{
		margin-top: 12px;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="well">
				<center><h3>Settings</h3></center>
				<div class="input-group">
					<span class="input-group-addon">Change username</span>
					<input class="form-control" id="changeUsername" value="<?php echo $currAccount->username ?>">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="changeUsernameBtn">Change</button>
					</span>
				</div>
				<div class="input-group">
					<span class="input-group-addon">Change display name</span>
					<input class="form-control" id="changeDisplayName" value="<?php echo $currAccount->displayname ?>">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="changeDisplayNameBtn">Change</button>
					</span>
				</div>
				<div class="input-group">
					<span class="input-group-addon">Change password</span>
					<input class="form-control" type="password" id="changePassword">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="changePasswordBtn">Change</button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="well">
				<center><h3>Privacy</h3></center>
				 - show display name or username
			</div>
		</div>
		<div class="col-md-6">
			<div class="well">
				<center>
					<h3>Security</h3>
					<h5>Here is all the logs created by you:</h5>
				</center>
			</div>
		</div>
	</div>
</div>

<script>
	$("#changeUsernameBtn").click(function(){
		$.post("/phpscripts/requests/changeUsername.php", {username: $("#changeUsername").val()}, function(html){
			console.log(html)
			if(html == "success"){
				location.reload()
			}else{
				$.notify({
                    message: html,
                },{
                    type: "danger",
                })
			}
		})
	})
	$("#changeDisplayNameBtn").click(function(){
		$.post("/phpscripts/requests/changeDisplayName.php", {displayName: $("#changeDisplayName").val()}, function(html){
			console.log(html)
			if(html == "success"){
				location.reload()
			}else{
				$.notify({
                    message: html,
                },{
                    type: "danger",
                })
			}
		})
	})
	$("#changePasswordBtn").click(function(){
		$.post("/phpscripts/requests/changePassword.php", {password: $("#changePassword").val()}, function(html){
			console.log(html)
			if(html == "success"){
				location.reload()
			}else{
				$.notify({
                    message: html,
                },{
                    type: "danger",
                })
			}
		})
	})
</script>