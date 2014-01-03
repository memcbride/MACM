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
<?php include "header.php"; ?>
<?php require_once('../Connections/CAPM.php'); ?>
<?php
$uid = $HTTP_SESSION_VARS['user_id'];

$rid_orders = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['rid'])) {
  $rid_orders = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['rid'] : addslashes($HTTP_POST_VARS['rid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_orders = sprintf("SELECT orders.order_id, orders.type, orders.price, orders.quantity, orders.executed, asset.name, round.round_num FROM orders, asset, round WHERE orders.round_id = round.round_id AND orders.asset_id = asset.asset_id  AND orders.user_id = $uid AND round.round_id = %s", $rid_orders);
$orders = mysql_query($query_orders, $CAPM) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

$eid = $HTTP_SESSION_VARS['cur_experiment'];

mysql_select_db($database_CAPM, $CAPM);
$query_round = "SELECT round.round_id, round.round_num FROM round WHERE round.experiment_id = $eid ORDER BY round_num";
$round = mysql_query($query_round, $CAPM) or die(mysql_error());
$row_round = mysql_fetch_assoc($round);
$totalRows_round = mysql_num_rows($round);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Student | Past Orders</title>
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
                    Past Orders<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
                  <form method="POST" action="past_orders.php" name="form">
                    <p>Select the round for which you want the past orders and 
                      then click on Get Info?<br>
                      <select name="rid">
                        <?php do { ?>
                        <option <?php if($rid_orders == $row_round['round_id']){echo " selected "; $disp_r = $row_round['round_num'];}?>value="<?php echo $row_round['round_id']?>">Round 
                        <?php echo $row_round['round_num']?></option>
                        <?php
} while ($row_round = mysql_fetch_assoc($round));
  $rows = mysql_num_rows($round);
  if($rows > 0) {
      mysql_data_seek($round, 0);
	  $row_round = mysql_fetch_assoc($round);
  }
?>
                      </select>
                      <input type="submit" name="Submit" value="Get Info">
                    </p>
                  </form>
                  <span class="redtitles">Past Orders for <?php echo $HTTP_SESSION_VARS['user_name']; ?> in Round <?php echo $disp_r; ?>:</span> 
                  <TABLE>
                    <TR BGCOLOR="#990000" class="topBar"> 
                      <TD ALIGN=center>OrderID</TD>
                      <TD ALIGN=center>Round</TD>
                      <TD ALIGN=center>Type</Td>
                      <TD ALIGN=center>Asset</TD>
                      <TD ALIGN=center>Price</TD>
                      <TD ALIGN=center>Quantity</TD>
                      <TD ALIGN=center>Executed</TD>
                    </TR>
                    <?php $row1 = "#d0d0f0";
		$row2 = "#f0d0d0";
		$CurrentColor= $row1; ?>
                    <?php do { ?>
                    <TR bgcolor="<?php echo $CurrentColor ?>"> 
                      <TD ALIGN=center><?php echo $row_orders['order_id']; ?></TD>
                      <TD ALIGN=center><?php echo $row_orders['round_num']; ?></TD>
                      <TD ALIGN=center><?php echo $row_orders['type']; ?></TD>
                      <TD ALIGN=center><?php echo $row_orders['name']; ?></TD>
                      <TD ALIGN=center>
                        <?php if(isset($row_orders['price'])){?>
                        $<?php echo number_format ($row_orders['price'],2); ?>
                        <?php }?>
                      </TD>
                      <TD ALIGN=center><?php echo $row_orders['quantity']; ?></TD>
                      <TD ALIGN=center><?php echo $row_orders['executed']; ?></TD>
                    </TR>
                    <?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
                  </TABLE>
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
mysql_free_result($orders);

mysql_free_result($round);
?>
