<?php
	$settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

	$key = $settings["key"];
	$mysqlIP = decrypt_text($settings["mysql"]["ip"], $key);
	$mysqlUsername = decrypt_text($settings["mysql"]["username"], $key);
	$mysqlPassword = decrypt_text($settings["mysql"]["password"], $key);
	$mysqlDBName = decrypt_text($settings["mysql"]["dbname"], $key);
?>

<style>
	.input-group{
		margin-top: 12px;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<center><h1>General settings</h1></center>
			<br>
			<div class="well">
				<div class="input-group">
					<span class="input-group-addon">Forums Cooldown Timer</span>
					<input class="form-control" id="forumsCooldown" value="<?php echo $settings["forumsCooldown"] ?>">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="forumsCooldownBtn">Change</button>
					</span>
				</div>
				<div class="input-group">
					<span class="input-group-addon">Usertags Update Interval</span>
					<input class="form-control" id="updateUsertagsInterval" value="<?php echo $settings["updateUsertagsInterval"] ?>">
					<span class="input-group-btn">
						<button class="btn btn-primary" id="updateUsertagsIntervalBtn">Change</button>
					</span>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<center><h1>Database settings</h1></center>
			<br>
			<div class="well">
				<form id="dbSettingsForm">
					<div class="form-group">
						<label>MySQL IP Address</label>
						<input type="text" class="form-control" id="mysqlIPAddress" placeholder="MySQL IP Address" value="<?php echo $mysqlIP ?>">
					</div>
					<div class="form-group">
						<label>MySQL Username</label>
						<input type="text" class="form-control" id="mysqlUsername" placeholder="MySQL Username" value="<?php echo $mysqlUsername ?>">
					</div>
					<div class="form-group">
						<label>MySQL Password</label>
						<input type="password" class="form-control" id="mysqlPassword" placeholder="MySQL Password" value="<?php echo $mysqlPassword ?>">
					</div>
					<div class="form-group">
						<label>MySQL Database Name</label>
						<input type="text" class="form-control" id="mysqlDBName" placeholder="MySQL Database Name" value="<?php echo $mysqlDBName ?>">
					</div>
					<center>
						<button type="submit" class="btn btn-primary">Submit</button>
					</center>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').tooltip()
	
	$("#dbSettingsForm").submit(function(){
		var postArray = {
			ip: $("#mysqlIPAddress").val(),
			username: $("#mysqlUsername").val(),
			password: $("#mysqlPassword").val(),
			dbname: $("#mysqlDBName").val(),
		}

		$.post("/admin/requests/settings/setmysql.php", postArray, function(html){
			console.log(html)
		})
		return false
	})
	
	$("#forumsCooldownBtn").click(function(){
		var input = $("#forumsCooldown").val()
		input = parseInt(input)
		$.post("/admin/requests/settings/setforumscooldown.php", {cooldown: input})
	})
	
	$("#updateUsertagsIntervalBtn").click(function(){
		var input = $("#updateUsertagsInterval").val()
		input = parseInt(input)
		$.post("/admin/requests/settings/setusertagsupdatetime.php", {time: input})
	})
</script>
