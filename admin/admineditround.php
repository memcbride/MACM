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
$clearing = $HTTP_GET_VARS['mid'];

mysql_select_db($database_CAPM, $CAPM);
$query_edit_market = "SELECT asset.name, clearing.clearing_id, clearing.volume, clearing.price, round.round_num, round.round_id FROM asset, clearing, round WHERE asset.asset_id = clearing.asset_id AND round.round_id = clearing.round_id AND clearing.clearing_id = $clearing";
$edit_market = mysql_query($query_edit_market, $CAPM) or die(mysql_error());
$row_edit_market = mysql_fetch_assoc($edit_market);
$totalRows_edit_market = mysql_num_rows($edit_market);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Edit Market Round</title>
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
                    Edit Market Round<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --><span class="redtitles">Edit Market Round</span> 
<p>To edit the experiment information, enter new values in the fields provided 
        and then press the Update button.</p>
      <p>Each of the fields should be fairly obvious. </p>
         
 
      <form method='POST' action="updatemarket.php">
        <table border="0" cellpadding="3">
          <tr> 
                        <th align=center>MarketID</th>
            <th align=center>Round</th>
            <th align=center>Asset</th>
            <th align=center>Volume</th>
            <th align=center>Price</th>
         </tr>
          <tr> 
                        <td align=center><?php echo $row_edit_market['clearing_id']; ?></td>
                        <td align=center><?php echo $row_edit_market['round_num']; ?></td>
                        <td align=center><?php echo $row_edit_market['name']; ?></td>
                        <td align=center><input type=text name="volume" value="<?php echo $row_edit_market['volume']; ?>" maxlength='7' size='7'></td><td align=center><input type=text name="price" value="<?php echo $row_edit_market['price']; ?>" maxlength='7' size='7'></td>
 </tr>
        </table>
                    <input type=hidden name='marketid' value="<?php echo $row_edit_market['clearing_id']; ?>">
                    <input type=hidden name='round' value="<?php echo $row_edit_market['round_id']; ?>">
                    <input type=submit value="Update" name="submit">
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
mysql_free_result($edit_market);
?>
