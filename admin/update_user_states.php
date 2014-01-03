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

//aquire the needed values for loop and the form variables aquisition.
$rid_Values = 1;
if (isset($HTTP_POST_VARS['rid'])) {
  $rid_Values = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['rid'] : addslashes($HTTP_POST_VARS['rid']);
}
$uid_Values = 7;
if (isset($HTTP_POST_VARS['uid'])) {
  $uid_Values = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['uid'] : addslashes($HTTP_POST_VARS['uid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Values = sprintf("SELECT state.asset_id, asset.name, state.state_id FROM state, asset WHERE state.round_id = %s AND state.user_id = %s AND state.asset_id = asset.asset_id", $rid_Values,$uid_Values);
$Values = mysql_query($query_Values, $CAPM) or die(mysql_error());
$row_Values = mysql_fetch_assoc($Values);
$totalRows_Values = mysql_num_rows($Values);

//****************************************************************//
// Loops through the update statements to change the state values //
//****************************************************************//
do{
$AN = $row_Values['name']; //asset_name
$s = $row_Values['state_id']; //state_id
$formQ = "$AN$s"; //asset_name and state_id together to form name of fields used on form
$q = $HTTP_POST_VARS[$formQ]; //value placed in the form

// Trims white space off numbers and makes sure the entered value is numeric
$q = trim($q);
if(!is_numeric($q) || !isset($q))
{
$q = 0;
}

// Update the state table
mysql_select_db($database_CAPM, $CAPM);
$query_update = "update state set quantity = $q Where state_id = $s";
mysql_query($query_update, $CAPM) or die(mysql_error());

}while($row_Values = mysql_fetch_assoc($Values));

// update the cash table
$cash = $HTTP_POST_VARS['Cash'];

mysql_select_db($database_CAPM, $CAPM);
$query_update = "update cash set amount = $cash WHERE user_id = $uid_Values AND round_id = $rid_Values";
mysql_query($query_update, $CAPM) or die(mysql_error());



mysql_free_result($Values);

header("Location: user_states.php?round=$rid_Values");
?>

