<style>
	.row{
		padding: 0px 19px;
	}
	.well{
		border-color: #c1c1c1;
		padding: 0px;
	}
	.divLink{
		padding: 10px;
		display: block;
		text-align: center;
	}
	.divLink:hover{
		background: #ccc;
		color: #0089ff;
	}
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 well">
			<?php
				if(usertags::user_has_permission($currUsertags, "addusertag")){
					echo "<a href='/admin/usertags/new' class='divLink createNew'>Create new usertag</a>";
				}
			?>
			<?php foreach(usertags::get_all_limited() as $value){ ?>
				<a href="/admin/usertags/<?php echo $value->id ?>" class="divLink"><?php echo $value->name ?> <span class="label label-default"><?php echo count(accounts::get_by_usertag($value->id))-1 ?></span></a>
			<?php } ?>
        </div>
    </div>
</div>
