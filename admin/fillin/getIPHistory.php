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
	#mainIPHistoryDiv{
		width: 100%;
		padding: 8px;
		overflow: auto;
	}
	#ipHistorySideDiv{
		float: left;
		width: 160px;
		border-right: rgba(0,0,0,0.25) 1px solid;
	}
	#ipHistorySideDiv > div{
		padding: 6px;
		margin-right: 10px;
	}
	#ipHistorySideDiv > div:hover{
		background: rgba(0,0,0,0.25);
		cursor: pointer;
	}
	#ipHistoryAccountHistory{
		margin-left: 16px;
		float: left;
		max-width: 376px;
	}
	#ipHistoryMatchingAccounts{
		margin-left: 16px;
		float: left;
		max-width: 376px;
	}
	.same_ip_account_list_div{
		width: 200px;
		padding: 6px;
		text-align: center;
		cursor: pointer;
	}
	.same_ip_account_list_div:hover{
		background: rgba(0,0,0,0.24);
	}
</style>

<div id="mainIPHistoryDiv">
	<div id="ipHistorySideDiv">
		<div id="ipHistorySelect1">Account IP history</div>
		<div id="ipHistorySelect2">Other accounts with the same IP</div>
	</div>
	<div id="ipHistoryAccountHistory">
		<?php foreach(accounts::get_iplist($user->id) as $value){
			echo "<h4>$value->ip</h4><h5>Created at ".timestamp_to_date($value->firstseen,true)." and last seen at ".timestamp_to_date($value->lastseen,true)."</h5>";
		} ?>
	</div>
	<div id="ipHistoryMatchingAccounts" style="display:none;">
		<?php
			$all_same_ip_accounts = accounts::get_all_with_ip($user->id, $user->id);
			foreach($all_same_ip_accounts as $value){
	            echo "<div class='same_ip_account_list_div' data-id='$value->id'>".accounts::get_by_id($value->id)->username."</div>";
	        }
			if(count($all_same_ip_accounts) == 0){
	            echo "<center><h4>No other accounts with the same IP!</h4></center>";
	        }
		?>
	</div>
</div>

<script>
	$("#ipHistorySelect1").click(function(){
		$("#ipHistoryAccountHistory").css("display", "block")
		$("#ipHistoryMatchingAccounts").css("display", "none")
	})
	$("#ipHistorySelect2").click(function(){
		$("#ipHistoryMatchingAccounts").css("display", "block")
		$("#ipHistoryAccountHistory").css("display", "none")
	})
	$(".same_ip_account_list_div").click(function(){
		getIPHistory($(this).data("id"))
	})
</script>
