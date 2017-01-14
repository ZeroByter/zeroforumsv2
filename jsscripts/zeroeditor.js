function refreshStringSelection(){ //refresh the vars for selection text and such
	stringSelected = $("#textarea").val().slice($("#textarea").prop("selectionStart"), $("#textarea").prop("selectionEnd"))
    stringBefore = $("#textarea").val().slice(0, textarea.selectionStart)
    stringAfter = $("#textarea").val().slice($("#textarea").prop("selectionStart") + stringSelected.length)
}

function addBBTag(tagName, properties="", text=""){ //add a bbtag to the textarea
	refreshStringSelection()
    var startPos = $("#textarea").prop("selectionStart")
    $("#textarea").val(stringBefore + "["+tagName+properties+"]" + stringSelected + text + "[/"+tagName+"]" + stringAfter)
	$("#textarea").prop("selectionStart", startPos + tagName.length + properties.length + 2)
	$("#textarea").prop("selectionEnd", startPos + tagName.length + properties.length + 2)
	$("#textarea").focus()
}

$("body").ready(function(){
	$("#bold").click(function(){ //when user clicks on bold
		addBBTag("b")
	})
	$("#italic").click(function(){ //when user clicks on italic
		addBBTag("i")
	})
	$("#underline").click(function(){ //when user clicks on italic
		addBBTag("u")
	})
	$("#strikethrough").click(function(){ //when user clicks on italic
		addBBTag("s")
	})
	$("#alignleft").click(function(){
	    addBBTag("align", "=left")
	})
	$("#aligncenter").click(function(){
	    addBBTag("center")
	})
	$("#alignright").click(function(){
	    addBBTag("align", "=right")
	})
	$("#listpoints").click(function(){
	    addBBTag("list", "=points", "\n[*] List item\n")
	})
	$("#listnumbers").click(function(){
	    addBBTag("list", "=numbers", "\n[*] List item\n")
	})
	$("#link").click(function(){
	    addBBTag("link", ' href=')
	})
	$("#youtube").click(function(){
		refreshStringSelection()
		if(stringSelected == ""){
			addBBTag("youtube")
		}else{
			if(stringSelected.split("?v=").length == 2){
				$("#textarea").val($("#textarea").val().replace(stringSelected, ""))
				addBBTag("youtube", "", stringSelected.split("?v=")[1])
			}else{
				addBBTag("youtube")
			}
		}
	})
})

function remove_bbcode(text){ //prevent xss and fix bbcode tags
	format_search =  [
		/\[b\]([\s\S]*?)\[\/b\]/ig,
		/\[i\]([\s\S]*?)\[\/i\]/ig,
		/\[u\]([\s\S]*?)\[\/u\]/ig,
		/\[s\]([\s\S]*?)\[\/s\]/ig,
        /\[align=left\]([\s\S]*?)\[\/align\]/ig,
		/\[center\]([\s\S]*?)\[\/center\]/ig,
        /\[align=center\]([\s\S]*?)\[\/align\]/ig,
        /\[align=right\]([\s\S]*?)\[\/align\]/ig,
        /\[list=points\]([\s\S]*?)\[\/list\]/ig,
        /\[list=numbers\]([\s\S]*?)\[\/list\]/ig,
        /\[\*\](.*?)(\n|\r\n?)/ig,
        /\[link href=(.*?)\](.*?)\[\/link\]/ig,
		/\[youtube\](.*?)\[\/youtube\]/ig,
	]
	format_replace = [
		'$1',
		'$1',
		'$1',
		'$1',
		'$1',
		'$1',
		'$1',
        '$1',
        '$1',
        '$1',
        '$1',
        '$2',
		'$1',
	]
	for (var i =0;i<format_search.length;i++){
		text = text.replace(format_search[i], format_replace[i]);
	}
	return text
}

function filter_bbcode(text){ //prevent xss and fix bbcode tags
	if(!text.endsWith("\n")){
		text = text + "\n"
	}

	format_search =  [
		/\[b\]([\s\S]*?)\[\/b\]/ig,
		/\[i\]([\s\S]*?)\[\/i\]/ig,
		/\[u\]([\s\S]*?)\[\/u\]/ig,
		/\[s\]([\s\S]*?)\[\/s\]/ig,
        /\[align=left\]([\s\S]*?)\[\/align\]/ig,
		/\[center\]([\s\S]*?)\[\/center\]/ig,
        /\[align=center\]([\s\S]*?)\[\/align\]/ig,
        /\[align=right\]([\s\S]*?)\[\/align\]/ig,
        /\[list=points\]([\s\S]*?)\[\/list\]/ig,
        /\[list=numbers\]([\s\S]*?)\[\/list\]/ig,
        /\[\*\](.*?)(\n|\r\n?)/ig,
        /\[link href=(.*?)\](.*?)\[\/link\]/ig,
		/\[youtube\](.*?)\[\/youtube\]/ig,
	]
	format_replace = [
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<span style="text-decoration:underline;">$1</span>',
		'<s>$1</s>',
		'<span style="text-align:left;display:block;">$1</span>',
		'<center>$1</center>',
		'<center>$1</center>',
        '<span style="text-align:right;display:block;">$1</span>',
        '<ul>$1</ul>',
        '<ol>$1</ol>',
        '<li>$1</li>',
        '<a href=$1>$2</a>',
		'<iframe width="420" height="315" src="https://www.youtube.com/embed/$1"></iframe>',
	]
	for (var i =0;i<format_search.length;i++){
		text = text.replace(format_search[i], format_replace[i]);
	}
	text = text.replace(/\n/g, "<br>")
	if(text.startsWith("<br>")){
		text = text.replace("<br>", "")
	}
	text = text.replace(/<br>$/g, "")
	return text
}

$("body").ready(function(){
	$("#preview").click(function(){
		$("#textarea").parent().css("display", "none")
		$("#texteditor_toolbar").parent().css("display", "none")
		$("#textarea_preview_div").css("display", "block")
	})
	$("#editor").click(function(){
		$("#textarea").parent().css("display", "block")
		$("#texteditor_toolbar").parent().css("display", "block")
		$("#textarea_preview_div").css("display", "none")
	})
	$("#textarea").bind("keyup focus", function(){ //when textarea gets a keyup or focus
		text = $(this).val()
		text = filter_bbcode(text)

		$("#preview_text").html(text)
	})
})

function setEditorString(string){
	string = string.replace(/\\n/ig, "<br>")
	$("#textarea").val(string)
	$("#textarea").focus()
	$("#preview_text").html(filter_bbcode(string))
}
function getEditorString(){
	var text = $("#textarea").val()
	text = text.replace(/javascript:/g, "javascript : ")
	return text
}
