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
<?php require_once('../Connections/CAPM.php'); ?>
<?php 
include('header.php');


$colname_drop_rounds = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $colname_drop_rounds = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_drop_rounds = sprintf("SELECT round_id, round_num FROM round WHERE experiment_id = %s ORDER BY round_num", $colname_drop_rounds);
$drop_rounds = mysql_query($query_drop_rounds, $CAPM) or die(mysql_error());
$row_drop_rounds = mysql_fetch_assoc($drop_rounds);
$totalRows_drop_rounds = mysql_num_rows($drop_rounds);

$expid_drop_asset = "1";
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid_drop_asset = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_drop_asset = sprintf("SELECT asset.name, asset.asset_id FROM asset WHERE asset.experiment_id = %s", $expid_drop_asset);
$drop_asset = mysql_query($query_drop_asset, $CAPM) or die(mysql_error());
$row_drop_asset = mysql_fetch_assoc($drop_asset);
$totalRows_drop_asset = mysql_num_rows($drop_asset);

//Set info from user choice to populate tables
if(isset($HTTP_POST_VARS['round'])){
	$rid = $HTTP_POST_VARS['round'];
	$aid = $HTTP_POST_VARS['asset'];
}else{
	$rid = $HTTP_SESSION_VARS['cur_round'];
	$aid = $row_drop_asset['asset_id'];
}

//Retreves clearing price and Volume
mysql_select_db($database_CAPM, $CAPM);
$query_summary = "SELECT clearing.price, clearing.volume FROM clearing WHERE clearing.round_id =$rid AND clearing.asset_id = $aid";
$summary = mysql_query($query_summary, $CAPM) or die(mysql_error());
$row_summary = mysql_fetch_assoc($summary);
$totalRows_summary = mysql_num_rows($summary);

//Retreves the Detailed view of the asset in that round
mysql_select_db($database_CAPM, $CAPM);
$query_M_detail = "SELECT market.price, market.Qd, market.Qs, market.Nd, market.Ns, market.Ed FROM market WHERE market.round_id = $rid AND market.asset_id = $aid";
$M_detail = mysql_query($query_M_detail, $CAPM) or die(mysql_error());
$row_M_detail = mysql_fetch_assoc($M_detail);
$totalRows_M_detail = mysql_num_rows($M_detail);
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Market Results</title>
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
                    Market Results<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" -->      <form method="post" action="results.php" name="">
                    <p>Select the round and asset for which you want the market results and 
                      then click on Get Info?<br>
          <select name="round">
                        <?php do { ?>
                        <option value="<?php echo $row_drop_rounds['round_id']?>" <?php if($row_drop_rounds['round_id'] == $rid) { echo "selected"; $disr = $row_drop_rounds['round_num'];} ?>>Round 
                        <?php echo $row_drop_rounds['round_num']?></option>
                        <?php
						} while ($row_drop_rounds = mysql_fetch_assoc($drop_rounds));
  								$rows = mysql_num_rows($drop_rounds);
  								if($rows > 0) {
      					mysql_data_seek($drop_rounds, 0);
	  						$row_drop_rounds = mysql_fetch_assoc($drop_rounds);
  					}
?>
                      </select>
                      <select name="asset">
                        <?php
do {  
?>
                        <option value="<?php echo $row_drop_asset['asset_id']?>" <?php if($row_drop_asset['asset_id'] == $aid) { echo "selected"; $disa = $row_drop_asset['name'];  } ?>><?php echo $row_drop_asset['name']?></option>
                        <?php
} while ($row_drop_asset = mysql_fetch_assoc($drop_asset));
  $rows = mysql_num_rows($drop_asset);
  if($rows > 0) {
      mysql_data_seek($drop_asset, 0);
	  $row_drop_asset = mysql_fetch_assoc($drop_asset);
  }
?>
                      </select>
          <input type="submit" name="Submit" value="Get Info">
        </p>
      </form>
      <p>Scroll down the screen to see the details of each market.</p>
         
 
                  <span class="redtitles">Market Summary for the <?php echo $disa; ?> Asset in Round <?php echo $disr; ?>:</span>
      <TABLE>
	  <TR bgcolor="#990000" class="topBar">
<TD>Asset</TD>
<TD>Volume</TD>
<TD>Price</TD>
</TR>
<TR>
<TD ALIGN=center><?php echo $disa; ?></TD>
<TD ALIGN=center><?php echo $row_summary['volume']; ?></TD>
<TD ALIGN=center><?php if(isset($row_summary['price'])){ echo "$"; echo number_format($row_summary['price'],2);}?></TD>
</TABLE>
 <BR>
                 
                <span class="redtitles">Market Details for the <?php echo $disa; ?> Asset 
                    in Round <?php echo $disr; ?> :</span> 
                  <TABLE>
	<TR bgcolor="#990000" class="topBar">			  
<TD>Price</TD>
<TD>Qd</TD>
<TD>Qs</TD>
<TD>Nd</TD>
<TD>Ns</TD>
<TD>Ed</TD>
</TR>
<?php $row1 = "#d0d0f0";
	$row2 = "#f0d0d0";
	$CurrentColor= $row1; ?>
<?php do { ?>
<tr bgcolor="<?php echo $CurrentColor ?>"> 
<TD><?php if(isset($row_M_detail['price'])){ echo "$"; echo number_format($row_M_detail['price'],2);}?></TD>
<TD><?php echo $row_M_detail['Qd']; ?></TD>
<TD><?php echo $row_M_detail['Qs']; ?></TD>
<TD><?php echo $row_M_detail['Nd']; ?></TD>
<TD><?php echo $row_M_detail['Ns']; ?></TD>
<TD><?php echo $row_M_detail['Ed']; ?></TD>
</TR>
<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
	elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
<?php } while ($row_M_detail = mysql_fetch_assoc($M_detail)); ?>
</TABLE>
 
      <p>&nbsp;</p><!-- InstanceEndEditable --></td>
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
mysql_free_result($drop_rounds);

mysql_free_result($drop_asset);

mysql_free_result($summary);

mysql_free_result($M_detail);
?>
