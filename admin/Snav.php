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
if(!isset($HTTP_GET_VARS['vardefset'])){
?>
<div style="border: solid 1px #999999; padding:5px">
<table width="140" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
        <td bgcolor="#990000"><span class="navtitles">&nbsp;Admin Options</span></td>
    </tr>
	<tr>
		<td> 
			<a href="status_admin.php" class="Snav">Status</a><BR>
            		<a href="users.php" class="Snav">Users</a><BR>
            		<a href="user_states.php" class="Snav">User States</a><BR>
            		<a href="rounds.php" class="Snav">Rounds</a><BR>
			<a href="orders.php" class="Snav">Orders</a><BR>
			<a href="results.php" class="Snav">View Results</a><BR>
			<a href="track_students.php" class="Snav">Track Student</a><BR>
			<a href="../logout.php" class="Snav">Log Out</a>
		</td>
	</tr>
</table>
</DIV>
<?php } ?>