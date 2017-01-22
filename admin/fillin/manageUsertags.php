<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
	start_session();
	$currUsertags = [];
	if(accounts::is_logged_in()){
		$currAccount = accounts::get_current_account();
		$currUsertags = accounts::get_current_usertags();
	}
	$user = accounts::get_by_id($_GET["id"]);
	$usertagsList = accounts::get_user_tags($_GET["id"]);
?>

<center>
	<?php
		if($user->id == accounts::get_current_account()->id){
			echo "<h4 style='color:red;'>Careful! You are editting your own usertags! You could accidently remove your own permissions to edit permissions!</h4><br><br><br>";
		}
	?>
</center>
<div class="btn-group">
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Add usertag <span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php
			foreach(usertags::get_all_limited() as $value){
				if($value){
					if(!in_array($value->id, $usertagsList)){
						echo "<li><a href='javascript:' class='addUsertag' data-id='$value->id'>$value->name</a></li>";
					}
				}
			}
		?>
	</ul>
</div>
<br><br>
<table class="table table-hover table-stripped">
	<tbody>
		<?php foreach($usertagsList as $value){ if($value){ $value = usertags::get_by_id($value) ?>
			<tr>
				<td><?php echo $value->name ?></td>
				<td><button type="button" class="btn btn-danger btn-xs takeUsertag" data-id='<?php echo $value->id ?>'>&times;</button></td>
			</tr>
		<?php } } ?>
	</tbody>
</table>

<script>
	$(".addUsertag").click(function(){
		$.post("/admin/requests/addusertag.php", {userid: "<?php echo $user->id ?>", usertagid: $(this).data("id")}, function(html){
			console.log(html)
			getManageUsertags()
		})
	})
	$(".takeUsertag").click(function(){
		$.post("/admin/requests/takeusertag.php", {userid: "<?php echo $user->id ?>", usertagid: $(this).data("id")}, function(html){
			console.log(html)
			if(html == "success"){
				getManageUsertags()
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
	})
</script>
