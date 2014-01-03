<?php 
/*
Copyright (c) 1999 - 2003 Mark E. McBride & Christian Ratterman. All Rights Reserved.

You may study, use, modify, and distribute this software for any 
purpose within an academic environment provided that this copyright 
notice appears in all copies. Business and Corporate use requires 
expressed permission from the authors listed below.

@Authors: Mark E. McBride & Christian Ratterman
@Contact: mcbridme@muohio.edu
@Version: 2.1
@date: May 6, 2003
*/
?>
<?php include "header.php" ?>
<?php require_once('../Connections/CAPM.php'); ?>
<?php
$uid = $HTTP_SESSION_VARS['user_id'];

mysql_select_db($database_CAPM, $CAPM);
$query_user = "SELECT users.user_name, users.password, users.email, users.get_email FROM users WHERE users.user_id = $uid";
$user = mysql_query($query_user, $CAPM) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Student | Edit Your Info</title>
<!-- InstanceEndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable --> 
<style type="text/css">
<!--
.navtitles {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}

a.Snav:Link { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #990000}
a.Snav:visited{ font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #990000}
a.Snav:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #CCCCCC}
a.Snav:hover { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #CCCCCC}
p {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
.topBar {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	color: #FFFFFF;
}
.redtitles {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #990000;
}

-->
</style>
<style type="text/css">
<!--
.text {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../images/headerlogo.gif"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="150" valign="top">
		  <BR>
		  <?php include('Snav.php'); ?>
		  <BR>
            <?php 
		  if ($HTTP_SESSION_VARS['status'] == 'admin')
		  {
		  include('EXPnav.php');
		  } ?>
            <BR>
</td>
          <td width="20">&nbsp;</td>
		  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="20">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><span class="redtitles">
                  <div align="right"><!-- InstanceBeginEditable name="Description" -->Student: 
                    Edit Your Info<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --><span class="redtitles">Edit Your Info:</span>
      <p>To edit the user information, enter new information in the fields provided. 
      </p>
      <ul>
                    <li>The Password should contain<b> no blank spaces</b> and 
                      can contain characters and numbers. </li>
        <li>The EMail must be a valid e-mail address.</li>
      </ul>
      <p>   
 </p>
      <form method=POST action="change_info_action.php">
        <table border="0" cellpadding="3">
          <tr> 
            <th align=center>Name</th>
            <th align=center>Password</th>
            <th align=center>EMail</th>
            <th align=center>Get EMail</th>
          </tr>
          <tr> 
		                <td align=center><?php echo $row_user['user_name']; ?></td>       
		  <td align=center><input name="password" type="text" value="<?php echo $row_user['password']; ?>" size="10" maxlength="10"></td>       
		  <td align=center><input name="email" type="text" value="<?php echo $row_user['email']; ?>" size="30"  maxlength="30"></td>       
		  <td align=center><select name="get_email">
                            <option <?php if(trim($row_user['get_email']) == 'yes'){ echo " selected ";} ?> value="yes">Yes</option>
                            <option <?php if(trim($row_user['get_email']) == 'no'){ echo " selected ";} ?> value="no">No</option>
                          </select></td>      
 </tr>
        </table>
                    <input name="uid" type="hidden" value="<?php echo $HTTP_SESSION_VARS['user_id']; ?>" >   
        <input type="submit" value="Update" name="submit">
      </form>
      <p>&nbsp;</p><!-- InstanceEndEditable --></td>
              </tr>
              <tr>
                <td height="20">&nbsp;</td>
              </tr>
            </table></td>
          <td width="20">&nbsp;</td>
        </tr>
      </table>
</td>
  </tr>
  <tr>
    <td><div align="center">Copyright &copy; 2000 - 2002, Mark E. McBride &amp; 
        Christian Ratterman, All Rights Reserved. </div></td>
  </tr>
</table>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($user);
?>
