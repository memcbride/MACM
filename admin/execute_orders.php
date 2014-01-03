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
include('header.php'); 
/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

UPDATE THE STATE AND CASH TABLES 

XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
$round = $HTTP_GET_VARS['round']; //current round_id


$Pround_num = ($HTTP_GET_VARS['rnum'] - 1); //creates prev round_num
$ex_id = $HTTP_SESSION_VARS['cur_experiment'];// experiment_id

//finds prev round_id
mysql_select_db($database_CAPM, $CAPM);
$query_prev_round = "SELECT round.round_id FROM round WHERE round.round_num = $Pround_num AND round.experiment_id = $ex_id";
$p_round = mysql_query($query_prev_round, $CAPM) or die("1");
$row_prev_round = mysql_fetch_assoc($p_round);
$totalRows_prev_round = mysql_num_rows($p_round);


$prev_round = $row_prev_round['round_id'];//id for round before the one being finished up

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Populate the State table
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

//Select all info from state table for the prev_round
mysql_select_db($database_CAPM, $CAPM);
$query_prev_state = "SELECT * FROM state WHERE round_id = $prev_round";
$prev_state = mysql_query($query_prev_state, $CAPM) or die("2");//mysql_error()
$row_prev_state = mysql_fetch_assoc($prev_state);
$totalRows_prev_state = mysql_num_rows($prev_state);

//insert state values back in with updated round
do { 
	$user_id = $row_prev_state['user_id'];
	$quantity = $row_prev_state['quantity'];
	$asset_id = $row_prev_state['asset_id'];

	
	mysql_select_db($database_CAPM, $CAPM);
	$query_ins_state = "INSERT INTO state (round_id, user_id, quantity, asset_id) VALUES ($round, $user_id, $quantity, $asset_id)";
	mysql_query($query_ins_state, $CAPM) or die("3");

} while ($row_prev_state = mysql_fetch_assoc($prev_state));

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Populate the Cash table
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

//Select all info from Cash table for the prev_round
mysql_select_db($database_CAPM, $CAPM);
$query_prev_cash = "SELECT * FROM cash WHERE round_id = $prev_round";
$prev_cash = mysql_query($query_prev_cash, $CAPM) or die(mysql_error());
$row_prev_cash = mysql_fetch_assoc($prev_cash);
$totalRows_prev_cash = mysql_num_rows($prev_cash);



//insert state values back in with updated round
do { 
	$user_id = $row_prev_cash['user_id'];
	$amount = $row_prev_cash['amount'];

	
	mysql_select_db($database_CAPM, $CAPM);
	$query_ins_cash = "INSERT INTO cash (round_id, user_id, amount) VALUES ($round, $user_id, $amount)";
	mysql_query($query_ins_cash, $CAPM) or die(mysql_error());

} while ($row_prev_cash = mysql_fetch_assoc($prev_cash));


/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Retreve needed pieces of orders from DB
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
mysql_select_db($database_CAPM, $CAPM);
$query_orders = "SELECT orders.executed, clearing.price, orders.type, orders.asset_id, orders.user_id FROM orders, clearing WHERE orders.round_id = $round  AND orders.executed > 0 AND orders.round_id = clearing.round_id AND orders.asset_id = clearing.asset_id";
$orders = mysql_query($query_orders, $CAPM) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);





/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
LOOP ORDERS UPDATEING STATE AND CASH
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
do{
$executed = $row_orders['executed'];
$price = $row_orders['price'];
$money_exchanged = ($price * $executed);
$asset_id = $row_orders['asset_id'];
$user_id = $row_orders['user_id'];
$type = $row_orders['type'];


	if($type == "offer"){
		//Update State table
		mysql_select_db($database_CAPM, $CAPM);
		$query_up_state = "UPDATE state SET quantity = (quantity - $executed) WHERE user_id = $user_id AND asset_id = $asset_id AND round_id = $round";
		mysql_query($query_up_state, $CAPM) or die(mysql_error());
		
		//Update Cash table
		mysql_select_db($database_CAPM, $CAPM);
		$query_up_cash = "UPDATE cash SET amount = (amount + $money_exchanged) WHERE user_id = $user_id AND round_id = $round";
		mysql_query($query_up_cash, $CAPM) or die(mysql_error());
	
	}elseif($type == "bid"){
		//Update State table
		mysql_select_db($database_CAPM, $CAPM);
		$query_up_state = "UPDATE state SET quantity = (quantity + $executed) WHERE user_id = $user_id AND asset_id = $asset_id AND round_id = $round";
		mysql_query($query_up_state, $CAPM) or die(mysql_error());
		
		//Update Cash table
		mysql_select_db($database_CAPM, $CAPM);
		$query_up_cash = "UPDATE cash SET amount = (amount - $money_exchanged) WHERE user_id = $user_id AND round_id = $round";
		mysql_query($query_up_cash, $CAPM) or die(mysql_error());
	
	}


} while ($row_orders = mysql_fetch_assoc($orders));


mysql_free_result($p_round);

mysql_free_result($prev_state);
mysql_free_result($prev_cash);

mysql_free_result($orders);

header("Location: status_admin.php");
?>
