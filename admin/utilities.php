<?php
session_start();
include_once("libs/dbfunctions.php");

foreach (glob("class/*.php") as $filename) {
	// echo $filename;
	include_once($filename);
}

$op = $_REQUEST['op'];

$operation  = array();
$operation = explode(".", $op);

// getting data for the class method
$params = array();
$_REQUEST['files'] = $_FILES;
$params = $_REQUEST;

$data = [$params];
// var_dump($data);

// callling the method of  the class
$foo = new $operation[0];
echo call_user_func_array(array($foo, trim($operation[1])), $data);
// }else{
// 	echo "invalid token";
// }
?>