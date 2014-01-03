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
<?php require_once('../Connections/CAPM.php'); 
include "header.php";

$expid_users = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid_users = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_users = sprintf("SELECT users.user_id, users.user_name FROM users WHERE users.experiment_id = %s AND users.user_type = 'student' ORDER BY users.user_name", $expid_users);
$users = mysql_query($query_users, $CAPM) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

$expid_drop_round = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid_drop_round = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_drop_round = sprintf("SELECT round.round_id, round.round_num FROM round WHERE round.experiment_id = %s ORDER BY round_num", $expid_drop_round);
$drop_round = mysql_query($query_drop_round, $CAPM) or die(mysql_error());
$row_drop_round = mysql_fetch_assoc($drop_round);
$totalRows_drop_round = mysql_num_rows($drop_round);

$user_tracked_user = $row_users['user_id'];
if (isset($HTTP_POST_VARS['uid'])) {
  $user_tracked_user = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['uid'] : addslashes($HTTP_POST_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_tracked_user = sprintf("SELECT users.user_id, users.user_name FROM users WHERE users.user_id = %s", $user_tracked_user);
$tracked_user = mysql_query($query_tracked_user, $CAPM) or die(mysql_error());
$row_tracked_user = mysql_fetch_assoc($tracked_user);
$totalRows_tracked_user = mysql_num_rows($tracked_user);

$rid_tracked_round = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['round'])) {
  $rid_tracked_round = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_tracked_round = sprintf("SELECT round.round_num, round.round_id FROM round WHERE round.round_id = %s", $rid_tracked_round);
$tracked_round = mysql_query($query_tracked_round, $CAPM) or die(mysql_error());
$row_tracked_round = mysql_fetch_assoc($tracked_round);
$totalRows_tracked_round = mysql_num_rows($tracked_round);
?>
			
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

$rid_order_list = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['round'])) {
  $rid_order_list = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_market_prices = sprintf("SELECT asset.name, clearing.price FROM clearing, asset WHERE clearing.round_id = %s AND asset.asset_id = clearing.asset_id", $rid_order_list);
$market_prices = mysql_query($query_market_prices, $CAPM) or die(mysql_error());
$row_market_prices = mysql_fetch_assoc($market_prices);
$totalRows_market_prices = mysql_num_rows($market_prices);


$uid_order_list = $row_users['user_id'];
if (isset($HTTP_POST_VARS['uid'])) {
  $uid_order_list = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['uid'] : addslashes($HTTP_POST_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_order_list = sprintf("SELECT orders.order_id, orders.order_time, orders.type, orders.price, orders.quantity, orders.executed, round.round_num, asset.name FROM orders, round, asset WHERE asset.asset_id = orders.asset_id AND round.round_id = orders.round_id AND orders.round_id = %s AND orders.user_id = %s", $rid_order_list,$uid_order_list);
$order_list = mysql_query($query_order_list, $CAPM) or die(mysql_error());
$row_order_list = mysql_fetch_assoc($order_list);
$totalRows_order_list = mysql_num_rows($order_list);


//Find prev round_id

$last_round_num = $row_tracked_round['round_num'] - 1;
if($last_round_num >= 0){
	mysql_select_db($database_CAPM, $CAPM);
	$query_prev_round = "SELECT round_id FROM round WHERE round_num = $last_round_num AND experiment_id = $expid_users";
	$prev_round = mysql_query($query_prev_round, $CAPM) or die(mysql_error());
	$row_prev_round = mysql_fetch_assoc($prev_round);
	$totalRows_prev_round = mysql_num_rows($prev_round);
}

$SQL1 = "SELECT DISTINCT r.round_id, r.round_num, s.user_id, u.user_name, c.amount";
	$SQL2 = ' '; 
	$SQL4 = ' ';
	$SQL5 = ' ';
	$SQL6 = ' ';
	$uid = $user_tracked_user;
do { 
	$row = $row_assets['name'];
	$row_id = $row_assets['asset_id'];
	$SQL2 = "$SQL2, T$row.quantity '$row' ";
	$SQL4 = "$SQL4, state T$row ";
	$SQL6 = "$SQL6 AND T$row.asset_id = $row_id AND T$row.round_id = r.round_id AND T$row.user_id = $uid";
} while ($row_assets = mysql_fetch_assoc($assets)); 
	
	$exp = $HTTP_SESSION_VARS['cur_experiment'];
	$rid = $HTTP_SESSION_VARS['cur_round'];
		if (isset($HTTP_POST_VARS['round'])) {
  			$rid = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
		}
	if($last_round_num >= 0){
		$ridPrev = $row_prev_round['round_id'];
	}else{
		$ridPrev = $rid;
	}		 
	$SQL3 = "FROM cash c, users u, round r, state s";
	$SQL5 = "WHERE s.user_id = c.user_id AND u.user_id = s.user_id AND s.round_id = r.round_id  AND c.round_id = r.round_id  AND u.user_id = $uid AND r.experiment_id = $exp AND s.round_id in ($rid, $ridPrev)";
					 
$State_SQL = "$SQL1 $SQL2 $SQL3 $SQL4 $SQL5 $SQL6";

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
<title>CAPM | Admin | Track Students</title>
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
                <td valign="top"><span class="redtitles"><div align="right"><!-- InstanceBeginEditable name="Description" -->

 
                   Admin: Track Students<!-- InstanceEndEditable --></div></span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
              <form method="POST" action="track_students.php" name="form">
                <p>Select the round and the student's name for which you want 
                  to track their trades and then click on Get Info. You will receive 
                  of report of their asset balances before and after the round 
                  and their orders for the round.<br>
                  <select name="round">
                    <?php do { ?>
                    <option value="<?php echo $row_drop_round['round_id']; ?>"<?php if($row_drop_round['round_id'] == $rid_tracked_round){echo " selected";}?>>Round 
                    <?php echo $row_drop_round['round_num']?></option>
                    <?php } while ($row_drop_round = mysql_fetch_assoc($drop_round));
  				$rows = mysql_num_rows($drop_round);
  		if($rows > 0) {
      			mysql_data_seek($drop_round, 0);
	  		$row_drop_round = mysql_fetch_assoc($drop_round);
  		}?>
                  </select>
                  <SELECT NAME="uid">
                    <?php
do {  
?>
                    <option value="<?php echo $row_users['user_id']; ?>"<?php if($row_users['user_id'] == $uid_order_list){echo " selected";}?>><?php echo $row_users['user_name']?></option>
                    <?php
} while ($row_users = mysql_fetch_assoc($users));
  $rows = mysql_num_rows($users);
  if($rows > 0) {
      mysql_data_seek($users, 0);
	  $row_users = mysql_fetch_assoc($users);
  }
?>
                  </SELECT>
                  <br>
                  <input type="submit" name="Submit" value="Get Info">
                </p>
              </form>
              <p><span class="redtitles">Portfolio Status for <?php echo $row_tracked_user['user_name']; ?> in Round <?php echo $row_tracked_round['round_num']; ?>:</span></p>
              
              <table border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#990000" class="topBar"> 
                  <td>Round</td>
                  <td>Cash</td>
                  <?php mysql_data_seek($assets,0);
			$row_assets = mysql_fetch_assoc($assets);?>
                  <?php do{ ?>
                  <td><?php echo $row_assets['name']; ?></td>
                  <?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
				  <td>Round Status</td>
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
                  </td><?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
		<td><?php if($row_user_state['round_num'] == $row_tracked_round['round_num']){ echo "Choosen";}else{ echo "Previous";} ?></td>
                  </tr>
                <?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                <?php } while ($row_user_state = mysql_fetch_assoc($user_state)); ?>
              </TABLE>
              <p><span class="redtitles">Order Status for <?php echo $row_tracked_user['user_name']; ?> in Round <?php echo $row_tracked_round['round_num']; ?> :</span></p>
			  
              <table border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#990000" class="topBar"> 
                  <td>Order ID</td>
                  <td>Order Time</td>
                  <td>Round</td>
                  <td>Asset</td>
                  <td>Type</td>
                  <td>Price</td>
                  <td>Quantity</td>
                  <td>Executed</td>

                </tr>
				<?php $row1 = "#d0d0f0";
					$row2 = "#f0d0d0";
					$CurrentColor= $row1; ?>
                <?php do { ?>
                <tr bgcolor="<?php echo $CurrentColor ?>"> 
                  <td><?php echo $row_order_list['order_id']; ?></td>
                  <td><?php echo $row_order_list['order_time']; ?></td>
				  <td><?php echo $row_order_list['round_num']; ?></td>
                  <td><?php echo $row_order_list['name']; ?></td>
                  <td><?php echo $row_order_list['type']; ?></td>
                  <td><?php if(isset($row_order_list['price'])){ ?>
                    $<?php echo number_format($row_order_list['price'],2); }?></td>
                  <td><?php echo $row_order_list['quantity']; ?></td>
                  <td><?php echo $row_order_list['executed']; ?></td>
                </tr>
				<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                <?php } while ($row_order_list = mysql_fetch_assoc($order_list)); ?>
              </table>
			  
			  <p><span class="redtitles">Market Prices in Round <?php echo $row_tracked_round['round_num']; ?> :</span></p>
			  
              
              
<table border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#990000" class="topBar"> 
                  <td>Asset</td>
                  <td>Price</td>
                </tr>
				<?php $row1 = "#d0d0f0";
					$row2 = "#f0d0d0";
					$CurrentColor= $row1; ?>
                <?php do { ?>
                <tr bgcolor="<?php echo $CurrentColor ?>"> 
                  <td><?php echo $row_market_prices['name']; ?></td>
                  <td>
                    <?php if(isset($row_market_prices['price'])){ ?>
                    $<?php echo number_format($row_market_prices['price'],2); }?></td>
                </tr>
				<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                <?php } while ($row_market_prices = mysql_fetch_assoc($market_prices)); ?>
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
mysql_free_result($users);

mysql_free_result($drop_round);

mysql_free_result($tracked_user);

mysql_free_result($tracked_round);

mysql_free_result($assets);

mysql_free_result($market_prices);

mysql_free_result($order_list);

?>



