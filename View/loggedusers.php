<?php
for($i =0 ;$i < count($b) ;$i ++)
{
	echo "<a href='javascript:void(0)' onclick='abc(". $b[$i]['id'].")'><img src ='".SITE_URL."/images/grl.png' height=20 width=20 />".$b[$i]['username']."</a><br/>";
}
?>
