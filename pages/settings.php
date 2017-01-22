<style>
	h1, h2, h3, h4, h5, h6{
		margin-top: 0px;
	}
	.input-group{
		margin-top: 12px;
	}
	.logContainer{
		width: 100%;
		margin: 0px auto;
	}
	.logContainer:not(:first-of-type) > .logHeader{
		border-top: none;
	}
	.logHeader{
		padding: 8px;
		border: grey 1px solid;
		border-bottom: none;
		background: rgba(0, 0, 0, 0.14);
		cursor: pointer;
	}
	.logDetails{
		border: grey 1px solid;
		border-top: none;
		overflow: hidden;
		transition: height 500ms;
		height: 0;
	}
	.logWrapper{
		padding: 12px 10px;
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
					<h5>Here are the last 12 logs created by you:</h5>
					<a href="javascript:">View all.</a>
				</center><br>
				<?php foreach(array_filter(logs::get_all_last_by_owner($currAccount->id)) as $value){ ?>
					<div class="logContainer">
						<div class="logHeader" data-log="<?php echo $value->id ?>">
							<span style="float: right"><?php echo timestamp_to_date($value->time, true) ?></span>
							<?php echo $value->title ?>
						</div>
						<div class="logDetails" data-log="<?php echo $value->id ?>">
							<div class="logWrapper" data-log="<?php echo $value->id ?>">
								<b>Log level:</b> <?php echo $value->level ?><br>
								<b>Source:</b> <?php echo $value->ip ?><br>
								<b>Description:</b> <?php echo $value->description ?><br>
								<b>Owner:</b> <?php
									if($value->owner > 0){
										echo accounts::get_by_id($value->owner)->username;
									}else{
										echo $value->ip;
									}
								?><br>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<script>
	$(".logHeader").click(function(){
		var logID = $(this).data("log")
		var logDetails = $(".logDetails[data-log='"+logID+"']")[0]
		if(logDetails.clientHeight){
			$(logDetails).attr("data-open", "0")
			logDetails.style.height = 0
		}else{
			$(logDetails).attr("data-open", "1")
			var logWrapper = $(".logWrapper[data-log='"+logID+"']")[0]
			logDetails.style.height = logWrapper.clientHeight + "px"
		}
	})
	
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