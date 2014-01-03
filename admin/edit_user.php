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
$colname_select_user = 1;
if (isset($HTTP_GET_VARS['uid'])) {
  $colname_select_user = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['uid'] : addslashes($HTTP_GET_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_select_user = sprintf("SELECT user_id, user_name, password, email, get_email FROM users WHERE user_id = %s", $colname_select_user);
$select_user = mysql_query($query_select_user, $CAPM) or die(mysql_error());
$row_select_user = mysql_fetch_assoc($select_user);
$totalRows_select_user = mysql_num_rows($select_user);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Edit User</title>
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
                  <div align="right"><!-- InstanceBeginEditable name="Description" -->Admin: 
                    Edit User<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" -->
<h3>Edit User Information</h3>
      <p>To edit the user information, enter new information in the fields provided. 
      </p>
      <ul>
        <li>The Password should contain<b> no blank spaces</b>. </li>
        <li>The EMail must be a valid e-mail address.</li>
        <li>The GetEMail field should be yes or no.</li>
      </ul>
         
 
      <form method=POST action="user_update.php">
                    <table border="0" cellpadding="3">
                      
          <tr> 
            <th align=center>Name</th>
            <th align=center>Password</th>
          </tr>
          <tr> <td align=center>
<input name='UN' type=text value="<?php echo $row_select_user['user_name']; ?>" size=20 maxlength=20></td><td align=center>
<input name='UP' type=text value="<?php echo $row_select_user['password']; ?>" size=20 maxlength=20></td>
 </tr>
        </table>
                    <table border="0" cellpadding="3">
                      
                      <tr>
            <th align=center>EMail</th>
            <th align=center>Get EMail</th>
         </tr>
          <tr><td align=center>
<input name='EM' type=text value="<?php echo $row_select_user['email']; ?>" size=30 maxlength=30></td><td align=center>
<input name='GM' type=text value="<?php echo $row_select_user['get_email']; ?>" size=5 maxlength=3></td>
          </tr>
        </table>
                    <input name='UID' type=hidden value="<?php echo $row_select_user['user_id']; ?>">
                    
                    <input type=submit value="Update" name="submit">
      </form><!-- InstanceEndEditable --></td>
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
mysql_free_result($select_user);
?>
