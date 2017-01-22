<style>
	#setDefault, #toggleStaff, #delete{
		margin-bottom: 14px;
	}
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <a href="/admin/usertags"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
			<form id="usertagForm">
				<div class="input-group form-group">
					<span class="input-group-addon">Name</span>
					<input class="form-control" placeholder="Name" id="nameInput">
				</div>
				<div class="input-group form-group">
					<span class="input-group-addon">Listorder</span>
					<input class="form-control" placeholder="Listorder" id="listorderInput" value="1">
				</div>
				<center><button type="submit" class="btn btn-default">Create new usertag</button></center>
			</form>
        </div>
    </div>
</div>

<script>
	$("#usertagForm").submit(function(){
		$.post("/admin/requests/createusertag.php", {name: $("#nameInput").val(), listorder: $("#listorderInput").val()}, function(html){
			if(html.split(":")[0] == "success"){
				window.location = "/admin/usertags/" + html.split(":")[1]
			}else{
				$.notify({
					message: html,
				},{
					type: "danger",
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
