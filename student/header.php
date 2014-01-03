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
<?php session_start();
$status = $HTTP_SESSION_VARS['status'];

if(isset($HTTP_SESSION_VARS['status']))
{
	if($status == "admin")
		{
		header("Location: ../index.php?autherror=NS");
		}
	elseif($status == "student")
		{ //Should display the page
		}
	else 
	{
	header("Location: ../index.php?autherror=NL");
	}
} else 
{
header("Location: ../index.php?autherror=NL");
} ?>