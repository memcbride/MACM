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

require_once('../Connections/CAPM.php');
include "header.php";

if(isset($HTTP_GET_VARS['vc'])){
	if($HTTP_GET_VARS['vc']==34223){
		$HTTP_POST_VARS['check'] = "Yes";
		$HTTP_POST_VARS['exid'] = $HTTP_GET_VARS['eid'];
	}
}

if(isset($HTTP_POST_VARS['check'])){
	if($HTTP_POST_VARS['check'] == "Yes"){
		$exid = $HTTP_POST_VARS['exid'];
		
		//Get rounds for exid
		mysql_select_db($database_CAPM, $CAPM);
		$query_get_rounds = "SELECT round_id FROM round WHERE experiment_id = $exid";
		$get_rounds = mysql_query($query_get_rounds, $CAPM) or die(mysql_error());
		$row_get_rounds = mysql_fetch_assoc($get_rounds);
		$totalRows_get_rounds = mysql_num_rows($get_rounds);
		
				//Create , separated list of round_ids for delete SQL
				
				$comma = 0;
				do { 
					$rid = $row_get_rounds['round_id'];
					if($comma == 0){
						$rounds = "$rid";
					}else{
						$rounds = "$rounds,$rid";
					}
					$comma++;
				} while ($row_get_rounds = mysql_fetch_assoc($get_rounds));
				
				
		mysql_free_result($get_rounds);
				
		//Delete States for rounds
		$query_del_states = "Delete from state where round_id in ($rounds)";
		mysql_query($query_del_states, $CAPM);
		//Delete Orders for rounds
		$query_del_orders = "Delete from orders where round_id in ($rounds)";
		mysql_query($query_del_orders, $CAPM);
		//Delete Market for rounds
		$query_del_Market = "Delete from Market where round_id in ($rounds)";
		mysql_query($query_del_Market, $CAPM);
		//Delete Cash for rounds
		$query_del_Cash = "Delete from Cash where round_id in ($rounds)";
		mysql_query($query_del_Cash, $CAPM);
		//Delete Clearing for rounds
		$query_del_Clearing = "Delete from Clearing where round_id in ($rounds)";
		mysql_query($query_del_Clearing, $CAPM);
		//Delete Assets for experiment
		$query_del_Asset = "Delete from asset where experiment_id = $exid";
		mysql_query($query_del_Asset, $CAPM);
		//Delete Rounds for experiment
		$query_del_Round = "Delete from Round where experiment_id = $exid";
		mysql_query($query_del_Round, $CAPM);
		//Delete Users for experiment
		$query_del_Users = "Delete from Users where experiment_id = $exid AND user_type != 'admin'";
		mysql_query($query_del_Users, $CAPM);
		//Delete Experiment
		$query_del_Experiment = "Delete from Experiment where experiment_id = $exid";
		mysql_query($query_del_Experiment, $CAPM);
		
		//change current Experiment
		
		mysql_select_db($database_CAPM, $CAPM);
		$query_exp_find = "SELECT experiment_id FROM experiment order by experiment_id DESC";
		$exp_find = mysql_query($query_exp_find, $CAPM) or die(mysql_error());
		$row_exp_find = mysql_fetch_assoc($exp_find);
		$totalRows_exp_find = mysql_num_rows($exp_find);
		
		if($totalRows_exp_find == 0){
			$HTTP_SESSION_VARS['cur_experiment'] = 0;
			header("Location: create_exp.php?msg=ED");
		}
		
		$N_expid = $row_exp_find['experiment_id'];
		$HTTP_SESSION_VARS['cur_experiment'] = $N_expid;
		
		$query_change_cur = "UPDATE experiment set current = 1 WHERE experiment_id = $N_expid";
		mysql_query($query_change_cur, $CAPM) or die(mysql_error());
		
		$N_expid = $HTTP_SESSION_VARS['cur_experiment'];
		 
		mysql_select_db($database_CAPM, $CAPM);
		$query_round_find = "SELECT round_id FROM round WHERE round.experiment_id = $N_expid AND round.current = 1";
		$round_find = mysql_query($query_round_find, $CAPM) or die(mysql_error());
		$row_round_find = mysql_fetch_assoc($round_find);
		$totalRows_round_find = mysql_num_rows($round_find);
		
		$HTTP_SESSION_VARS['cur_round'] = $row_round_find['round_id'];
		
		
		mysql_free_result($round_find);
		mysql_free_result($exp_find);

		
		if(isset($HTTP_GET_VARS['vc'])){
			if($HTTP_GET_VARS['vc']==34223){
				$uname = $HTTP_GET_VARS['u_name'];
				header("Location: create_exp.php?msg_ce=RU&u_name=$uname");
			}else{
			header("Location: status_admin.php?msg=C");
		}
		}else{
		header("Location: status_admin.php?msg=C");
		}
	}else{
	header("Location: status_admin.php?msg=EY");
	}
}else{
header("Location: ../index.php");
}
?>