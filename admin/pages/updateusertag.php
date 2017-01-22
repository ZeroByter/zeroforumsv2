<style>
	#setDefault, #toggleStaff, #delete{
		margin-bottom: 14px;
	}
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <a href="/admin/usertags"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
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
			<center>
				<div class="row">
					<div class="col-md-6">
						<div class="well">
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
						</div>
					</div>
					<div class="col-md-6">
						<div class="well">
							<h4>Usertag Color:</h4>
							<button class="btn btn-default" id="textcolor_usedefault" style="margin-bottom:12px;">Use default color</button>
							<div id="textcolorpicker" class="input-group colorpicker-component">
								<input type="text" value="<?php echo $usertag->textcolor ?>" class="form-control" />
								<span class="input-group-addon"><i></i></span>
							</div>
						</div>
					</div>
				</div>
			</center>
        </div>
    </div>
</div>

<script>
	var usertagID = "<?php echo $usertag->id ?>"
	var redirectURL = "/admin/usertags"

	function updateUsertag(){
		$.post("/admin/requests/updateusertag.php", {tagid: usertagID, name: $("#nameInput").val(), listorder: $("#listorderInput").val(), textcolor: $("#textcolorpicker").colorpicker("getValue")}, function(html){
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
	}

	$("[data-toggle='tooltip']").tooltip()
	$("#textcolorpicker").colorpicker({ format: "hex" })
	$("#textcolorpicker").on("hidePicker", function(e){
		updateUsertag()
	})
	$("#textcolor_usedefault").click(function(){
		$("#textcolorpicker").colorpicker("setValue", "")
		updateUsertag()
	})

	$("#usertagForm").submit(function(){
		updateUsertag()
		return false
	})

	$("#setDefault").click(function(){
		$.post("/admin/requests/makeusertagdefault.php", {tagid: usertagID}, function(html){
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

	$("#toggleStaff").click(function(){
		$.post("/admin/requests/toggleusertagstaff.php", {tagid: usertagID}, function(html){
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

	$("#delete").click(function(){
		$.post("/admin/requests/deleteusertag.php", {tagid: usertagID}, function(html){
			if(html == "success"){
				window.location = redirectURL
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
