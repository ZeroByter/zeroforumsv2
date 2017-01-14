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
	.permissionBtn{
		margin: 2px;
	}
	.permissionMainDiv{
		margin-bottom: 12px;
		border-top: 1px solid #ccc;
		padding: 6px;
	}
	.permissionBtn[data-state="true"]{
		color: #fff;
		background-color: #5cb85c;
		border-color: #4cae4c;
	}
	.permissionBtn[data-state="false"]{
		color: #fff;
		background-color: #d9534f;
		border-color: #d43f3a;
	}
</style>

<div class="well">
	<center>
		<button class="btn btn-success" id="selectAll">Select all</button>
		<button class="btn btn-danger" id="denyAll">Deny all</button><br><br><br>
		<?php
			$permissions = usertags::getpermissions();
			foreach($permissions as $key=>$value){
				echo "<div class='permissionMainDiv'>";
				echo "<button class='btn btn-primary' data-toggle='collapse' data-target='#permissionCollapse$key'>{$value[0]}</button>";
				echo "<div class='collapse' id='permissionCollapse$key'><br>";
				foreach($value as $key2=>$permission){
					if($key2 != 0){
						echo "<button class='btn permissionBtn' data-name='{$permission[0]}' data-desc='{$permission[1]}'>{$permission[1]}</button>";
					}
				}
				echo "</div>";
				echo "</div>";
			}
		?>
	</center>
</div>

<script>
	var allPermissions = []
	var permissions = JSON.parse("<?php echo addslashes($usertag->permissions) ?>")
	$(".permissionBtn").each(function(i, v){
		allPermissions.push($(v).data("name"))

		if(permissions[0] == "*"){
			$(v).attr("data-state", "true")
		}else{
			if(permissions.indexOf($(v).data("name")) > -1){
				$(v).attr("data-state", "true")
			}else{
				$(v).attr("data-state", "false")
			}
		}
	})
	
	function updatePermissions(permissionUpdateName){
		var postPermissions = permissions
		if(postPermissions.length <= 0){
			postPermissions = ""
		}
		
		$.post("/admin/requests/updatepermissions.php", {usertagid: selectedUsertag, permissions: postPermissions}, function(html){
			console.log(html)
			if(html == "success"){
				console.log($("[data-name='"+permissionUpdateName+"']"))
				if($("[data-name='"+permissionUpdateName+"']").attr("data-state")){
					$("[data-name='"+permissionUpdateName+"']").attr("data-state", "false")
				}else{
					$("[data-name='"+permissionUpdateName+"']").attr("data-state", "true")
				}
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
	}

	$(".permissionBtn").click(function(){
		var state = $(this).attr("data-state")

		if(permissions.length <= 1 && permissions[0] == "*"){
			permissions = Array.from(allPermissions)
		}

		if(state == "true"){
			permissions.splice(permissions.indexOf($(this).data("name")), 1)
		}else{
			permissions.push($(this).data("name"))

			if(permissions.length >= allPermissions.length){
				permissions = ["*"]
			}
		}

		updatePermissions($(this).data("name"))
	})
	
	$("#selectAll").click(function(){
		permissions = ["*"]
		updatePermissions()
		$(".permissionBtn").attr("data-state", "true")
	})
	$("#denyAll").click(function(){
		permissions.length = 0
		updatePermissions()
		$(".permissionBtn").attr("data-state", "false")
	})
</script>
