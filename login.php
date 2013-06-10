<?php require_once "/var/www/ChatSystem/trunk/libraries/constant.php"; ?>
<link rel="stylesheet" href="<?php echo SITE_URL;?>/./css/style.css">
<form id="login" action="../controller/controller.php?method=handleLogin" method="post">
    <h1 id="ff-proof" class="ribbon">Chat Server &nbsp;&nbsp;</h1>
    <div class="apple">
    	<div class="top"><span></span></div>
    	<div class="butt"><span></span></div>
    	<div class="bite"></div>
	</div>
    <fieldset id="inputs">
        <input id="username" type="text" onblur="if(this.value=='')this.value='username';" onfocus="if(this.value=='username')this.value='';" value="username" name="username"/>
        <?php
    		if(!empty($_REQUEST['user'])) 
    		{
    	?>
    		<div><?php echo $_REQUEST['user']; ?></div>
    	<?php 
    		}
    	?>
        <input id="password" type="password" onblur="if(this.value=='')this.value='password';" onfocus="if(this.value=='password')this.value='';" value="password" name="password"/>
    	<?php
    		if(!empty($_REQUEST['password'])) 
    		{
    	?>
    		<div><?php echo $_REQUEST['password']; ?></div>
    	<?php 
    		}
    	?>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" id="submit" value="Login"/>
       
    </fieldset>
    
</form>
