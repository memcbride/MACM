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
 
$UN = $HTTP_POST_VARS['UN'];
$UP = $HTTP_POST_VARS['UP'];
$EM = $HTTP_POST_VARS['EM'];
$GM = strtolower($HTTP_POST_VARS['GM']);
$UID = $HTTP_POST_VARS['UID'];

mysql_select_db($database_CAPM, $CAPM);
$query_update_experiment = "update users SET user_name = '$UN', password = '$UP', email = '$EM', get_email = '$GM' WHERE user_id = $UID";
mysql_query($query_update_experiment, $CAPM) or die(mysql_error());

header("Location: users.php");
?>

