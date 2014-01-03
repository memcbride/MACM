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
<?php require_once('Connections/CAPM.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Data Base Install</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table 
	cellpadding="0"
	cellspacing="0" 
	border="0">
  <tr> 
    <td valign=TOP><img src="images/headerlogo.gif"> </td>
  </tr>
</table>
<?php 
//Accepts login and pass from form
$a_login = $HTTP_POST_VARS['login'];
$a_pass = $HTTP_POST_VARS['pass'];

if(!isset($HTTP_POST_VARS['login'])){
	header("Location: Install_DB.php?msg=NoLog");
}
if(strlen($a_login) < 3){
	header("Location: Install_DB.php?msg=NoLog");
}


//Creates DB
$Cr_status = mysql_query("CREATE DATABASE $database_CAPM", $CAPM);
	if($Cr_status == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 1: Create Database<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 1: Create Database<BR>";
	}
//Loads Tables
$Sel_status = mysql_select_db($database_CAPM, $CAPM);
	if($Sel_status == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 2: Select Database<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 2: Select Database<BR>";
	}
	
$cr_asset = mysql_query("CREATE TABLE asset (
  asset_id mediumint(8) NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  experiment_id mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (asset_id)
) TYPE=MyISAM", $CAPM);

	if($cr_asset == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 3: Create Table Asset<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 3: Create Table Asset<BR>";
	}
	
$cr_cash = mysql_query("CREATE TABLE cash (
  cash_id mediumint(8) NOT NULL auto_increment,
  user_id mediumint(8) NOT NULL default '0',
  round_id mediumint(8) NOT NULL default '0',
  amount double NOT NULL default '0',
  PRIMARY KEY  (cash_id)
) TYPE=MyISAM", $CAPM);

	if($cr_cash == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 4: Create Table Cash<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 4: Create Table Cash<BR>";
	}
	
$cr_clearing = mysql_query("CREATE TABLE clearing (
  clearing_id int(11) NOT NULL auto_increment,
  round_id int(11) NOT NULL default '0',
  asset_id int(11) NOT NULL default '0',
  price double NOT NULL default '0',
  volume int(11) NOT NULL default '0',
  PRIMARY KEY  (clearing_id)
) TYPE=MyISAM", $CAPM);

	if($cr_clearing == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 5: Create Table Clearing<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 5: Create Table Clearing<BR>";
	}
	
$cr_experiment = mysql_query("CREATE TABLE experiment (
  experiment_id mediumint(8) NOT NULL auto_increment,
  experiment_name varchar(30) NOT NULL default '',
  number_assets mediumint(9) NOT NULL default '5',
  status tinyint(4) NOT NULL default '1',
  current tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (experiment_id)
) TYPE=MyISAM", $CAPM);

	if($cr_experiment == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 6: Create Table Experiment<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 6: Create Table Experiment<BR>";
	}
	
$cr_market = mysql_query("CREATE TABLE market (
  market_id mediumint(8) NOT NULL auto_increment,
  round_id mediumint(8) NOT NULL default '0',
  asset_id mediumint(8) NOT NULL default '0',
  NTrans int(11) NOT NULL default '0',
  price double NOT NULL default '0',
  Qd int(11) NOT NULL default '0',
  Qs int(11) NOT NULL default '0',
  Ed int(11) NOT NULL default '0',
  Nd int(11) NOT NULL default '0',
  Ns int(11) NOT NULL default '0',
  PRIMARY KEY  (market_id)
) TYPE=MyISAM", $CAPM);

	if($cr_market == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 7: Create Table Market<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 7: Create Table Market<BR>";
	}
	
$cr_orders = mysql_query("CREATE TABLE orders (
  order_id mediumint(8) unsigned NOT NULL auto_increment,
  order_time datetime NOT NULL default '0000-00-00 00:00:00',
  user_id mediumint(8) unsigned NOT NULL default '0',
  round_id tinyint(3) unsigned NOT NULL default '0',
  asset_id int(11) default NULL,
  type varchar(5) default NULL,
  price double default '0',
  quantity mediumint(8) unsigned NOT NULL default '0',
  executed mediumint(8) unsigned default '0',
  PRIMARY KEY  (order_id)
) TYPE=MyISAM", $CAPM);

	if($cr_orders == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 8: Create Table Orders<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 8: Create Table Orders<BR>";
	}
	
$cr_round = mysql_query("CREATE TABLE round (
  round_id mediumint(8) NOT NULL auto_increment,
  round_num int(11) NOT NULL default '0',
  end_time datetime NOT NULL default '0000-00-00 00:00:00',
  experiment_id mediumint(8) NOT NULL default '0',
  current int(1) NOT NULL default '0',
  opened int(1) NOT NULL default '0',
  PRIMARY KEY  (round_id)
) TYPE=MyISAM", $CAPM);

	if($cr_round == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 9: Create Table Round<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 9: Create Table Round<BR>";
	}
	
$cr_state = mysql_query("CREATE TABLE state (
  state_id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  round_id int(10) NOT NULL default '0',
  quantity int(11) NOT NULL default '0',
  asset_id int(11) NOT NULL default '0',
  PRIMARY KEY  (state_id)
) TYPE=MyISAM", $CAPM);

	if($cr_state == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 10: Create Table State<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 10: Create Table State<BR>";
	}
	
$cr_users = mysql_query("CREATE TABLE users (
  user_id mediumint(8) unsigned NOT NULL auto_increment,
  user_name varchar(30) default NULL,
  password varchar(10) default NULL,
  user_type varchar(10) default 'student',
  email varchar(30) default NULL,
  get_email varchar(10) default 'no',
  logins mediumint(9) NOT NULL default '0',
  experiment_id mediumint(8) NOT NULL default '1',
  PRIMARY KEY  (user_id),
  UNIQUE KEY user_name (user_name),
  KEY user_index (user_name)
) TYPE=MyISAM", $CAPM);

	if($cr_users == true){
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 11: Create Table Users<BR>";
	}else{
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 11: Create Table Users<BR>";
	}
	
//Creates Admin Account
$add_admin = mysql_query("INSERT INTO users 
(user_name,password,user_type,email,get_email,logins,experiment_id) 
VALUES ('$a_login','$a_pass','admin','N/A','no',0,0)", $CAPM) or die(mysql_error());

	if($add_admin == false){
		echo "<font color=#CC0000><strong>Failed</strong></font>&nbsp;Step 12: Add admin account to allow initial login<BR>";
	}else{
		echo "<font color=#00CC00><strong>Passed</strong></font>&nbsp;Step 12: Add admin account to allow initial login<BR>";
	}
?>
<br>
<br>
The Database has been Created (barring any failed messaged above). <a href="index.php">Login 
here</a> 
</body>
</html>