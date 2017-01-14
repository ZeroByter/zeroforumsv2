<style>
	h1, h2, h3, h4, h5, h6{
		margin-top: 0px;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="well" style="float:right;margin:10px;">
					<h3 style="margin-top:0px;">Usertags:</h3>
					<?php
						foreach(accounts::get_user_tags($profile->id) as $value){
							$tag = usertags::get_by_id($value);
							echo "$tag->name<br>";
						}
					?>
				</div>
				<div class="panel-body">
					<h2><?php echo accounts::get_display_name($profile->id) ?></h2>
					<h3><?php echo $profile->bio ?></h3>
					<div class="well" style="margin-top:30px;">
						<center>
							<h2>Forum posts:</h2>
							( ͡° ͜ʖ ͡°) nothing here yet ( ͡° ͜ʖ ͡°)
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
