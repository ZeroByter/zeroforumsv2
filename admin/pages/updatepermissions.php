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

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <a href="/admin/permissions"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
            <center>
                Editing permissions for: <b><?php echo $usertag->name ?></b><br><br>
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
    </div>
</div>

<script>
    var usertagID = "<?php echo $usertag->id ?>"
    var redirectURL = "/admin/permissions"

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
		var postPermissions = JSON.stringify(permissions)

		$.post("/admin/requests/updatepermissions.php", {usertagid: usertagID, permissions: postPermissions}, function(html){
			console.log(html)
			if(html == "success"){
				if($("[data-name='"+permissionUpdateName+"']").attr("data-state") == "true"){
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
