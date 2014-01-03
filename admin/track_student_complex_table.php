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
				  <td><a href="edit_user_states.php?uid=<?php echo $row_user_state['user_id']; ?>&rid=<?php echo $HTTP_GET_VARS['round']; ?>"><?php echo $row_user_state['user_name']; ?></a></td>
			<td><?php echo $row_user_state['round_num']; ?></td>
			<td><?php echo $row_user_state['amount']; ?></td>
			<?php do{ ?>
				<td>
				<?php $color = $row_assets['name'];
				 echo $row_user_state[$color]; ?></td>
			<?php } while ($row_assets = mysql_fetch_assoc($assets)); ?>
		</tr>
		<?php if ($CurrentColor == $row1) {$CurrentColor = $row2;}
				elseif ($CurrentColor == $row2) {$CurrentColor = $row1;} ?>
	<?php } while ($row_user_state = mysql_fetch_assoc($user_state)); ?>
	</TABLE>