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
<?php
require_once('../Connections/CAPM.php');
 
$EN = $HTTP_POST_VARS['EN'];
$EID = $HTTP_POST_VARS['EID'];

mysql_select_db($database_CAPM, $CAPM);
$query_update_experiment = "update experiment set experiment_name = '$EN' WHERE experiment_id = $EID";
mysql_query($query_update_experiment, $CAPM) or die(mysql_error());

header("Location: status_admin.php");
?>

