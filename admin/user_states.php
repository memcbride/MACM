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
$colname_assets = $HTTP_SESSION_VARS['cur_experiment'];

mysql_select_db($database_CAPM, $CAPM);
$query_assets = "SELECT asset_id, name FROM asset WHERE experiment_id = $colname_assets";
$assets = mysql_query($query_assets, $CAPM) or die(mysql_error());
$row_assets = mysql_fetch_assoc($assets);
$totalRows_assets = mysql_num_rows($assets);


mysql_select_db($database_CAPM, $CAPM);
$query_drop_rounds = "SELECT round_id, round_num FROM round WHERE experiment_id = $colname_assets ORDER BY round_num";
$drop_rounds = mysql_query($query_drop_rounds, $CAPM) or die(mysql_error());
$row_drop_rounds = mysql_fetch_assoc($drop_rounds);
$totalRows_drop_rounds = mysql_num_rows($drop_rounds);

$colname_round_id = $HTTP_SESSION_VARS['cur_round'];
if (isset($HTTP_POST_VARS['round'])) {
  $colname_round_id = (get_magic_quotes_gpc()) ? $HTTP_POST_VARS['round'] : addslashes($HTTP_POST_VARS['round']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_round_num = sprintf("SELECT round_num FROM round WHERE round_id = %s", $colname_round_id);
$round_num = mysql_query($query_round_num, $CAPM) or die(mysql_error());
$row_round_num = mysql_fetch_assoc($round_num);
$totalRows_round_num = mysql_num_rows($round_num);
?>

			
<?php $SQL1 = "SELECT DISTINCT s.user_id, u.user_name, r.round_num, c.amount";
	$SQL2 = ' '; 
	$SQL4 = ' ';
	$SQL5 = ' ';
	$SQL6 = ' ';
do { 
	$row = $row_assets['name'];
	$row_id = $row_assets['asset_id'];
	$SQL2 = "$SQL2, T$row.quantity '$row' ";
	$SQL4 = "$SQL4, state T$row ";
	$SQL6 = "$SQL6 AND T$row.asset_id = $row_id AND T$row.user_id = c.user_id AND T$row.round_id = r.round_id";
} while ($row_assets = mysql_fetch_assoc($assets)); 
	
	$round = $colname_round_id;
	$exp = $HTTP_SESSION_VARS['cur_experiment']; 
				 
	$SQL3 = "FROM cash c, users u, round r, state s";
	$SQL5 = "WHERE s.user_id = c.user_id AND u.user_id = s.user_id AND s.round_id = r.round_id  AND c.round_id = r.round_id  AND r.round_id = $round AND r.experiment_id = $exp";
	$SQL7 = "ORDER BY s.user_id";			 
$State_SQL = "$SQL1 $SQL2 $SQL3 $SQL4 $SQL5 $SQL6 $SQL7";

?>
<?php
mysql_select_db($database_CAPM, $CAPM);
$user_state = mysql_query($State_SQL, $CAPM) or die(mysql_error());
$row_user_state = mysql_fetch_assoc($user_state);
$totalRows_user_state = mysql_num_rows($user_state);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | User States</title>
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
                <td valign="top"><span class="redtitles"><div align="right"><!-- InstanceBeginEditable name="Description" -->Admin: User States<!-- InstanceEndEditable --></div></span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
			
			  <p><span class="redtitles">User State Information</span> </p>
              
<form method="POST" action="user_states.php">
        <p>Select the round for which you want the User state information and 
          then click on Get Info? 
          <select name="round">
                        <?php do { ?>
                        <option value="<?php echo $row_drop_rounds['round_id']?>" <?php if($row_drop_rounds['round_id'] == $colname_round_id) { echo "selected"; } ?>>Round 
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
        </p>
      
          <input type="submit" name="Submit" value="Get Info">
        
      </form>
              <BR><span class="redtitles">Users State Information Round <?php echo $row_round_num['round_num']; ?></span><BR> 
              <p>To edit a user's state information, click on the user's ID.</p>
				
               <table border="0" cellpadding="1" cellspacing="1">
	<tr bgcolor="#990000" class="topBar"> 
		<td>UserID</td>
		<td>User Name</td>
		          <td>Round</td>
                  <td>Cash</td>
		<?php mysql_data_seek($assets,0);
			$row_assets = mysql_fetch_assoc($assets);?>
			<?php do{ ?>
				<td><?php echo $row_assets['name']; ?></td>
			<?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
                  
	</tr>
	<?php $row1 = "#d0d0f0";
							$row2 = "#f0d0d0";
							$CurrentColor= $row1; ?>
	<?php do { 
	
			mysql_data_seek($assets,0);
			$row_assets = mysql_fetch_assoc($assets);?>
		
		<tr bgcolor="<?php echo $CurrentColor ?>"> 
           		<td><?php echo $row_user_state['user_id']; ?></td>
				  <td><a href="edit_user_states.php?uid=<?php echo $row_user_state['user_id']; ?>&rid=<?php echo $colname_round_id; ?>"><?php echo $row_user_state['user_name']; ?></a></td>
			<td><?php echo $row_user_state['round_num']; ?></td>
			<td><?php if(isset($row_user_state['amount'])){?>$<?php echo number_format($row_user_state['amount'],2); ?><?php }?></td>
			<?php do{ ?>
				<td>
				<?php $color = $row_assets['name'];
				 echo $row_user_state[$color]; ?></td>
			<?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
		</tr>
		<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
	<?php } while ($row_user_state = mysql_fetch_assoc($user_state)); ?>
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
mysql_free_result($user_state);

mysql_free_result($assets);
mysql_free_result($drop_rounds);
mysql_free_result($round_num);
?>


