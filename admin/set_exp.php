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
session_start();

$HTTP_SESSION_VARS['cur_experiment'] = $HTTP_GET_VARS['expid'];

$expid_Recordset1 = $HTTP_GET_VARS['expid'];
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid_Recordset1 = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Recordset1 = sprintf("SELECT round_id FROM round WHERE round.experiment_id =%s AND round.current = 1", $expid_Recordset1);
$Recordset1 = mysql_query($query_Recordset1, $CAPM) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
 


$HTTP_SESSION_VARS['cur_round'] = $row_Recordset1['round_id'];

$url = $HTTP_SESSION_VARS['url'];

mysql_free_result($Recordset1);

header("Location: $url");

?>
