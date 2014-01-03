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

$uid = $HTTP_POST_VARS['uid'];
$pword = $HTTP_POST_VARS['password'];
$email = $HTTP_POST_VARS['email'];
$gem = $HTTP_POST_VARS['get_email'];

mysql_select_db($database_CAPM, $CAPM);
$update_user = "UPDATE users SET password = '$pword', email = '$email', get_email = '$gem' where user_id = $uid";
mysql_query($update_user, $CAPM) or die(mysql_error());

header("Location: status_student.php");
?>