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

$rnum = $HTTP_POST_VARS['round_num'];
$end_time = $HTTP_POST_VARS['end_time'];
$old_rid = $HTTP_POST_VARS['old_rid'];
$exid = $HTTP_POST_VARS['exid'];

mysql_select_db($database_CAPM, $CAPM);
$unset_old_round = "UPDATE round set opened = 0, current = 0 WHERE round_id = $old_rid";
mysql_query($unset_old_round, $CAPM) or die(mysql_error());


mysql_select_db($database_CAPM, $CAPM);
$insert_new_round = "INSERT into round (round_num, end_time, experiment_id, current, opened) VALUES ($rnum,'$end_time', $exid, 1, 0)";
mysql_query($insert_new_round, $CAPM) or die(mysql_error());

header("Location: status_admin.php");
?>

