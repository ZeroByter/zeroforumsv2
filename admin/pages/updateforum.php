<style>
	.permissionBtn{
		margin: 0px 2px;
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
            <a href="/admin/forums"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
            <form id="submitForm">
                <div class="input-group form-group">
                    <span class="input-group-addon">Title</span>
                    <input type="text" class="form-control" value="<?php echo $forum->title ?>" id="title" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Description</span>
                    <input type="text" class="form-control" value="<?php echo $forum->text ?>" id="text">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">List order</span>
                    <input type="number" class="form-control" value="<?php echo $forum->listorder ?>" id="listorder" required>
                </div>
				<center>
					View permissions for <select id="permissions_type_in">
						<option data-type="canview">who can view</option>
						<option data-type="canpost">who can post</option>
					</select><br><br>
					<button class="btn permissionBtn" id="permissions_all" data-name="all" data-state="false">All</button>
					<button class="btn permissionBtn" id="permissions_registered" data-name="registered" data-state="false">Registered</button>
					<button class="btn permissionBtn" id="permissions_staff" data-name="staff" data-state="false">Staff</button>
					<br><br>Usertags:<br>
					<?php
						foreach(usertags::get_all_limited() as $value){
							if($value){
								echo "<button class='btn permissionBtn' id='permissions_tag_$value->id' data-name='$value->id' data-state='false'>$value->name</button>";
							}
						}
					?>
				</center>
				<br><br>
                <button class="btn btn-danger" style="width:100%;" id="delete">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    var forumid = "<?php echo $forum->id ?>"
    var title = "<?php echo $forum->title ?>"
    var text = "<?php echo $forum->text ?>"
    var listorder = "<?php echo $forum->listorder ?>"
    var permissionstype = "canview"
    var permissions = []
	
	$("#submitForm").submit(function(){
		return false
	})
	
	function getpermissions(type){
        $(".permissionBtn").attr("data-state", "false")
        $.get("/admin/requests/getforumpermission.php", {id: forumid, type: type}, function(html){
            permissionstype = type
            permissions = JSON.parse(html)
            $(JSON.parse(html)).each(function(i, v){
                if(Number(v)){
                    $("#permissions_tag_" + v).attr("data-state", "true")
                }else{
                    $("#permissions_" + v).attr("data-state", "true")
                }
            })
        })
    }
    getpermissions("canview")

    function updateforum(){
        $.post("/admin/requests/updateforum.php", {id: forumid, title: $("#title").val(), text: $("#text").val(), listorder: $("#listorder").val()}, function(html){
            console.log(html)
        })
    }
	
	$(".permissionBtn").click(function(){
        var state = $(this).attr("data-state")
        var name = $(this).data("name")
        if(state == "true"){ //turn off
            $(this).attr("data-state", "false")
			permissions.splice(permissions.indexOf(name), 1)
			if(permissions.length <= 0){
                permissions = ["all"]
                $("#permissions_all").attr("data-state", "true")
            }
        }else{ //turn on
            $(this).attr("data-state", "true")
			permissions.push(name)
        }
        $.post("/admin/requests/setforumpermission.php", {id: forumid, type: permissionstype, permissions: JSON.stringify(permissions)}, function(html){
            console.log(html)
        })
    })

    $("#title, #text, #listorder").bind("change", function(){
        updateforum()
    })
	
	$("#permissions_type_in").change(function(){
        if($(this).val() == "who can post"){
            getpermissions("canpost")
        }
        if($(this).val() == "who can view"){
            getpermissions("canview")
        }
    })
	
	$("#delete").click(function(){
		$.post("/admin/requests/deleteforum.php", {id: forumid}, function(html){
			if(html == "success"){
				window.location = "/admin/forums"
			}else{
				
			}
		})
	})
</script>
