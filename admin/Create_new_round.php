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
$colname_asset = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $colname_asset = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_asset = sprintf("SELECT asset_id, name FROM asset WHERE experiment_id = %s", $colname_asset);
$asset = mysql_query($query_asset, $CAPM) or die(mysql_error());
$row_asset = mysql_fetch_assoc($asset);
$totalRows_asset = mysql_num_rows($asset);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Create a new Round</title>
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
                    Create a new Round<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
                  <p>&nbsp;</p>
				  <?php $new_round_num = ($HTTP_POST_VARS['rnum'] + 1); ?>
                  <form name="form1" method="post" action="insert_new_round.php">
                    <p>New Round Number: <?php echo $new_round_num; ?>
                      <input name="round_num" type="hidden" value="<?php echo $new_round_num; ?>">
                      <input name="old_rid" type="hidden" value="<?php echo $HTTP_POST_VARS['rid']; ?>">
                      <input name="exid" type="hidden" value="<?php echo $HTTP_SESSION_VARS['cur_experiment']; ?>">
                      <BR>
                      Assets for Experiment:
                      <BR>
					  <?php do { ?>
                      &nbsp;&nbsp;&nbsp;<?php echo $row_asset['name']; ?><BR>
                      <?php } while ($row_asset = mysql_fetch_assoc($asset)); ?>
                   </p>
                    <p>End Time 
                      <input name="end_time" type="text" id="end_time" value="0000-00-00 00:00:00" size="30">
                      YYYY-MM-DD HH:MM:SS</p>
                    <p>
                      <input type="submit" name="Submit" value="Create this Round">
                    </p>
                  </form>
                  <p>&nbsp;</p>
                  <p>&nbsp;</p>
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
mysql_free_result($asset);
?>

