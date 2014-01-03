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
 
if($HTTP_POST_VARS['submit'] == 'Close Current Round')
{
$rid = $HTTP_POST_VARS['rid'];

mysql_select_db($database_CAPM, $CAPM);
$close_round = "UPDATE round set opened = 0 WHERE round_id = $rid";
mysql_query($close_round, $CAPM) or die(mysql_error());

}
elseif($HTTP_POST_VARS['submit'] == 'Open Current Round')
{
$rid = $HTTP_POST_VARS['rid'];

mysql_select_db($database_CAPM, $CAPM);
$close_round = "UPDATE round set opened = 1 WHERE round_id = $rid";
mysql_query($close_round, $CAPM) or die(mysql_error());

}
header("Location: status_admin.php");
?>

