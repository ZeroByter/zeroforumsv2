<style>
    #errortext{
        font-size: 70px;
        color: grey;
    }

    #urlScrolDiv{
        overflow: hidden;
        margin-top: 60px;
        width: 80%;
        height: 40px;
        padding: 10px;
        background: white;
    }

    .lenny{
        position: fixed;
    }
</style>

<center>
    <h2>There is nothing here, and probably never will be.</h2>
    <h3>We looked everywhere, we even hacked the pentagon for you, we really did :(</h3>

    <span id="errortext"><i>#got404ed</i></span>

    <div id="urlScrolDiv"></div>
</center>

<script>
    var urls = ["/", "/forums", "/index", "/subforums", "/thread", "/user/login", "/user/register", "The pentagon"]
    var urlNumber = 0

    setInterval(function(){
        urlNumber += 1
        $("#urlScrolDiv").html("We looked through: <b>" + urls[urlNumber] + "</b>")
        if(urlNumber >= urls.length-1){
            urlNumber = 0
        }
    }, 1000)

    var easterEgg = false
    if(easterEgg){
        var lennyFace = "( ͡° ͜ʖ ͡°)"
        var isFocus = true
        window.onfocus = function(){
            isFocus = true
        }
        window.onblur = function(){
            isFocus = false
        }

        setInterval(function(){
            if(isFocus){
                var lenny = $("<div class='lenny'>"+lennyFace+"</div>")
                $("body").append(lenny)
                lenny.attr("data-rotate", "0")
                lenny.css("top", "0px")
                lenny.css("left", (Math.floor((Math.random() * window.innerWidth) + 0)) + "px")
            }
        }, 500)
        setInterval(function(){
            $(".lenny").each(function(i, v){
                $(v).css("top", parseInt($(v).css("top")) + 1)
                $(v).attr("data-rotate", parseInt($(v).attr("data-rotate")) + 1)
                $(v).css("transform", "rotate("+parseInt($(v).attr("data-rotate"))+"deg)")
                if(parseInt($(v).css("top")) > window.innerHeight){
                    $(v).remove()
                }
            })
        })
    }
</script>
