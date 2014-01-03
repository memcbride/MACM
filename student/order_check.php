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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Student: Verify Order</title>
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
                    Verify Order <!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" -->     
				<span class="redtitles">Verify Your Limit Order:</span>
      <p>Please verify your bid/offer information below. </p>
      <ol>
        <li>Clicking the submit button will add the order to your current order 
          file. </li>
        <li>Once submitted, an order <font size="+1" color="#990000"> <b>cannot</b></font> 
          be reversed.</li>
        <li> Clicking the cancel button will reset the values of the form and 
          return you to the main student page.</li>
      </ol>
      <form method=POST action="order_action.php">
        <table border="0" cellpadding="3">
          <tr> 
            <th>Asset</th>
            <th>Bid or Offer</th> 
            <th>Quantity</th>
            <th>Price</th>
          </tr>
          <tr> 
		  <td>
		  <?php 
		  $aid = $HTTP_POST_VARS['asset'];
		  mysql_select_db($database_CAPM, $CAPM); 
			$query_assets = "SELECT asset.name FROM asset WHERE asset.asset_id = $aid"; 
			$assets= mysql_query($query_assets, $CAPM) or die(mysql_error()); 
			$row_assets = mysql_fetch_assoc($assets); 
			$totalRows_assets = mysql_num_rows($assets);

		  	echo $row_assets['name']?></td> 
		  <td><?php echo $HTTP_POST_VARS['type']; ?></td> 
		  <td><?php echo $HTTP_POST_VARS['quantity']; ?></td> 
		  <td><?php echo $HTTP_POST_VARS['price']; ?></td>
 </tr>
        </table>
        <input type=hidden name='asset' value="<?php echo $HTTP_POST_VARS['asset']; ?>">   
		<input type=hidden name='type' value="<?php echo $HTTP_POST_VARS['type']; ?>">   
		<input type=hidden name='quantity' value="<?php echo $HTTP_POST_VARS['quantity']; ?>">   
		<input type=hidden name='price' value="<?php echo $HTTP_POST_VARS['price']; ?>">  
 
        <input type=submit value="submit order" name="submit">
      </form>
      <form method=POST action="order_form.php">
        <input type=submit value="cancel order">
      </form>

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
mysql_free_result($M_status);
?>