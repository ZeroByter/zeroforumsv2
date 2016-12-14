<div class="modal" id="loginModal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Login</h4>
            </div>
            <form id="loginForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control" id="usernameInput" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="usernameInput">Password</label>
                        <input type="password" class="form-control" id="passwordInput" placeholder="Password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/"><button type="button" class="btn btn-default">Home</button></a>
                    <a href="/user/register"><button type="button" class="btn btn-default">Register</button></a>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#loginModal").modal("show")

    $("#loginForm").submit(function(){
        $.post("/phpscripts/requests/login.php", {username: $("#usernameInput").val(), password: $("#passwordInput").val()}, function(html){
            console.log(html)
            if(html == "success"){
                <?php if($url["path"][0] == "user" && $url["path"][1] == "login"){ ?>
                    window.location = "/"
                <?php }else{ ?>
                    window.location = "<?php echo $_SERVER['REQUEST_URI'] ?>"
                <?php } ?>
            }else{
                $.notify({
                    message: html.split("error:")[1],
                },{
                    type: "danger",
                    z_index: 103001,
                    placement: {
                		from: "bottom",
                		align: "center"
                	},
                })
            }
        })
        return false
    })
</script>
