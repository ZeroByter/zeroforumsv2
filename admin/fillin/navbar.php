<nav class="navbar navbar-default">
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
            <ul class="nav navbar-nav navbar-left" style="margin-left: -30px;">
                <li><a href="/"><font color="red">Exit panel</font></a></li>
                <li><a href="/admin/">Dashboard</a></li>
                <?php if(usertags::user_has_permission($currUsertags, "settingstab")){ ?>
                    <li><a href="/admin/settings">Settings</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "forumstab")){ ?>
                    <li><a href="/admin/forums">Forums</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "userstab")){ ?>
                    <li><a href="/admin/users">Users</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "usertagstab")){ ?>
                    <li><a href="/admin/usertags">Usertags</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "permissionstab")){ ?>
                    <li><a href="/admin/permissions">Permissions</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "sessionstab")){ ?>
                    <li><a href="/admin/sessions">Sessions</a></li>
                <?php }if(usertags::user_has_permission($currUsertags, "logstab")){ ?>
                    <li><a href="/admin/logs">Logs</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
