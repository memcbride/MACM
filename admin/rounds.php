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
<?php include "header.php" ?>
<?php require_once('../Connections/CAPM.php'); ?>
<?php
$colname_Drop_rounds = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $colname_Drop_rounds = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Drop_rounds = sprintf("SELECT round_id, round_num FROM round WHERE experiment_id = %s ORDER BY round_num ASC", $colname_Drop_rounds);
$Drop_rounds = mysql_query($query_Drop_rounds, $CAPM) or die(mysql_error());
$row_Drop_rounds = mysql_fetch_assoc($Drop_rounds);
$totalRows_Drop_rounds = mysql_num_rows($Drop_rounds);


$exp = $HTTP_SESSION_VARS['cur_experiment'];

$colname_round_id = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['round'])) {
  $colname_round_id = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_round = "SELECT clearing.clearing_id, round.round_num, asset.name, clearing.volume, clearing.price FROM clearing, round, asset WHERE clearing.round_id = round.round_id  AND clearing.asset_id = asset.asset_id AND round.round_id = $colname_round_id AND round.experiment_id = $exp";
$round = mysql_query($query_round, $CAPM) or die(mysql_error());
$row_round = mysql_fetch_assoc($round);
$totalRows_round = mysql_num_rows($round);


mysql_select_db($database_CAPM, $CAPM);
$query_round_num = sprintf("SELECT round_num FROM round WHERE round_id = %s", $colname_round_id);
$round_num = mysql_query($query_round_num, $CAPM) or die(mysql_error());
$row_round_num = mysql_fetch_assoc($round_num);
$totalRows_round_num = mysql_num_rows($round_num);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Market Rounds</title>
<!-- InstanceEndEditable --> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable --> 
<style type="text/css">
<!--
.navtitles {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}

a.Snav:Link { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #990000}
a.Snav:visited{ font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #990000}
a.Snav:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #CCCCCC}
a.Snav:hover { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #CCCCCC}
p {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
.topBar {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	color: #FFFFFF;
}
.redtitles {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #990000;
}

-->
</style>
<style type="text/css">
<!--
.text {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../images/headerlogo.gif"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="150" valign="top">
		  <BR>
		  <?php include('Snav.php'); ?>
		  <BR>
            <?php 
		  if ($HTTP_SESSION_VARS['status'] == 'admin')
		  {
		  include('EXPnav.php');
		  } ?>
            <BR>
</td>
          <td width="20">&nbsp;</td>
		  <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="20">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top"><span class="redtitles">
                  <div align="right"><!-- InstanceBeginEditable name="Description" -->Admin: 
                    Market Rounds<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
                  <p><span class="redtitles">Market Rounds</span><br>
                    Select the round for which you want the market summary results 
                    and then click on Get Info? </p>
                  <form name="round" method="POST" action="rounds.php">
                    <select name="round">
                      <?php do { ?>
                      <option value="<?php echo $row_Drop_rounds['round_id']?>" <?php if($row_Drop_rounds['round_id'] == $colname_round_id) { echo "selected"; } ?>>Round 
                      <?php echo $row_Drop_rounds['round_num']?></option>
                      <?php
						} while ($row_Drop_rounds = mysql_fetch_assoc($Drop_rounds));
  								$rows = mysql_num_rows($Drop_rounds);
  								if($rows > 0) {
      					mysql_data_seek($Drop_rounds, 0);
	  						$row_Drop_rounds = mysql_fetch_assoc($Drop_rounds);
  					}
?>
                    </select>
                    <input type="submit" name="Submit" value="Submit">
                  </form>
                  <p><span class="redtitles">Market Information Round <?php echo $row_round_num['round_num']; ?> 
                    </span><br>
                    To edit the market information for a given round, click on 
                    the round's EntryID.<br>
                  </p>
                  <table border="0" cellspacing="2" cellpadding="2">
                    <tr bgcolor="#990000"> 
                      <td><span class="topBar">Market ID</span></td>
                      <td><span class="topBar">Round</span></td>
                      <td><span class="topBar">Asset</span></td>
                      <td><span class="topBar">Volume</span></td>
                      <td><span class="topBar">Price</span></td>
                    </tr>
                    <?php $row1 = "#d0d0f0";
							$row2 = "#f0d0d0";
							$CurrentColor= $row1; ?>
                    <?php do { ?>
                    <tr bgcolor="<?php echo $CurrentColor ?>"> 
                      <td><a href="admineditround.php?mid=<?php echo $row_round['clearing_id']?>"><?php echo $row_round['clearing_id']?></a></td>
                      <td><?php echo $row_round['round_num']?></td>
                      <td><?php echo $row_round['name']?></td>
                      <td><?php echo $row_round['volume']?></td>
                      <td><?php if(isset($row_round['price'])){?>$<?php echo number_format($row_round['price'],2);?><?php }?></td>
                    </tr>
                    <?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
						elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
                    <?php } while ($row_round = mysql_fetch_assoc($round)); ?>
                  </table>
                  <!-- InstanceEndEditable --></td>
              </tr>
              <tr>
                <td height="20">&nbsp;</td>
              </tr>
            </table></td>
          <td width="20">&nbsp;</td>
        </tr>
      </table>
</td>
  </tr>
  <tr>
    <td><div align="center">Copyright &copy; 2000 - 2002, Mark E. McBride &amp; 
        Christian Ratterman, All Rights Reserved. </div></td>
  </tr>
</table>

</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($Drop_rounds);

mysql_free_result($round);

mysql_free_result($round_num);
?>


