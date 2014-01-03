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
$oid_display_order = 1000;
if (isset($HTTP_GET_VARS['oid'])) {
  $oid_display_order = (get_magic_quotes_gpc()) ? $HTTP_GET_VARS['oid'] : addslashes($HTTP_GET_VARS['oid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_display_order = sprintf("SELECT orders.order_id, orders.order_time, orders.user_id, orders.round_id, orders.quantity, orders.price, orders.type, users.user_name, round.round_num, orders.asset_id FROM orders, users, round WHERE orders.user_id =  users.user_id AND round.round_id = orders.round_id AND orders.order_id = %s", $oid_display_order);
$display_order = mysql_query($query_display_order, $CAPM) or die(mysql_error());
$row_display_order = mysql_fetch_assoc($display_order);
$totalRows_display_order = mysql_num_rows($display_order);

$exid = $HTTP_SESSION_VARS['cur_experiment'];

mysql_select_db($database_CAPM, $CAPM);
$query_drop_asset = "SELECT asset_id, name FROM asset WHERE experiment_id = $exid";
$drop_asset = mysql_query($query_drop_asset, $CAPM) or die(mysql_error());
$row_drop_asset = mysql_fetch_assoc($drop_asset);
$totalRows_drop_asset = mysql_num_rows($drop_asset);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin : Edit Order</title>
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
                  <div align="right"><!-- InstanceBeginEditable name="Description" --> 
                    Admin : Edit Order<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --><span class="redtitles">Edit 
                  Order Information for <?php echo $row_display_order['user_name']; ?></span> 
<p>To edit the user order information, enter new information in the fields 
        provided. Most the fields are self-explanatory. </p>
      <form method=POST action="update_orders.php">
        <table border="0" cellpadding="3">
          <tr> 
            <th align=center>OrderID</th>
            <th align=center>OrderTime</th>
            <th align=center>Round</th>
            <th align=center>Type</th>
            <th align=center>Asset</th>
            <th align=center>Quantity</th>
            <th align=center>Price</th>
          </tr>
          <tr>        
                        <td align=center><?php echo $row_display_order['order_id']; ?></td>
                        <td align=center><?php echo $row_display_order['order_time']; ?></td>
                        <td align=center><?php echo $row_display_order['round_num']; ?></td>
						<td align=center>
<input name='type' type=text value="<?php echo $row_display_order['type']; ?>" size=12 maxlength=10></td>       
						<td align=center>
						<select name="asset">
                            <?php do { ?>
                            <option value="<?php echo $row_drop_asset['asset_id']?>" <?php if($row_drop_asset['asset_id'] == $row_display_order['asset_id']){echo "selected";} ?>><?php echo $row_drop_asset['name']?></option>
                            <?php } while ($row_drop_asset = mysql_fetch_assoc($drop_asset));
  							$rows = mysql_num_rows($drop_asset);
  							if($rows > 0) {
      							mysql_data_seek($drop_asset, 0);
	  							$row_drop_asset = mysql_fetch_assoc($drop_asset);
  							} ?>
                          </select></td>       
						<td align=center>
<input name='quantity' type=text value="<?php echo $row_display_order['quantity']; ?>" size=7 maxlength=7></td>       
						<td align=center>
<input name='price' type=text value="<?php echo $row_display_order['price']; ?>" size=7 maxlength=7></td>      
 </tr>
        </table>
                    <input name='OID' type=hidden value="<?php echo $row_display_order['order_id']; ?>">   
                    
 
        <input type='submit' value="update" name="submit">
                    <input type='submit' value='delete' name="submit">
                  </form>
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
mysql_free_result($display_order);

mysql_free_result($drop_asset);
?>

