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
$rid_Values = 1;
if (isset($HTTP_GET_VARS['rid'])) {
  $rid_Values = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['rid'] : addslashes($HTTP_GET_VARS['rid']);
}
$uid_Values = 1;
if (isset($HTTP_GET_VARS['uid'])) {
  $uid_Values = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['uid'] : addslashes($HTTP_GET_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Values = "SELECT state.quantity, state.asset_id, asset.name, state.state_id FROM state, asset WHERE state.round_id = $rid_Values AND state.user_id = $uid_Values AND state.asset_id = asset.asset_id";
$Values = mysql_query($query_Values, $CAPM) or die(mysql_error());
$row_Values = mysql_fetch_assoc($Values);
$totalRows_Values = mysql_num_rows($Values);

$rid_cash = 1;
if (isset($HTTP_GET_VARS['rid'])) {
  $rid_cash = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['rid'] : addslashes($HTTP_GET_VARS['rid']);
}
$uid_cash = 1;
if (isset($HTTP_GET_VARS['uid'])) {
  $uid_cash = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['uid'] : addslashes($HTTP_GET_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_cash = sprintf("SELECT cash.amount, users.user_name FROM cash, users WHERE cash.user_id = %s  AND cash.round_id = %s AND users.user_id = cash.user_id", $uid_cash,$rid_cash);
$cash = mysql_query($query_cash, $CAPM) or die(mysql_error());
$row_cash = mysql_fetch_assoc($cash);
$totalRows_cash = mysql_num_rows($cash);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Edit User State</title>
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
                    Edit User State<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> <span class="redtitles">Edit 
                  State Information for <?php echo $row_cash['user_name']; ?></span> 
                  <p>To edit the user state information, enter new information 
                    in the fields provided. Most the fields are self-explanatory. 
                  </p>
                  <form method=POST action="update_user_states.php">
                    <table border="0" cellpadding="3">
                      <tr> 
                        <th align=center>Cash</th>
        <!--- Loop the asset names --->
						<?php do { ?>
                        <th align=center><?php echo $row_Values['name']; ?></th>
                        <?php } while ($row_Values = mysql_fetch_assoc($Values)); ?>
                      </tr>
                      <tr> 
                        <td align=center><input name='Cash' type=text value="<?php echo $row_cash['amount']; ?>" size=12 maxlength=10></td>
                        <?php mysql_data_seek($Values,0); 
		  $row_Values = mysql_fetch_assoc($Values); ?>
<!--- Loop the current asset quantities for this user --->
                        <?php do { ?>
                        <td align=center> 
                          <input name='<?php echo $row_Values['name']; ?><?php echo $row_Values['state_id']; ?>' type=text value="<?php echo $row_Values['quantity']; ?>" size=7 maxlength=7> 
                        </td>
                        <?php } while ($row_Values = mysql_fetch_assoc($Values)); ?>
                      </tr>
                    </table>
                    <input name='uid' type=hidden value="<?php echo $HTTP_GET_VARS['uid']; ?>">
                    <input name='rid' type=hidden value="<?php echo $HTTP_GET_VARS['rid']; ?>">
                    <input type=submit value="Update" name="submit">
                  </form>
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
mysql_free_result($Values);

mysql_free_result($cash);
?>

