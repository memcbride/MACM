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
<?php  require_once('../Connections/CAPM.php');
$ex_name = $HTTP_POST_VARS['ex_name'];

$data_file = $HTTP_POST_FILES['start_info']['tmp_name'];
//fread(fopen($data_file, 'r'), filesize($data_file));


$data= file($data_file); //takes the file of info and turns it into an array

for($i=0; $i<count($data);$i++){//makes sure standard vars are lower case for processing
	$data[$i] = trim($data[$i]);
}

$title = split(",",$data[0]);//Gets the titles of each of the columns

for($i=0; $i<4;$i++){//makes sure standard vars are lower case for processing
	$title[$i] = strtolower($title[$i]);
}

$num_assets = count($title) - 4;

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
Create Experiment and get experiment_id for it
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/

mysql_select_db($database_CAPM, $CAPM);
$query_check_exp = "SELECT experiment_id FROM experiment where experiment_name = '$ex_name'";
$check_exp = mysql_query($query_check_exp, $CAPM) or die("1");//mysql_error()
$row_check_exp = mysql_fetch_assoc($check_exp);
$totalRows_check_exp = mysql_num_rows($check_exp);

if($totalRows_check_exp > 0){
	mysql_free_result($check_exp);
	header("Location: create_exp.php?msg_ce=N");
}else{

	mysql_select_db($database_CAPM, $CAPM);
	$update_exp_cur = "UPDATE experiment set current = 0";
	mysql_query($update_exp_cur, $CAPM) or die("2");
	
	
	mysql_select_db($database_CAPM, $CAPM);
	$insert_exp = "INSERT INTO experiment (experiment_name, number_assets, status, current) VALUES ('$ex_name', $num_assets, 1, 1)";
	mysql_query($insert_exp, $CAPM) or die("3");
	
	$eid = mysql_insert_id();
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	Create Round 0 and get round_id for it
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	mysql_select_db($database_CAPM, $CAPM);
	$insert_round = "INSERT INTO round (round_num, experiment_id, current, opened) VALUES (0,$eid,1,0)";
	mysql_query($insert_round, $CAPM) or die("5");
	
	$rid = mysql_insert_id();
	
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	insert assets into asset table and names of assets in 
	$title array with the new asset_id's for use in inserts
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	for($i=4;$i<count($title);$i++){
		mysql_select_db($database_CAPM, $CAPM);
		$insert_asset = "INSERT INTO asset (name, experiment_id) VALUES ('$title[$i]', $eid)";
		mysql_query($insert_asset, $CAPM) or die("7");
	}
	
	mysql_select_db($database_CAPM, $CAPM);
	$query_get_aid = "SELECT asset_id, name FROM asset WHERE experiment_id = $eid";
	$get_aid = mysql_query($query_get_aid, $CAPM) or die("8");
	$row_get_aid = mysql_fetch_assoc($get_aid);
	$totalRows_get_aid = mysql_num_rows($get_aid);
	
	do{
		for($i=4; $i<count($title);$i++){
			if($row_get_aid['name'] == $title[$i]){
				$title[$i] = $row_get_aid['asset_id'];
			}
		}
	}while($row_get_aid = mysql_fetch_assoc($get_aid));
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	following loop takes comma separated values in data array and 
	creats a table style multidemintional array
	
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	for($i=1;$i<count($data);$i++){
		$row_data = split(",",$data[$i]);
		for($j=0;$j<count($title);$j++){
			$col_header = $title[$j];
			$full_table[$i-1][$col_header]= trim($row_data[$j]);
		}
	}
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	Insert users and select there new user_id and add it to the array
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	for($i=0;$i<count($full_table);$i++){
		$name = $full_table[$i]['name'];
		$pass = $full_table[$i]['password'];
		$email= $full_table[$i]['email'];
		
		mysql_select_db($database_CAPM, $CAPM);
		$insert_user = "INSERT INTO users (user_name, password, user_type, email, get_email, experiment_id) VALUES ('$name','$pass', 'student', '$email', 'yes', $eid)";
		mysql_query($insert_user, $CAPM) or die(header("Location: delete_exp.php?eid=$eid&vc=34223&u_name=$name"));
	
		//if(mysql_errno() != 0){
			//header("Location: create_exp.php?msg_ce=RU&u_name=$name");
		//}
		$full_table[$i]['user_id'] = mysql_insert_id();
	}
	
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	Insert starting cash values for each user
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	for($i=0;$i<count($full_table);$i++){
		$user_id = $full_table[$i]['user_id'];
		$amount = $full_table[$i]['cash'];
	
		mysql_select_db($database_CAPM, $CAPM);
		$insert_cash = "INSERT INTO cash (user_id,round_id,amount) VALUES ($user_id,$rid,$amount)";
		mysql_query($insert_cash, $CAPM) or die("11");
	
	}
	
	
	/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	Insert state values for each user & asset
	XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
	
	for($i=0;$i<count($full_table);$i++){
		$user_id = $full_table[$i]['user_id'];
	
		for($j=4; $j<count($title);$j++){
			$aid = $title[$j];
			$quantity = $full_table[$i][$aid];
		
	
			mysql_select_db($database_CAPM, $CAPM);
			$insert_cash = "INSERT INTO state (user_id,round_id,asset_id,quantity) VALUES ($user_id,$rid,$aid,$quantity)";
			mysql_query($insert_cash, $CAPM) or die("12");
		}
	}
	
	mysql_free_result($check_exp);
	mysql_free_result($get_aid);

/*XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
SETS CURRENT EXPERIMENT
XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX*/
session_start();
$HTTP_SESSION_VARS['cur_experiment'] = $eid;
$HTTP_SESSION_VARS['cur_round'] = $rid;

header("Location: create_exp.php?msg_ce=C");
}

?>

