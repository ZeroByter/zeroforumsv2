<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/phpscripts/fillin/scripts.php");
	start_session();
	$currUsertags = [];
	if(accounts::is_logged_in()){
		$currAccount = accounts::get_current_account();
		$currUsertags = accounts::get_current_usertags();
	}
	$user = accounts::get_by_id($_GET["id"]);
?>

<style>
	#mainWarningsDiv{
		width: 100%;
		padding: 8px;
		overflow: auto;
		text-align: center;
	}
</style>

<div id="mainWarningsDiv">
	<table class="table table-stripped">
		<thead>
			<tr>
				<th>Warned by</th>
				<th>Time issued</th>
				<th>Message</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$warnings = accounts::get_warnings($user->id);
				foreach($warnings as $value){
					echo "
						<tr>
							<td>".accounts::get_display_name($value->warnedby)."</td>
							<td>".timestamp_to_date($value->time)."</td>
							<td>$value->message</td>
						</tr>
					";
				}
			?>
		</tbody>
	</table>
</div>

<script>

</script>
