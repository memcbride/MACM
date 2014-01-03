<?php require_once('Connections/CAPM.php'); ?>

<html>
<head>
<title>Install DB</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table 
	cellpadding="0"
	cellspacing="0" 
	border="0">
  <tr> 
    <td valign=TOP><img src="images/headerlogo.gif"> </td>
  </tr>
</table>
<?php
if(isset($HTTP_GET_VARS['msg'])){
	if($HTTP_GET_VARS['msq']== "NoLog"){
		echo "Your login name did not meet the specified requirements";
	}
}

if($CAPM == false){
echo "Your connection to your MySQL server is not set up correctly, please adjust the CAPM.php file in the Connections folder<BR>";
}else{ ?>
Please enter the login name and password you want to use to access the administration pages on the site.<BR>

<form action="Install_DB_action.php" method="post" name="admin">
  Admin Login name: 
  <input name="login" type="text">
  (min of 3 chars no_spaces)<BR>
  Admin Password: 
  <input name="pass" type="text">(no_spaces)<BR>
  <input name="submit" type="submit" value="Submit">
</form>

<?php }
?>
</body>
</html>
<?php

?>

