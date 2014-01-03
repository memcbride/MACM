<?php 
/*
Copyright (c) 1999 - 2003 Mark E. McBride & Christian Ratterman. All Rights Reserved.

You may study, use, modify, and distribute this software for any 
purpose within an academic environment provided that this copyright 
notice appears in all copies. Business and Corporate use requires 
expressed permission from the authors listed below.

@Authors: Mark E. McBride & Christian Ratterman
@Contact: mcbridme@muohio.edu
@Version: 2.0
@date: May 6, 2003
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/sitestyles.css"></head>

<body bgcolor="#FFFFFF">
<table cellpadding="0"
	cellspacing="0" 
	border="0">
  <tr> 
    <td valign=TOP><img src="images/headerlogo.gif"> </td>
  </tr>
</table>
<!-- This table is for the body of your page --> 
<table 
	width="584" 
	cellpadding="5"  
	cellspacing="0" 
	border="0">
  <tr> 
    <td
			valign=TOP colspan="3">
      <div align="right"><a href="http://mcbridme.sba.muohio.edu/capm/"><font face="Verdana, Arial, Helvetica, sans-serif" color="#990000"><b>Login:</b></font></a></div>
    </td>
  </tr>
  <tr> 
    <td
			valign=TOP colspan="3"> 
      <div align="left">
        <p align="left"><font size="+1"><b>W</b></font>elcome to the CAPM Experiment 
          Connection. </p>
        <p align="left">The CAPM Experiment pages provide a means for submitting 
          your bids/offers and seeing the results of trading each round.</p>
</div>
    </td>
  </tr>
  <tr> 
    <td 
			width="141"
			valign=TOP> 
      <p><font color="#990000">Please log in.</font> </p>
      <ol>
        <li>You may get your user id and password from the professor.</li>
        <li>Enter theUserID and Password which are case-sensitive.</li>
        <li>Click &quot;Login.&quot;</li>
      </ol>
      <h3 align="center">&nbsp;</h3>
    </td>
    <td 
			width="3"
			valign=TOP>&nbsp; </td>
    <td 
			width="410"
			valign=TOP> 
      <strong><font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="#CC0000">
<?php 
if(isset($HTTP_GET_VARS['msg'])){
	if($HTTP_GET_VARS['msg'] == "L"){
		echo "You have been logged out, close this window for extra protection";
	}elseif($HTTP_GET_VARS['msg'] == "BP"){
		echo "Your loggin failed, If you feel you may have typed something incorrect please try again.";
	}elseif($HTTP_GET_VARS['msg'] == "DBI"){
		echo "Your Database has been created. Please login.";
	}

}
?>
</font></strong>
</p>
<form name="form1" method="post" action="user_auth.php">
  <strong>Login:</strong> 
  <input type="text" name="login"><BR>
  <strong>Password:</strong> 
  <input type="password" name="password"><BR>
  <input name="Enter" type="submit" value="Login">
</form>
<p></p>
      <h3>&nbsp;</h3>
    </td>
  </tr>
  <tr> 
    <td
			colspan="3"> 
      <center>
        <p>Copyright &copy; 2000 - 2002, Mark E. McBride &amp; Christian Ratterman, 
          All Rights Reserved.<br>
          <cite><insert_modified> </cite> </p>
      </center>
    </td>
  </tr>
</table>
</body>
</html>

