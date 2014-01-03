<?php require_once('../Connections/CAPM.php'); ?>
<?php include "header.php"; ?>
<?php
$eid_Get_assets = "1";
if (isset($HTTP_POST_VARS['exid'])) {
  $eid_Get_assets = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['exid'] : addslashes($HTTP_POST_VARS['exid']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Get_assets = sprintf("SELECT asset.name, asset.asset_id FROM asset WHERE asset.experiment_id = %s", $eid_Get_assets);
$Get_assets = mysql_query($query_Get_assets, $CAPM) or die(mysql_error());
$row_Get_assets = mysql_fetch_assoc($Get_assets);
$totalRows_Get_assets = mysql_num_rows($Get_assets);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin : Insert Final Prices</title>
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
                    Admin : Insert Final Prices<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" -->
				Fill in the prices you calculated from the drawing they will be used to calculate each users standing.
				
				<form action="end_exp.php" method="post">
                    
					<?php $count = 1;
					do { ?>
                    Price of <?php echo $row_Get_assets['name']; ?>: <input name="asset<?php echo $count; ?>" type="text">
                    <input name="asset_id<?php echo $count; ?>" type="hidden" value="<?php echo $row_Get_assets['asset_id']; ?>"> <br>
                    <?php 
					$count++;
					} while ($row_Get_assets = mysql_fetch_assoc($Get_assets)); ?>
                   

  <input name="num_assets" type="hidden" value="<?php echo $totalRows_Get_assets; ?>">
  <input name="rid" type="hidden" value="<?php echo $HTTP_POST_VARS['rid']; ?>">
 <input name="rnum" type="hidden" value="<?php echo $HTTP_POST_VARS['rnum']; ?>">
  <input name="exid" type="hidden" value="<?php echo $HTTP_POST_VARS['exid']; ?>">
<input name="Submit" type="submit"></form>
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
mysql_free_result($Get_assets);
?>
