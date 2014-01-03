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

$clearing = $HTTP_POST_VARS['marketid'];
$volume = $HTTP_POST_VARS['volume'];
$price = $HTTP_POST_VARS['price'];

//check for invalid entrys
$volume = trim($volume);
$price = trim($price);
if(!is_numeric($volume) || !isset($volume))
{
$volume = 0;
}
if(!is_numeric($price) || !isset($price))
{
$price = 0;
}

//update market entry
mysql_select_db($database_CAPM, $CAPM);
$change_market = "UPDATE clearing set volume = $volume, price = $price WHERE clearing_id = $clearing";
mysql_query($change_market, $CAPM) or die(mysql_error());

$round =  $HTTP_POST_VARS['round'];

header("Location: rounds.php?round=$round");
?>