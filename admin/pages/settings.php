<?php
	$settings = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

	$key = $settings["key"];
	$mysqlIP = decrypt_text($settings["mysql"]["ip"], $key);
	$mysqlUsername = decrypt_text($settings["mysql"]["username"], $key);
	$mysqlPassword = decrypt_text($settings["mysql"]["password"], $key);
	$mysqlDBName = decrypt_text($settings["mysql"]["dbname"], $key);
?>

<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<center><h1>General settings</h1></center>
			<br>
			<div class="well">

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
	$("#dbSettingsForm").submit(function(){
		var postArray = {
			ip: $("#mysqlIPAddress").val(),
			username: $("#mysqlUsername").val(),
			password: $("#mysqlPassword").val(),
			dbname: $("#mysqlDBName").val(),
		}

		$.post("/admin/requests/setmysql.php", postArray, function(html){
			console.log(html)
		})
		return false
	})
</script>
