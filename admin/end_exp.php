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
<?php require_once('../Connections/CAPM.php'); ?>
<?php


$rid = $HTTP_POST_VARS['rid'];
$rnum = $HTTP_POST_VARS['rnum'];
$end_round_num = $rnum + 1;
$exid = $HTTP_POST_VARS['exid'];

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Create final round
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
mysql_select_db($database_CAPM, $CAPM);
$insert_final_round = "INSERT INTO round (round_num, experiment_id, current) VALUES ($end_round_num, $exid, 1)";
mysql_query($insert_final_round, $CAPM) or die(mysql_error());

$final_rid = mysql_insert_id();

mysql_select_db($database_CAPM, $CAPM);
$update_round = "UPDATE round SET current = 0 WHERE round_id = $rid";
mysql_query($update_round, $CAPM) or die(mysql_error());

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Load Final prices into clearing.
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
$num_assets = $HTTP_POST_VARS['num_assets'];
for($i=1;$i<=$num_assets;$i++){
	$name = "asset$i";
	$final_price = $HTTP_POST_VARS[$name];
	$name_id = "asset_id$i";
	$asset_id = $HTTP_POST_VARS[$name_id];
	
	mysql_select_db($database_CAPM, $CAPM);
	$new_clearing = "INSERT INTO clearing (round_id, asset_id, price) VALUES ($final_rid, $asset_id, $final_price)";
	mysql_query($new_clearing, $CAPM) or die(mysql_error());
	
}
/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

CASH OUT THE USERS

XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

//Get Cash info for each user
mysql_select_db($database_CAPM, $CAPM);
$query_user_cash = "SELECT cash.amount, cash.user_id FROM cash WHERE cash.round_id = $rid";
$user_cash = mysql_query($query_user_cash, $CAPM) or die(mysql_error());
$row_user_cash = mysql_fetch_assoc($user_cash);
$totalRows_user_cash = mysql_num_rows($user_cash);


do{ //loops through user_id's found in the cash table and gets the clearing price and personal quantitys
	$uid = $row_user_cash['user_id'];
	$amount = $row_user_cash['amount'];
	
	
	//Get State table info for the user in question
	mysql_select_db($database_CAPM, $CAPM);
	$query_state_info = "SELECT state.quantity, clearing.price, state.asset_id FROM state, clearing WHERE state.round_id = $rid AND state.user_id = $uid AND clearing.asset_id = state.asset_id AND clearing.round_id = $final_rid";
	$state_info = mysql_query($query_state_info, $CAPM) or die(mysql_error());
	$row_state_info = mysql_fetch_assoc($state_info);
	$totalRows_state_info = mysql_num_rows($state_info);
	
		do{
			$Q_owned = $row_state_info['quantity'];
			$asset_price = $row_state_info['price'];
			$aid = $row_state_info['asset_id'];
			$amount += ($asset_price * $Q_owned);
			
			
			mysql_select_db($database_CAPM, $CAPM);
			$insert_state_values = "INSERT INTO state (user_id, round_id, asset_id) VALUES ($uid, $final_rid, $aid)";
			mysql_query($insert_state_values, $CAPM) or die(mysql_error());
		
		} while ($row_state_info = mysql_fetch_assoc($state_info));
	
	mysql_select_db($database_CAPM, $CAPM);
	$insert_end_cash = "INSERT INTO cash (amount, user_id, round_id) VALUES ($amount, $uid, $final_rid)";
	mysql_query($insert_end_cash, $CAPM) or die(mysql_error());

} while ($row_user_cash = mysql_fetch_assoc($user_cash));


mysql_select_db($database_CAPM, $CAPM);
$update_exp = "UPDATE experiment SET status = 0 WHERE experiment_id = $exid";
mysql_query($update_exp, $CAPM) or die(mysql_error());//mysql_error()


mysql_free_result($user_cash);

mysql_free_result($state_info);

header("Location: status_admin.php");
?>
