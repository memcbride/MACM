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
$expid_Ex_status = 1;
if (isset($HTTP_SESSION_VARS['cur_experiment'])) {
  $expid_Ex_status = (get_magic_quotes_gpc()) ? $HTTP_SESSION_VARS['cur_experiment'] : addslashes($HTTP_SESSION_VARS['cur_experiment']);
}
mysql_select_db($database_CAPM, $CAPM);
$query_Ex_status = "SELECT round.round_num, experiment. experiment_name, round.opened FROM round, experiment WHERE round.current = 1   AND  experiment.experiment_id = $expid_Ex_status  AND round.experiment_id = experiment.experiment_id";
$Ex_status = mysql_query($query_Ex_status, $CAPM) or die(mysql_error());
$row_Ex_status = mysql_fetch_assoc($Ex_status);
$totalRows_Ex_status = mysql_num_rows($Ex_status);
?>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Welcome <?php echo ucfirst($HTTP_SESSION_VARS['user_name']); ?><BR>
      Experiment: <?php echo $row_Ex_status['experiment_name']; ?><BR>
      Round: <?php echo $row_Ex_status['round_num']; ?><BR>
		Status: <?php if ($row_Ex_status['opened'] == 1)
					  				{print "Opened";}
								else {print "Closed";} ?></td>
  </tr>
  <tr>
    <td><div style="border: solid 1px #999999; padding:5px">
<table width="140" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
         <td bgcolor="#990000"><span class="navtitles">&nbsp;User Options</span></td>
    </tr>
    <tr>
        <td><a href="status_student.php" class="Snav">Status</a><BR>
			<?php if ($row_Ex_status['opened'] == 1)
				{echo "<a href='order_form.php' class='Snav'>Place an Order</a><BR>";} ?>
				<a href="results.php" class="Snav">View Market</a><BR>
              <a href="past_orders.php" class="Snav">View Past Orders</a><BR>
              <a href="change_info.php" class="Snav">Edit Your Info</a><BR>
			<a href="../logout.php" class="Snav">Log Out</a>
		</td>
	</tr>
</table>
</DIV></td>
  </tr>
</table>
<?php
mysql_free_result($Ex_status);
?>
