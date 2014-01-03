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
<?php require_once('../Connections/CAPM.php');  ?>
<?php
$M_status_ex = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $M_status_ex = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_M_status = "SELECT  round.opened FROM round, experiment WHERE round.current = 1   AND  experiment.experiment_id = $M_status_ex  AND round.experiment_id = experiment.experiment_id";
$M_status = mysql_query($query_M_status, $CAPM) or die(mysql_error());
$row_M_status = mysql_fetch_assoc($M_status);
$totalRows_M_status = mysql_num_rows($M_status);

if ($row_M_status['opened'] != 1){
header("Location: status_student.php");
}
 
session_start();
$uid = $HTTP_SESSION_VARS['user_id'];
$rid = $HTTP_SESSION_VARS['cur_round'];
$aid = $HTTP_POST_VARS['asset'];
$type = $HTTP_POST_VARS['type'];
$qty = $HTTP_POST_VARS['quantity'];
$pr = $HTTP_POST_VARS['price'];


mysql_select_db($database_CAPM, $CAPM);
$query_submit_order = "INSERT INTO orders (user_id, round_id, asset_id, type, quantity, price, order_time) VALUES ($uid, $rid, $aid, '$type', $qty, $pr, sysdate())";
mysql_query($query_submit_order, $CAPM) or die(mysql_error());


mysql_free_result($M_status);

header("Location: order_form.php");
?>