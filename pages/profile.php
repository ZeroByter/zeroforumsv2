<link rel="stylesheet" type="text/css" href="/stylesheets/subforum.css"></link>
<style>
	h1, h2, h3, h4, h5, h6{
		margin-top: 0px;
	}
	#forumsActivity{
		overflow: auto;
		width: 100%;
	}

	#main_threads_div{
		width: 100%;
	}
	.threadTitle{
		float: left;
	}
	.threadSubforum{
		color: black;
	    float: right;
	    text-align: right;
	    margin-right: 300px;
		color: grey;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="well" style="float:right;margin:0px;">
						<h3 style="margin-top:0px;">Usertags:</h3>
						<?php
							foreach(accounts::get_user_tags($profile->id) as $value){
								$tag = usertags::get_by_id($value);
								echo "$tag->name<br>";
							}
						?>
					</div>
					<h2><?php echo accounts::get_display_name($profile->id) ?></h2>
					<h3><?php echo $profile->bio ?></h3>
					<div id="forumsActivity">
						<div class="well" style="margin-top:20px;">
							<center>
								<h2>Forum posts:</h2><br>
								<div class="container" id="main_threads_div">
									<?php foreach(forums::get_all_posts_by_poster($profile->id) as $value){
										$iconString = "";
							            if($value->locked){
							                $iconString .= " <i class='fa fa-lock' data-toggle='tooltip' title='This thread is locked.'></i> ";
							            }
							            if($value->hidden){
							                $iconString .= " <i class='fa fa-eye-slash' data-toggle='tooltip' title='This thread is hidden.'></i> ";
							            }
							            if($value->hidden && !usertags::user_has_permission($currUsertags, "hideunhide")){
							                continue;
							            }
							            $previewString = $value->text;
							            if(strlen($previewString) > 50){
							                $previewString = substr($previewString, 0, 50);
							                $previewString .= "...";
							            }

										$threadParentName = forums::get_by_id($value->parent)->title;
										$threadLinkID = $value->id;
										$threadTitleText = $value->title;
										if($value->type == "reply"){
											$threadParentName = forums::get_by_id(forums::get_by_id($value->parent)->parent)->title;
											$threadLinkID = $value->parent;
											$threadTitleText = $value->text;
											$previewString = "";
										}
									?>
										<div class="row">
											<div class="col-xl-1">
												<a href="/thread/<?php echo $threadLinkID ?>" target="_blank" class="thread" data-toggle="tooltip" title="<?php echo $previewString ?>">
													<span class="threadTitle"><?php echo $threadTitleText ?></span><span><?php echo $iconString ?></span>
													<span class="threadDate"><?php echo get_human_time($value->lastactive) ?> ago</span>
													<span class="threadReplies"><?php echo $value->replies ?> <span class="fa fa-commenting-o"></span></span>
													<span class="threadSubforum"><i><?php echo $threadParentName ?></i></span>
												</a>
											</div>
										</div>
									<?php } ?>
								</div>
							</center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('[data-toggle="tooltip"]').tooltip()
</script>
