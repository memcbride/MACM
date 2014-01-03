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
if($HTTP_POST_VARS['submit'] == "delete"){
$oid = $HTTP_POST_VARS['OID'];

mysql_select_db($database_CAPM, $CAPM);
$delete_order = "Delete from order WHERE order_id = $oid";
mysql_query($delete_order, $CAPM) or die(mysql_error());

}elseif($HTTP_POST_VARS['submit'] == "update"){

$oid = $HTTP_POST_VARS['OID'];
$rid = $HTTP_POST_VARS['round'];
$aid = $HTTP_POST_VARS['asset'];
$type = $HTTP_POST_VARS['type'];
$price = $HTTP_POST_VARS['price'];
$q = $HTTP_POST_VARS['quantity'];

$type = trim($type);
$price = trim($price);
if(!is_numeric($price))
{
$price = str_replace('$','',$price);
}
$q = trim($q);
if(!is_numeric($q) || !isset($q))
{
$q = 0;
}


mysql_select_db($database_CAPM, $CAPM);
$change_order = "UPDATE orders set asset_id = $aid, type = '$type', price = $price, quantity = $q WHERE order_id = $oid";
mysql_query($change_order, $CAPM) or die(mysql_error());

}
session_start();
$round = $HTTP_SESSION_VARS['cur_round'];

header("Location: orders.php?round=$round&orderby=order_id");
?>