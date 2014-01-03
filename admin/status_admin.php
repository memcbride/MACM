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
<?php include "header.php"; ?>
<?php
$expid = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Ex_status = "SELECT round_id, round_num, opened FROM round WHERE current = 1 AND experiment_id = $expid";
$Ex_status = mysql_query($query_Ex_status, $CAPM) or die("1");//mysql_error()
$row_Ex_status = mysql_fetch_assoc($Ex_status);
$totalRows_Ex_status = mysql_num_rows($Ex_status);


mysql_select_db($database_CAPM, $CAPM);
$query_ex_info = "SELECT experiment_name, number_assets, Count(users.user_id) AS user_id, status FROM experiment, users WHERE experiment.experiment_id =  $expid AND users.experiment_id = $expid AND experiment.experiment_id = users.experiment_id GROUP BY users.experiment_id";
$ex_info = mysql_query($query_ex_info, $CAPM) or die("2");
$row_ex_info = mysql_fetch_assoc($ex_info);
$totalRows_ex_info = mysql_num_rows($ex_info);

$round = $row_Ex_status['round_id'];
if($totalRows_ex_info == 0){
	$round = 0;
}
mysql_select_db($database_CAPM, $CAPM);
$query_test_for_clear = "SELECT DISTINCT asset_id FROM state WHERE round_id = $round";
$test_for_clear = mysql_query($query_test_for_clear, $CAPM) or die("3");
$row_test_for_clear = mysql_fetch_assoc($test_for_clear);
$totalRows_test_for_clear = mysql_num_rows($test_for_clear);

$ex_id = $expid;

 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin | Experiment Status</title>
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
                    Experiment Status<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> 
				      <strong><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#CC0000">
					<?php 
					if(isset($HTTP_GET_VARS['msg'])){
						if($HTTP_GET_VARS['msg'] == "C"){
							echo "The experiment has been deleted";
						}elseif($HTTP_GET_VARS['msg'] == "EY"){
							echo "You must type 'Yes' with the Y capitalized to delete an experiment";
						}
					}
					?>
					</font></strong>
                  <p><span class="redtitles">Experiment Status</span><br>
                    To edit the experiment's status, click on the buttons below. 
                  </p>
                  <table border="0" cellspacing="1">
                    <tr bgcolor="#990000" class="topBar"> 
                      <td>round_id</td>
                      <td>Round Number</td>
                      <td>Round Status</td>
                      <td>Experiment Status</td>
                    </tr>
                    <tr> 
                      <td><?php echo $row_Ex_status['round_id']; ?></td>
                      <td><?php echo $row_Ex_status['round_num']; ?></td>
                      <td> 
                        <?php if ($row_Ex_status['opened'] == 1)
					  				{print "Opened";}
								else {print "Closed";} ?>
                      </td>
                      <td> 
                        <?php  if($row_ex_info['status'] == 1)
					  				{echo "Active";}
									else {echo "Complete";} ?>
                      </td>
                    </tr>
                  </table>
                  <form name="close_open" method="post" action="Update_r_status.php">
                    <input name="rid" type="hidden" value="<?php echo $row_Ex_status['round_id']; ?>">
                    <?php if($row_Ex_status['opened'] == 1){?>
                    <input type="submit" name="submit" value="Close Current Round" <?php if($totalRows_test_for_clear > 0 || $row_ex_info['status'] == 0){ echo "disabled";}?>>
                    <?php }else{ ?>
                    <input type="submit" name="submit" value="Open Current Round" <?php if($totalRows_test_for_clear > 0 || $row_ex_info['status'] == 0){ echo "disabled"; } ?>>
                    <?php } ?>
                    <?php if($totalRows_test_for_clear > 0 || $row_Ex_status['opened'] == 0 || $row_ex_info['status'] == 0){ echo "&nbsp;&nbsp;<img src='../images/checkmark.jpg'>";} ?>
                  </form>
                  <form name="clear_m" method="post" action="clear_market.php">
                    <input name="rid" type="hidden" value="<?php echo $row_Ex_status['round_id']; ?>">
                    <input name="rnum" type="hidden" value="<?php echo $row_Ex_status['round_num']; ?>">
                    <input name="Submit" type="submit" value="Clear Market" <?php if($totalRows_test_for_clear > 0 || $row_Ex_status['opened'] == 1 || $row_ex_info['status'] == 0){ echo "Disabled";}?>>
                    <?php if($totalRows_test_for_clear > 0 || $row_ex_info['status'] == 0){ echo "&nbsp;&nbsp;<img src='../images/checkmark.jpg'>";} ?>
                  </form>
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td><form name="create" method="post" action="Create_new_round.php">
                          <input name="rid" type="hidden" value="<?php echo $row_Ex_status['round_id']; ?>">
                          <input name="rnum" type="hidden" value="<?php echo $row_Ex_status['round_num']; ?>">
                          <input name="submit" type="submit" value="Create Next Round" <?php if($row_Ex_status['opened'] == 1 || $totalRows_test_for_clear == 0 || $row_ex_info['status'] == 0){ echo "disabled";} ?>>
                        </form></td>
                      <td valign="top"><img src="../images/spacer.gif" width="10" height="1">or<img src="../images/spacer.gif" width="10" height="1"></td>
                      <td><form name="EndExp" method="post" action="final_prices.php">
                          <input name="rid" type="hidden" value="<?php echo $row_Ex_status['round_id']; ?>">
                          <input name="rnum" type="hidden" value="<?php echo $row_Ex_status['round_num']; ?>">
                          <input name="exid" type="hidden" value="<?php echo $expid; ?>">
                          <input name="submit"  value="End Experiment" type="Submit" <?php if($totalRows_test_for_clear == 0 || $row_ex_info['status'] == 0){ echo "disabled";} ?>>
                          <?php if($row_ex_info['status'] == 0){echo "&nbsp;&nbsp;<img src='../images/checkmark.jpg'>";} ?>
                        </form></td>
                    </tr>
                  </table>
                  <p></p>
                  <p><span class="redtitles">Experiment Information</span><br>
                    To edit an experiment's information, click on the experiment's 
                    Name.<BR>
					To delete an experiment you must type "Yes" in the box provided (capital Y) and hit the delete button. This will remove all record of that experiment form all tables. </p>
                  <table border="0" cellpadding="1">
                    <tr bgcolor="#990000" class="topBar"> 
                      <td>experiment_name</td>
                      <td># Participants</td>
                      <td>number_assets</td>
                      <td bgcolor="#FFFFFF"><font color="#000000"> Delete Experiment</font></td>
                    </tr>
                    <tr> 
                      <td><a href="edit_experiment.php?exid=<?php echo $HTTP_SESSION_VARS['cur_experiment']; ?>">
					  <?php echo $row_ex_info['experiment_name']; ?></a></td>
                      <td><?php echo $row_ex_info['user_id']; ?></td>
                      <td><?php echo $row_ex_info['number_assets']; ?></td>
                      <td><form action="delete_exp.php" method="post" name="form5">
                          <input type="hidden" name="exid" value="<?php echo $HTTP_SESSION_VARS['cur_experiment']; ?>">
                          <input name="check" type="text" size="3" maxlength="3">
                          <input name="delete"  value="Delete" type="Submit">
                        </form></td>
                    </tr>
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
mysql_free_result($Ex_status);

mysql_free_result($ex_info);

mysql_free_result($test_for_clear);
?>

