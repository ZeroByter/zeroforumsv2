<style>
	.permissionBtn[data-state="true"]{
		color: #fff;
		background-color: #5cb85c;
		border-color: #4cae4c;
	}
	.permissionBtn[data-state="false"]{
		color: #fff;
		background-color: #d9534f;
		border-color: #d43f3a;
	}
</style>

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <a href="/admin/forums"><button class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>  Go back</button></a>
            <br><br><br>
            <form id="submitForm">
                <div class="input-group form-group">
                    <span class="input-group-addon">Title</span>
                    <input type="text" class="form-control" id="title" required>
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">Description</span>
                    <input type="text" class="form-control" id="text">
                </div>
                <div class="input-group form-group">
                    <span class="input-group-addon">List order</span>
                    <input type="number" class="form-control" id="listorder" required>
                </div>
				<button type="submit" class="btn btn-primary">Create new subforum</button>
            </form>
        </div>
    </div>
</div>

<script>
	$("#submitForm").submit(function(){
		$.post("/admin/requests/createsubforum.php", {parent: <?php echo $forum->id ?>, title: $("#title").val(), text: $("#text").val(), listorder: $("#listorder").val()}, function(html){
			console.log(html)
			if(html.split(":")[0] == "success"){
				window.location = "/admin/forums/" + html.split(":")[1]
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
