<style>
	.logContainer{
		width: 70%;
		margin: 0px auto;
	}
	.logContainer:not(:first-of-type) > .logHeader{
		border-top: none;
	}
	.logHeader{
		padding: 8px;
		border: grey 1px solid;
		border-bottom: none;
		background: rgba(0, 0, 0, 0.14);
		cursor: pointer;
	}
	.logDetails{
		border: grey 1px solid;
		border-top: none;
		overflow: hidden;
		transition: height 500ms;
		height: 0;
	}
	.logWrapper{
		padding: 12px 10px;
	}
</style>

<br><br>
<center><a href="/admin/logs"><button class="btn btn-default">Go back to logs</button></a></center>
<br><br>
<div>
	<?php foreach(array_filter(logs::get_all_by_date($logDate)) as $value){ ?>
		<div class="logContainer">
			<div class="logHeader" data-log="<?php echo $value->id ?>">
				<span style="float: right"><?php echo timestamp_to_date($value->time, true) ?></span>
				<?php echo $value->title ?>
			</div>
			<div class="logDetails" data-log="<?php echo $value->id ?>">
				<div class="logWrapper" data-log="<?php echo $value->id ?>">
					<b>Log level:</b> <?php echo $value->level ?><br>
					<b>Source:</b> <?php echo $value->ip ?><br>
					<b>Description:</b> <?php echo filterXSS($value->description); ?><br>
					<b>Owner:</b> <?php
						if($value->owner > 0){
							echo filterXSS(accounts::get_by_id($value->owner)->username);
						}else{
							echo $value->ip;
						}
					?><br>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

<script>
	$(".logHeader").click(function(){
		var logID = $(this).data("log")
		var logDetails = $(".logDetails[data-log='"+logID+"']")[0]
		if(logDetails.clientHeight){
			$(logDetails).attr("data-open", "0")
			logDetails.style.height = 0
		}else{
			$(logDetails).attr("data-open", "1")
			var logWrapper = $(".logWrapper[data-log='"+logID+"']")[0]
			logDetails.style.height = logWrapper.clientHeight + "px"
		}
	})
</script>
