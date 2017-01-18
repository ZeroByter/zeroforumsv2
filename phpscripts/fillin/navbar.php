<nav class="navbar navbar-default" id="main_navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-contents" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbar-contents">
            <?php if(isset($currAccount) && accounts::is_staff($currAccount->id)){ ?>
                <ul class="navbar-form nav navbar-nav navbar-left">
                    <a href="/admin/" target=""><button type="submit" class="btn btn-primary" id="openAdminPanel">Admin panel</button></a>
                </ul>
            <?php } ?>

            <ul class="nav navbar-nav navbar-links navbar-center">
                <li><a href="/">Home</a></li>
                <li><a href="/forums">Forums</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php if(accounts::is_logged_in()){ ?>
                    <li class="dropdown">
                        <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown"><b><?php echo accounts::get_display_name(accounts::get_current_account()->id); ?></b> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <!--li><a href="javascript:">Action</a></li>
                            <li role="separator" class="divider"></li!-->
                            <li><a href="/user/profile/<?php echo $currAccount->id ?>">Profile</a></li>
                            <li><a href="/user/settings">Settings</a></li>
                            <li><a href="/phpscripts/requests/logout.php?next=<?php echo $_SERVER['REQUEST_URI'] ?>">Logout</a></li>
                        </ul>
                    </li>
                <?php }else{ ?>
                    <li><a href="/user/login?next=<?php echo $_SERVER['REQUEST_URI'] ?>"><b>Login</b></a></li>
                    <li><a href="/user/register"><b>Register</b></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    /*$("#openAdminPanel").click(function(){
        var w = 800
		var h = 600

		var width = screen.width
		var height = screen.height
		var left = ((width / 2) - (w / 2))
		var top = ((height / 2) - (h / 2))

		var newWindow = window.open("/admin/", "_blank", "width=" + w + ",height=" + h + ",top=" + top + ",left=" + left);
		newWindow.focus()
    })*/
</script>
