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
$colname_user_list = $HTTP_SESSION_VARS['cur_experiment'];
if (isset($HTTP_POST_VARS['exp'])) {
  $colname_user_list = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['exp'] : addslashes($HTTP_POST_VARS['exp']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_user_list = sprintf("SELECT users. user_id,  users.user_name,  users.password,  users.email,  users.logins FROM users WHERE users.experiment_id = %s  ORDER BY user_id ASC", $colname_user_list);
$user_list = mysql_query($query_user_list, $CAPM) or die(mysql_error());
$row_user_list = mysql_fetch_assoc($user_list);
$totalRows_user_list = mysql_num_rows($user_list);

mysql_select_db($database_CAPM, $CAPM);
$query_experiment = "SELECT experiment_id, experiment_name FROM experiment";
$experiment = mysql_query($query_experiment, $CAPM) or die(mysql_error());
$row_experiment = mysql_fetch_assoc($experiment);
$totalRows_experiment = mysql_num_rows($experiment);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Users</title>
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
                    Users<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
                  <p><span class="redtitles">Users</span><br>
                    To edit a user's information, click on the user's Name. </p>
                  <form name="form1" method="POST" action="users.php">
				  <p>To see the users who participated in other experiments
                  
                    <select name="exp">
                      <?php
do {  
?>
                      <option value="<?php echo $row_experiment['experiment_id']?>" <?php if($row_experiment['experiment_id'] == $colname_user_list){ echo " selected"; } ?>><?php echo $row_experiment['experiment_name']?></option>
                      <?php
} while ($row_experiment = mysql_fetch_assoc($experiment));
  $rows = mysql_num_rows($experiment);
  if($rows > 0) {
      mysql_data_seek($experiment, 0);
	  $row_experiment = mysql_fetch_assoc($experiment);
  }
?>
                    </select>
					<input type="submit" name="Submit" value="Get Info">
                  </form>
                  </P>
                  <table border="0" cellpadding="1" cellspacing="1">
                    <tr bgcolor="#990000" > 
                      <td><span class="topBar">User id</span></td>
                      <td><span class="topBar">UserName</span></td>
                      <td><span class="topBar">Password</span></td>
                      <td><span class="topBar">e-mail</span></td>
                      <td><span class="topBar"># ofLogins</span></td>
                    </tr>
                    <?php $row1 = "#d0d0f0";
							$row2 = "#f0d0d0";
							$CurrentColor= $row1; ?>
                    <?php do { ?>
                    <tr bgcolor="<?php echo $CurrentColor ?>"> 
                      <td><?php echo $row_user_list['user_id']; ?></td>
                      <td><a href="edit_user.php?uid=<?php echo $row_user_list['user_id']; ?>"><?php echo $row_user_list['user_name']; ?></a></td>
                      <td><?php echo $row_user_list['password']; ?></td>
                      <td><?php echo $row_user_list['email']; ?></td>
                      <td><div align="right"><?php echo $row_user_list['logins']; ?></div></td>
                    </tr>
                    <?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
						elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                    <?php } while ($row_user_list = mysql_fetch_assoc($user_list)); ?>
                  </table>
                  <!-- InstanceEndEditable --></td>
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
mysql_free_result($user_list);

mysql_free_result($experiment);
?>

