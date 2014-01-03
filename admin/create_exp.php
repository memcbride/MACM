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
<?php include('header.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><!-- InstanceBegin template="/Templates/CAPM.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<!-- InstanceBeginEditable name="doctitle" --> 
<title>Admin | Create a New Experiment</title>
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
                    Create a New Experiment<!-- InstanceEndEditable --></div>
                  </span></td>
              </tr>
              <tr>
                <td><!-- InstanceBeginEditable name="main" --> <span class="redtitles"> 
                  <?php if(isset($HTTP_GET_VARS['msg_ce'])){
		if($HTTP_GET_VARS['msg_ce'] == "N"){
			echo "The Experiment was not added, The name you choose was already used. Change the name and run it again.";
		}
		if($HTTP_GET_VARS['msg_ce'] == "C"){
			echo "The experiment was added and activated";
		}
		if($HTTP_GET_VARS['msg_ce'] == "ED"){
			echo "Your experiment was deleted but you have no other experiments. Please add a new one.";
		}
		if($HTTP_GET_VARS['msg_ce'] == "NE"){
			echo "You do not have an experiment. Please add one.";
		}
		if($HTTP_GET_VARS['msg_ce'] == "RU"){
			$name = $HTTP_GET_VARS['u_name'];
			echo "At least one of the users in the file was already in the system. Please review your file and change the user id &#8220;$name&#8221;";
		}
		}
		?><BR>
                  </span> To start a new experiment you must provide the info 
                  on users and their market starting status. This information 
                  is provided through the "File of Data" input box. Please browse 
                  your computer and find the .txt file that has the comma delimitated 
                  list of values. Please make sure to create this file to the 
                  exact specifications shone below the form. 
                  <form name="form1" method="post" action="create_exp_action.php" enctype="multipart/form-data">
                    <p>Experiment Name: 
                      <input type="text" name="ex_name">
                      <BR>
                      <input type="hidden" name="MAX_FILE_SIZE" value="200000">
                      File of Data: 
                      <input name="start_info" type="file">
                    </p>
                    <input name="submit" type="submit" value="submit">
                  </form>
                  The first line of the text doc must be the column names starting 
                  with U_name (the users name), password (user Password), cash 
                  (amount of cash the user starts with. You then list the assets 
                  you are using in the experiment. The asset names in the first 
                  line will be there names in the experiment. You can have as 
                  many assets in the list as you want, you must however place 
                  the quantities for each asset, for each user below them. 
                  <table border="1">
                    <tr> 
                      <td>name,password,email,cash,Red,White,Blue,Green,Gold<BR>
                        user1,1resu,user1@muohio.edu,5000,0,0,0,0,100<br>
                        user2,2resu,user2@muohio.edu,5000,0,0,0,0,100<br>
                        user3,3resu,user3@muohio.edu,5000,0,0,0,0,100<br>
                        user4,4resu,user4@muohio.edu,5000,0,0,0,0,100<br>
                        user5,5resu,user5@muohio.edu,5000,0,0,0,0,100<br>
                        user6,6resu,user6@muohio.edu,5000,0,0,0,100,0<br>
                        user7,7resu,user7@muohio.edu,5000,0,0,0,100,0<br>
                        user8,8resu,user8@muohio.edu,5000,0,0,0,100,0<br>
                        user9,9resu,user9@muohio.edu,5000,0,0,0,100,0<br>
                        user10,01resu,user10@muohio.edu,5000,0,0,0,100,0<br>
                        user11,11resu,user11@muohio.edu,5000,0,0,100,0,0<br>
                        user12,21resu,user12@muohio.edu,5000,0,0,100,0,0<br>
                        user13,31resu,user13@muohio.edu,5000,0,0,100,0,0<br>
                        user14,41resu,user14@muohio.edu,5000,0,0,100,0,0<br>
                        user15,51resu,user15@muohio.edu,5000,0,0,100,0,0<br>
                        user16,61resu,user16@muohio.edu,5000,0,100,0,0,0<br>
                        user17,71resu,user17@muohio.edu,5000,0,100,0,0,0<br>
                        user18,81resu,user18@muohio.edu,5000,0,100,0,0,0<br>
                        user19,91resu,user19@muohio.edu,5000,0,100,0,0,0<br>
                        user20,01resu,user20@muohio.edu,5000,0,100,0,0,0<br>
                        user21,12resu,user21@muohio.edu,5000,100,0,0,0,0<br>
                        user22,22resu,user22@muohio.edu,5000,100,0,0,0,0<br>
                        user23,32resu,user23@muohio.edu,5000,100,0,0,0,0<br>
                        user24,42resu,user24@muohio.edu,5000,100,0,0,0,0<br>
                        user25,52resu,user25@muohio.edu,5000,100,0,0,0,0<br></td>
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