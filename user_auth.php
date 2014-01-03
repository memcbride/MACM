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
<?php require_once('Connections/CAPM.php');

$login = strtolower($HTTP_POST_VARS['login']);
$password = strtolower($HTTP_POST_VARS['password']);

mysql_select_db($database_CAPM, $CAPM);
$query_check_user = "SELECT users.user_type, users.user_id FROM users WHERE lower(users.user_name) = '$login' AND lower(users.password) = '$password'";
$check_user = mysql_query($query_check_user, $CAPM) or die(mysql_error());
$row_check_user = mysql_fetch_assoc($check_user);
$totalRows_check_user = mysql_num_rows($check_user);

$uid = $row_check_user['user_id'];
$type = $row_check_user['user_type'];

if($type == 'student'){
	mysql_select_db($database_CAPM, $CAPM);
	$query_current = "SELECT round.round_id, round.experiment_id FROM round, experiment, users WHERE round.current = 1 AND round.experiment_id = experiment.experiment_id AND users.experiment_id = experiment.experiment_id AND users.user_id = $uid";
	$current = mysql_query($query_current, $CAPM) or die(mysql_error());
	$row_current = mysql_fetch_assoc($current);
	$totalRows_current = mysql_num_rows($current);
}elseif($type == 'admin'){
	mysql_select_db($database_CAPM, $CAPM);
	$query_current = "SELECT round.round_id, experiment.experiment_id FROM round, experiment WHERE round.current = 1 AND experiment.current = 1 AND experiment.experiment_id = round.experiment_id";
	$current = mysql_query($query_current, $CAPM) or die(mysql_error());
	$row_current = mysql_fetch_assoc($current);
	$totalRows_current = mysql_num_rows($current);
	
	if($totalRows_current ==0){
		$newexp = 1;
	}
}

if (strtolower(trim($row_check_user['user_type'])) == 'admin')
{
session_start();

$HTTP_SESSION_VARS['status'] = 'admin';
$HTTP_SESSION_VARS['cur_round'] = $row_current['round_id']; 
$HTTP_SESSION_VARS['cur_experiment'] = $row_current['experiment_id'];
$HTTP_SESSION_VARS['user_name'] = $HTTP_POST_VARS['login'];
$HTTP_SESSION_VARS['user_id'] = $row_check_user['user_id'];


	$query_add_one = "update users set logins = (logins + 1) where user_id = '$uid'"; //uid is up top
	 mysql_query($query_add_one, $CAPM) or die(mysql_error());
if(isset($newexp)){
	header("Location: admin/create_exp.php?vardefset=1");
}else{
	header("Location: admin/status_admin.php");
}
}
elseif (strtolower(trim($row_check_user['user_type'])) == 'student')
{ 
	 session_start();
	 $HTTP_SESSION_VARS['status'] = 'student'; 
	 $HTTP_SESSION_VARS['cur_round'] = $row_current['round_id']; 
	 $HTTP_SESSION_VARS['cur_experiment'] = $row_current['experiment_id']; 
	 $HTTP_SESSION_VARS['user_name'] = $HTTP_POST_VARS['login'];
	 $HTTP_SESSION_VARS['user_id'] = $row_check_user['user_id'];
	 
	 $uname = $HTTP_POST_VARS['login'];
	 $query_add_one = "update users set logins = (logins + 1) where user_name = '$uname'";
	 mysql_query($query_add_one, $CAPM) or die(mysql_error());
	 
	 header("Location: student/status_student.php");

}
else
{
	header("Location: index.php?msg=BP");
}
mysql_free_result($check_user);

mysql_free_result($current);
 ?>

