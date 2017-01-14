<style>
	#usertagsSelection{
		padding: 7px 0px;
		border: rgba(0, 0, 0, 0.5) 1px solid;
		width: 300px;
		height: calc(100% - 88px);
		position: fixed;
	}
	#usertagsDiv{
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
<div id="usertagsDiv">

</div>

<script>
	var selectedUsertag = 0
	function getPermissionsPanel(){
		$.get("/admin/fillin/permission.php", {id: selectedUsertag}, function(html){
			$("#usertagsDiv").html(html)
		})
	}

	$(".usertagListItem").click(function(){
		selectedUsertag = $(this).data("id")

		getPermissionsPanel()
	})
</script>
