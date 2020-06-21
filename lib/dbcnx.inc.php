<?php

ini_set('date.timezone', 'Africa/Lagos');
set_time_limit(1800);
$db_server = 'localhost';
$db_name = 'olivet_db';
$db_user = 'root';
$db_password = '';


//$db_server = 'localhost';
//$db_name = 'BabadBa';
//$db_user = 'baba_db';
//$db_password = 'Nhuf63!9';

//$db_connect = new mysqli($db_server, $db_user, $db_password,$db_name);
// $result = $mysqli->query("SELECT 'Hello, dear MySQL user!' AS _message FROM DUAL");
// $row = $result->fetch_assoc();
// echo htmlentities($row['_message']);
  $db_connect = mysql_connect($db_server, $db_user, $db_password) or trigger_error(mysql_error(), e_user_error);
 mysql_select_db($db_name, $db_connect);
