<?php require_once "/var/www/ChatSystem/trunk/libraries/constant.php"; ?>
<script src="<?php echo SITE_URL;?>/js/jquery.tools.min.js"></script>
<?php
//header("Refresh: 1; http://www.chatsystem.com/View/chat.php");
?>

<script>
$(document).ready(function()
{

	$.ajax
	({
		type: "POST",
	        url: '../controller/controller.php?method=loggedusers',
         	success: function(data)
         	{
			var resp=jQuery.parseJSON($.trim(data));
			$.each(resp, function(key, val) {

				$("#output").append("<img src ='<?php echo SITE_URL;?>/images/grl.png' height=20 width=20 />"+val+"<br/>");
			});
         	}
	});
});
</script>
<script>
function searchKeyPress(e){
    if (window.event) {
        e = window.event;
    }
    if (e.keyCode == 13) {
	
	entercomment();
}
}
function entercomment()
{
	$.ajax
	({
		type: "POST",
	        url: '../controller/controller.php?method=comment',
		data:$("#frmid").serialize(),
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
#chatmessage
{
	border:1px solid red;
	width:20%;
	height:50%;
	float:left;
	border-radius:10px;
	overflow-y:auto;
}
#output
{
	
	width:8%;
	height:50%;
	float:left;
	border-radius:10px;
	overflow-y:auto;
	
}
#typemessage
{
	
	width:20%;
	margin-top:25%;
	height:15%;
	border-radius:5px;
	
}
</style>
<?php
print_r($_SESSION); 
?>
<a href="../controller/controller.php?method=logout" >Logout</a>
<div id="chatmessage"></div>
<div id="output"></div>
<div id="typemessage">
<form name="message" action="#" id="frmid" >
<textarea rows="4" cols="32" id="usermsg" name="usermsg" onkeypress="searchKeyPress(event)"></textarea>
</form>
</div>

