<?php
ini_set('date.timezone','Africa/Lagos');
$db_server = 'localhost';
$db_name = 'kromoxome';

$db_user     = 'root';
$db_password = 'ugochukwu';

//$db_server = '192.168.201.6';
//$db_name   = 'agent_one';
//
//
//
//
//$db_user     = 'ugo.malu';
//$db_password = 'accessis4life';

$db_connect = mysql_connect($db_server, $db_user, $db_password) or trigger_error(mysql_error(),e_user_error);

//echo "gffgg ".$db_connect;
mysql_select_db($db_name,$db_connect);
	 
?>