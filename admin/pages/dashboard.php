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
                <div class="panel-heading"><i class="fa icon fa-user"></i> Registered users</div>
                <div class="panel-body">
                    <h1><?php echo count(accounts::get_all())-1 ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-success">
                <div class="panel-heading"><i class="fa icon fa-file-text"></i> Threads</div>
                <div class="panel-body">
                    <h1><?php echo count(forums::get_all_threads(0))-1 ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa icon fa-user"></i> bro we don't know yet</div>
                <div class="panel-body">
                    <h1></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-danger">
                <div class="panel-heading"><i class="fa icon fa-gavel"></i> Banned users</div>
                <div class="panel-body">
                    <h1><?php echo count(accounts::get_all_banned()) ?></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa icon fa-user"></i> ??</div>
                <div class="panel-body">
                    <h1></h1>
                </div>
            </div>
        </div>
		<div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa icon fa-user"></i> ??</div>
                <div class="panel-body">
                    <h1></h1>
                </div>
            </div>
        </div>
    </div>
</div>
