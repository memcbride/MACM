<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
// DATABASE VARIABLES
// If you do not know what these values should be, please contact 
// your hosting provider.  Most problems occur as a result of
// an error in this file.


// HOST NAME OR SERVER ADDRESS 
// Usually this is the name of your domain (www.yoursite.com).
// In some cases the hostname will instead be "localhost".
$hostname_CAPM = "localhost";


// MySQL USERNAME
// This is the username you use to access your MySQL database.
// Note: This is usually NOT the same as your FTP username.
$username_CAPM = "myUser";


// MySQL PASSWORD
// The password used to access your MySQL database.
// Note: This is usually NOT the same as your FTP password.
$password_CAPM = "myPassword";

 
//  DO NOT EDIT BELOW THIS LINE

// DATABASE NAME
// Some hosting providers assign you a specific database.
// If this is the case, change this variable to the name of
// the database they require, otherwise you can leave it alone.
// If you are not using an existing database you might have
// to create the database prior to installing pMachine.
$database_CAPM = "CAPM";

// DATABASE CONNECT
$CAPM = mysql_pconnect($hostname_CAPM, $username_CAPM, $password_CAPM) or die(mysql_error());
?>