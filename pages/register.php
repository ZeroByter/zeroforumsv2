<div class="modal" id="loginModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">Register</h4>
            </div>
            <form id="loginForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="usernameInput">Username</label>
                        <input type="text" class="form-control" id="usernameInput" placeholder="Username" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="passwordInput">Password</label>
                        <input type="password" class="form-control" id="passwordInput" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPasswordInput">Confirm password</label>
                        <input type="password" class="form-control" id="confirmPasswordInput" placeholder="Confirm password" required>
                    </div>
                    <center><h4>Optional:</h4></center>
                    <div class="form-group">
                        <label for="displayNameInput">Display name</label>
                        <input type="text" class="form-control" id="displayNameInput" placeholder="Display name" maxlength="12">
                    </div>
                    <div class="form-group" style="display:none;"><!--Email is not working!!-->
                        <label for="emailInput">Email</label>
                        <input type="email" class="form-control" id="emailInput" placeholder="Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/user/login"><button type="button" class="btn btn-default">Login</button></a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#loginModal").modal("show")
	$("#loginModal").on("hide.bs.modal", function(){
		window.location = "/"
	})

    $("#loginForm").submit(function(){
        if($("#passwordInput").val() != $("#confirmPasswordInput").val()){
            $.notify({
                message: "Password and password confirmation do not match!",
            },{
                type: "danger",
                z_index: 103001,
                placement: {
                    from: "bottom",
                    align: "center"
                },
            })
            return false
        }
		if(/\s/.test($("#usernameInput").val())){
			$.notify({
                message: "You can't have any spaces in your username!",
            },{
                type: "danger",
                z_index: 103001,
                placement: {
                    from: "bottom",
                    align: "center"
                },
            })
            return false
		}

        var inputArray = {
            username: $("#usernameInput").val(),
            password: $("#passwordInput").val(),
            displayname: $("#displayNameInput").val(),
            email: $("#emailInput").val(),
        }

        $.post("/phpscripts/requests/register.php", inputArray, function(html){
            console.log(html)
            if(html == "success"){
                window.location = "/"
            }else{
                $.notify({
                    message: html,
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
