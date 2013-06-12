<?php require_once "/var/www/ChatSystem/trunk/libraries/constant.php"; ?>
<script src="<?php echo SITE_URL;?>/js/jquery.tools.min.js"></script>
<?php
//header("Refresh: 1; http://www.chatsystem.com/View/chat.php");
?>
<script>
var uid;

function abc(id)
{
	
	uid = id;
        alert(uid);
	$("#output1").append("<table><tr><td id='mess"+uid+"' style='border:1px solid red;width:230px;height:250px;float:left;border-radius:10px;overflow-y:auto;'></td></tr><tr ><td id='typemessage'><form name='message' action='#' id='frmid'><textarea rows='2' cols='29' id='"+uid+"' name='usermsg' onkeypress='searchKeyPress(event)'></textarea></form></td></tr></table>");

setInterval(fetchcomment, 5000);


            		 
        	
}
$(document).ready(function()
{
	function loggedusers()
	{
		$.ajax
		({
			type: "POST",
	        	url: '../controller/controller.php?method=loggedusers',
         		success: function(data)
         		{
			
				$("#output").html($.trim(data));
				
         		}
		});
	}
	loggedusers();
	setInterval(loggedusers, 5000);
});
</script>
<script>
function searchKeyPress(e)
{
	if (window.event)
	{
		e = window.event;
	}
	if (e.keyCode == 13)
	{
		entercomment();
	}
}

function entercomment()
{
	$.ajax
	({

		type: "POST",
	        url: '../controller/controller.php?method=comment&recid='+uid,
		data:$("#frmid").serialize(),
         	success: function(data)
         	{
			var resp=jQuery.parseJSON($.trim(data));
			$.each(resp, function(key, val) {
			if(key % 2 ==0)
			{
				$("#mess"+uid).append(val+":");
				$("#mess"+uid).append(" ");
			}	
			else
			{
				$("#mess"+uid).append(val);
				$("#mess"+uid).append(" ");
			}
			});
         	},
		complete: function() 
		{
			$("#mess"+uid).append("<br/>");
			document.getElementById(uid).value='';
			
		}       
	});
}

function fetchcomment()
{
	
	$.ajax
	({

		type: "POST",
	        url: '../controller/controller.php?method=comment',
		
         	success: function(data)
         	{
			var resp=jQuery.parseJSON($.trim(data));
			$.each(resp, function(key, val) {
			$("#chatmessage").append(val);
			$("#chatmessage").append(" ");	
			});
         	},
		complete: function() 
		{
			$("#chatmessage").append("<br/>");
			document.getElementById('usermsg').value='';
			
		}       
	});
}


</script>
<style>


#output
{
	border:1px solid red;
	width:8%;
	height:50%;
	float:left;
	border-radius:10px;
	overflow-y:auto;
	
}
#output1
{
	
	width:80%;
	height:50%;
	float:left;
	border-radius:10px;
	overflow-y:auto;
	
	
}
#typemessage
{
	width:230px;
	margin-top:250px;
	border-radius:10px;
	overflow-y:auto;
}
</style>

<a href="../controller/controller.php?method=logout" >Logout</a>

<div id="output"></div>
<div id="output1"></div>


