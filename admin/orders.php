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
$colname_drop_rounds = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $colname_drop_rounds = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_drop_rounds = sprintf("SELECT round_id, round_num FROM round WHERE experiment_id = %s ORDER BY round_num", $colname_drop_rounds);
$drop_rounds = mysql_query($query_drop_rounds, $CAPM) or die(mysql_error());
$row_drop_rounds = mysql_fetch_assoc($drop_rounds);
$totalRows_drop_rounds = mysql_num_rows($drop_rounds);

$colname_round_num = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['round'])) {
  $colname_round_num = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_round_num = sprintf("SELECT round_num FROM round WHERE round_id = %s", $colname_round_num);
$round_num = mysql_query($query_round_num, $CAPM) or die(mysql_error());
$row_round_num = mysql_fetch_assoc($round_num);
$totalRows_round_num = mysql_num_rows($round_num);

$rid = $colname_round_num;
if(isset($HTTP_POST_VARS['orderby'])){
	$order = $HTTP_POST_VARS['orderby'];
}else{
	$order = "order_id";
}
$exp =  $HTTP_SESSION_VARS['cur_experiment'];

mysql_select_db($database_CAPM, $CAPM);
$query_orders = "SELECT orders.order_id, orders.user_id, orders.order_time, orders.price, orders.quantity, asset.name, round.round_num, users.user_name, orders.type FROM orders, asset, round, users WHERE orders.asset_id = asset.asset_id AND orders.round_id = round.round_id AND orders.user_id = users.user_id AND round.experiment_id = $exp AND orders.round_id = $rid ORDER BY $order";
$orders = mysql_query($query_orders, $CAPM) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Orders</title>
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
                  <div align="right"><!-- InstanceBeginEditable name="Description" --> 
                    Admin: Orders <!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --><span class="redtitles">Orders</span> 
                  <form method="POST" action="orders.php" name="">
                    <p>Select the round for which you want the orders and then 
                      click on Get Info? 
                      <select name="round">
                        <?php do { ?>
                        <option value="<?php echo $row_drop_rounds['round_id']?>" <?php if($row_drop_rounds['round_id'] == $colname_round_num) { echo "selected"; } ?>>Round 
                        <?php echo $row_drop_rounds['round_num']?></option>
                        <?php
						} while ($row_drop_rounds = mysql_fetch_assoc($drop_rounds));
  								$rows = mysql_num_rows($drop_rounds);
  								if($rows > 0) {
      					mysql_data_seek($drop_rounds, 0);
	  						$row_drop_rounds = mysql_fetch_assoc($drop_rounds);
  					}
?>
                      </select>
                      and sort the list by 
                      <select name="orderby">
                        <option value="order_id" selected>OrderID</option>
                        <option value="user_id">UserID</option>
                      </select>
                    </p>
                    <p> 
<input type="submit" name="Submit" value="Get Info">
                    </p>
                  </form>
                  <span class="redtitles">Market Orders for Round <?php echo $row_round_num['round_num']; ?> Sorted by <?php echo $order; ?> </span> 
<p>To edit or delete a particular order, click on the OrderID 
                    Number.</p>
                  <table width="100%" border="0" cellspacing="2" cellpadding="2">
                    <tr bgcolor="#990000" class="topBar"> 
                      <td>OrderID</td>
                      <td>UserId</td>
                      <td>UserName</td>
                      <td>Round</td>
                      <td>Asset</td>
					  <td>Type</td>
                      <td>Price</td>
                      <td>Quantity</td>
                      <td>Order Time</td>
                    </tr>
					<?php $row1 = "#d0d0f0";
							$row2 = "#f0d0d0";
							$CurrentColor= $row1; ?>
                    <?php do { ?>
                    <tr bgcolor="<?php echo $CurrentColor ?>"> 
                      <td><a href="edit_order.php?oid=<?php echo $row_orders['order_id']; ?>"><?php echo $row_orders['order_id']; ?></a></td>
                      <td><?php echo $row_orders['user_id']; ?></td>
                      <td><?php echo $row_orders['user_name']; ?></td>
                      <td><?php echo $row_orders['round_num']; ?></td>
                      <td><?php echo $row_orders['name']; ?></td>
					  <td><?php echo $row_orders['type']; ?></td>
                      <td><?php if(isset($row_orders['price'])){?>$<?php echo number_format ($row_orders['price'],2); ?><?php }?></td>
                      <td><?php echo $row_orders['quantity']; ?></td>
                      <td><?php echo $row_orders['order_time']; ?></td>
                    </tr>
					<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
						elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                    <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
                  </table>
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
mysql_free_result($drop_rounds);

mysql_free_result($orders);
?>

