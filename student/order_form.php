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
<?php require_once('../Connections/CAPM.php');  ?>
<?php
$M_status_ex = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $M_status_ex = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_M_status = "SELECT  round.opened FROM round, experiment WHERE round.current = 1   AND  experiment.experiment_id = $M_status_ex  AND round.experiment_id = experiment.experiment_id";
$M_status = mysql_query($query_M_status, $CAPM) or die(mysql_error());
$row_M_status = mysql_fetch_assoc($M_status);
$totalRows_M_status = mysql_num_rows($M_status);

if ($row_M_status['opened'] != 1){
header("Location: status_student.php");
}

$eid_assets = "0"; 
if (isset($HTTP_SESSION_VARS['cur_experiment'])) { 
$eid_assets = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']); 
} 
mysql_select_db($database_CAPM, $CAPM); 
$query_assets = sprintf("SELECT asset.name, asset.asset_id FROM asset WHERE asset.experiment_id = %s", $eid_assets); 
$assets= mysql_query($query_assets, $CAPM) or die(mysql_error()); 
$row_assets = mysql_fetch_assoc($assets); 
$totalRows_assets = mysql_num_rows($assets); ?> <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Student | Place Order</title>
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
                  <div align="right"><!-- InstanceBeginEditable name="Description" -->Student: 
                    Place Order <!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" -->      
				<span class="redtitles">Enter a Limit Order:</span>
      <p>Please fill in the following infomration. 
      <ol>
        <li>Select the Asset from the pull down menu.</li>
        <li>Select either Bid or Offer from the pull down menu.</li>
        <li>Enter the Quantity in the space provided.</li>
        <li>If entering an Offer, enter a minimum Price you are willing to sell 
          at.</li>
        <li>If entering a Bid, enter the maximum Price you are willing to pay.</li>
        <li>Click on the &quot;Submit Order&quot; button when you have entered 
          all the information.</li>
      </ol>
      <form method="POST" action="order_check.php" name="form">
        <table border="0" cellpadding="3">
          <tr bgcolor="#ffffff"> 
            <th align=center>Asset</th>
            <th align=center>Bid or Offer</th>
            <th align=center>Quantity</th>
            <th align=center>Price</th>
          </tr>
          <tr> 
            <td align=center> 
              <select name="asset" size=1>
                            <?php
do {  
?>
                            <option value="<?php echo $row_assets['asset_id']?>"><?php echo $row_assets['name']?></option>
                            <?php
} while ($row_assets = mysql_fetch_assoc($assets));
  $rows = mysql_num_rows($assets);
  if($rows > 0) {
      mysql_data_seek($assets, 0);
	  $row_assets = mysql_fetch_assoc($assets);
  }
?>
                          </select>
                        </td>
                        <td align=center> <select name="type" size=1>
                            <option value="bid">Bid</option>
                            <option value="offer">Offer</option>
                          </select></td>
            <td align=center> 
              <input type=text name="quantity" size=6 maxlength=6>
            </td>
            <td align=center> 
              <input type=text name="price" size=10 maxlength=10>
            </td>
          </tr>
        </table>
        <input type=submit value="Submit Order" name="submit">
      </form>
      <p>&nbsp;</p>
      <p>
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
mysql_free_result($assets);

mysql_free_result($M_status);
?>
