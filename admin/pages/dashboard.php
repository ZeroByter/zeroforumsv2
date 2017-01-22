<style>
    .icon{
        font-size: 24px;
    }

    .panel > .panel-body > h1{
        margin: 0px;
    }
</style>

<br>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-success">
                <div class="panel-heading"><i class="icon fa fa-user"></i> Registered users</div>
                <div class="panel-body">
                    <h1><?php echo count(accounts::get_all()) ?></h1>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-success">
                <div class="panel-heading"><i class="icon fa fa-clock-o"></i> Active sessions</div>
                <div class="panel-body">
                    <h1><?php echo count(sessions::get_all()) ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-success">
                <div class="panel-heading"><i class="icon fa fa-file-text"></i> Threads</div>
                <div class="panel-body">
                    <h1><?php echo count(forums::get_all_threads(0))-1 ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><i class="icon fa fa-gavel"></i> Banned users</div>
                <div class="panel-body">
                    <h1><?php echo count(accounts::get_all_banned()) ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="icon fa fa-tags"></i> Usertags</div>
                <div class="panel-body">
                    <h1><?php echo count(usertags::get_all()) ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><i class="icon fa fa-"></i> Number of logs</div>
                <div class="panel-body">
                    <h1><?php echo count(logs::get_all()) ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
