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
$colname_assets = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $colname_assets = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_assets = sprintf("SELECT asset_id, name FROM asset WHERE experiment_id = %s", $colname_assets);
$assets = mysql_query($query_assets, $CAPM) or die(mysql_error());
$row_assets = mysql_fetch_assoc($assets);
$totalRows_assets = mysql_num_rows($assets);


$SQL1 = "SELECT DISTINCT r.round_id, r.round_num, s.user_id, u.user_name, c.amount";
	$SQL2 = ' '; 
	$SQL4 = ' ';
	$SQL5 = ' ';
	$SQL6 = ' ';
	$uid = $HTTP_SESSION_VARS['user_id'];
do { 
	$row = $row_assets['name'];
	$row_id = $row_assets['asset_id'];
	$SQL2 = "$SQL2, T$row.quantity '$row' ";
	$SQL4 = "$SQL4, state T$row ";
	$SQL6 = "$SQL6 AND T$row.asset_id = $row_id AND T$row.round_id = r.round_id AND T$row.user_id = $uid";
} while ($row_assets = mysql_fetch_assoc($assets)); 
	
	$exp = $HTTP_SESSION_VARS['cur_experiment'];
			 
	$SQL3 = "FROM cash c, users u, round r, state s";
	$SQL5 = "WHERE s.user_id = c.user_id AND u.user_id = s.user_id AND s.round_id = r.round_id  AND c.round_id = r.round_id  AND u.user_id = $uid AND r.experiment_id = $exp";
					 
$State_SQL = "$SQL1 $SQL2 $SQL3 $SQL4 $SQL5 $SQL6";

$rid = $HTTP_SESSION_VARS['cur_round'];

mysql_select_db($database_CAPM, $CAPM);
$query_orders = "SELECT orders.order_id, round.round_num, orders.type, orders.price, orders.quantity, orders.order_time, asset.name FROM orders, asset, round WHERE orders.asset_id = asset.asset_id AND orders.executed = 0 AND orders.round_id = round.round_id AND orders.round_id = $rid AND orders.user_id = $uid";
$orders = mysql_query($query_orders, $CAPM) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);

mysql_select_db($database_CAPM, $CAPM);
$query_user_info = "SELECT users.user_id, users.email, users.get_email, users.logins FROM users WHERE users.user_id = $uid";
$user_info = mysql_query($query_user_info, $CAPM) or die(mysql_error());
$row_user_info = mysql_fetch_assoc($user_info);
$totalRows_user_info = mysql_num_rows($user_info);
?>

<?php
mysql_select_db($database_CAPM, $CAPM);
$user_state = mysql_query($State_SQL, $CAPM) or die(mysql_error());
$row_user_state = mysql_fetch_assoc($user_state);
$totalRows_user_state = mysql_num_rows($user_state);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Student | Status</title>
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
                  Status <!-- InstanceEndEditable --></div>
                </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
                  <p><span class="redtitles">Current Asset Status</span></p>
                  Asset Balances 
				   <table border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#990000" class="topBar"> 
                  <td height="19">Round</td>
                  <td>Cash</td>
                  <?php mysql_data_seek($assets,0);
			$row_assets = mysql_fetch_assoc($assets);?>
                  <?php do{ ?>
                  <td><?php echo $row_assets['name']; ?></td>
                  <?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
                </tr>
                <?php $row1 = "#d0d0f0";
		$row2 = "#f0d0d0";
		$CurrentColor= $row1; ?>
                <?php do { 
	
			mysql_data_seek($assets,0);
			$row_assets = mysql_fetch_assoc($assets);?>
                <tr bgcolor="<?php echo $CurrentColor ?>"> 
                  <td><?php echo $row_user_state['round_num']; ?></td>
                  <td> 
                    <?php if(isset($row_user_state['amount'])){ ?>
                    $<?php echo number_format($row_user_state['amount'],2); }?></td>
                  <?php do{ ?>
                  <td> 
                    <?php $color = $row_assets['name'];
				 echo $row_user_state[$color]; ?>
                  </td>
                  <?php } while ($row_assets = mysql_fetch_assoc($assets)); ?></tr>
                <?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                <?php } while ($row_user_state = mysql_fetch_assoc($user_state)); ?>
              </TABLE>
                  <p>&nbsp;</p>
                  
     
                  <p><span class="redtitles">Current Orders Placed</span><br>
         
<TABLE border="0" cellpadding="1" cellspacing="1">
	<tr bgcolor="#990000" class="topBar"> 
		<TD>OrderID</TD>
		<TD>Round</TD>
		<TD>Type</TD>
		<TD>Asset</TD>
		<TD>Price</TD>
		<TD>Quantity</TD>
		<TD>OrderTime</TD>
	</TR>
	<?php $row1 = "#d0d0f0";
		$row2 = "#f0d0d0";
		$CurrentColor= $row1; ?>
	<?php do{ ?><tr bgcolor="<?php echo $CurrentColor ?>">
		<TD><?php echo $row_orders['order_id']; ?></TD>
		<TD><?php echo $row_orders['round_num']; ?></TD>
		<TD><?php echo $row_orders['type']; ?></TD>
		<TD><?php echo $row_orders['name']; ?></TD>
		<TD><?php if(isset($row_orders['price'])){?>$<?php echo number_format ($row_orders['price'],2); ?><?php }?></TD>
		<TD><?php echo $row_orders['quantity']; ?></TD>
		<TD><?php echo $row_orders['order_time']; ?></TD>
	</TR>
	<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
	<?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
</TABLE>
 
     
      <p><span class="redtitles">Your User Information</span><br>
      <p>User Id: <?php echo $row_user_info['user_id']; ?><br>
	  Email: <?php echo $row_user_info['email']; ?><br>                           
	  Get Email: <?php echo $row_user_info['get_email']; ?><br>
      Logins: <?php echo $row_user_info['logins']; ?><br>
</p>
                  
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

mysql_free_result($user_state);
mysql_free_result($assets);

mysql_free_result($orders);

mysql_free_result($user_info);
?>
