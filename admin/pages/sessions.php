<?php

?>

<style>
	.sessionDiv > div{
		display: table-cell;
		margin: 10px 10px;
	}
</style>

<br><br><br>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-sm-offset-2">
			Your current session ID: <?php echo sessions::get_session($_SESSION["sessionid"])->id ?>
			<table class="table table-stripped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>IP</th>
						<th>Account ID</th>
						<th>Created Date</th>
						<th>Last Used Date</th>
						<th>Browser</th>
						<th>Platform</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach(sessions::get_all() as $value){ ?>
						<tr>
							<td><?php echo $value->id ?></td>
							<td><?php echo $value->ip ?></td>
							<td><?php echo $value->accountid ?></td>
							<td><?php echo timestamp_to_date($value->createddate, true) ?></td>
							<td><?php echo timestamp_to_date($value->lastuseddate, true) ?></td>
							<td><?php echo $value->browser ?></td>
							<td><?php echo $value->platform ?></td>
							<td><button class="btn btn-danger btn-sml deleteSession" data-id="<?php echo $value->id ?>">Delete session</button></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(".deleteSession").click(function(){
		$.post("/admin/requests/deleteSession.php", {id: $(this).data("id")}, function(html){
			console.log(html)
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
</script>
