<?php
session_start();
include_once("libs/dbfunctions.php");
//error_reporting(0);
//////request OP
//if( isset($_REQUEST['app_token']) && $_REQUEST['app_token'] == $_SESSION['app_token'] )
//{
//	unset($_SESSION['app_token']);
// Include all classes in the classes folder

// foreach (glob("class/*.php") as $filename) {
// 	include_once($filename);
// }
var_dump($GLOBALS);
var_dump($_REQUEST);
// User.login
//$op = $_REQUEST['op'];
// //user.register
// //$op =  $dbobject->DecryptData("pacific",$op);
// $operation  = array();
// $operation = explode(".", $op);


// // getting data for the class method
// $params = array();
// $params = $_REQUEST;
// $data = [$params];


// //////////////////////////////
// /// callling the method of  the class
// $foo = new $operation[0];
// echo call_user_func_array(array($foo, trim($operation[1])), $data);
// //}else
// //{
// //	echo "invalid token";
// //}
?>