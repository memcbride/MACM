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
mysql_select_db($database_CAPM, $CAPM);
$query_experiments = "SELECT experiment_id, experiment_name FROM experiment ORDER BY experiment_id DESC";
$experiments = mysql_query($query_experiments, $CAPM) or die(mysql_error());
$row_experiments = mysql_fetch_assoc($experiments);
$totalRows_experiments = mysql_num_rows($experiments);
?>

<div style="border: solid 1px #999999; padding:5px">
<table width="140" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr> 
      <td bgcolor="#990000"><span class="navtitles">&nbsp;Edit Experiment</span></td>
    </tr>
	<tr> 
      <td valign="top"> 
	  <a href="create_exp.php" class="Snav">Create New Experiment</a> 
        <HR size="1" noshade>
		<TABLE border="0" cellpadding="0" cellspacing="0">
          <?php $HTTP_SESSION_VARS['url'] = sprintf("%s%s%s","http://",$HTTP_SERVER_VARS['HTTP_HOST'],$HTTP_SERVER_VARS['PHP_SELF']); ?>
          <?php do { ?><TR>
		    <TD valign="top"> 
              <?php $expid = $row_experiments['experiment_id'];?>
              <?php if($HTTP_SESSION_VARS['cur_experiment'] == $expid){?>
              <img src="../images/check.gif" width="10" height="10"> 
              <?php }else{ ?>
			  <img src="../images/spacer.gif" width="10" height="10">
			   <?php } ?>
            </TD>
		    <TD valign="top"><a href="set_exp.php?expid=<?php echo $expid; ?>" class="Snav"><?php echo $row_experiments['experiment_name']; ?></a></TD>
		</TR>
		<?php } while ($row_experiments = mysql_fetch_assoc($experiments)); ?>
      </TABLE>
	  </td>
	</tr>
</table>
</DIV>
<?php
mysql_free_result($experiments);
?>
