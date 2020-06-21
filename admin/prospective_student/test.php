<?php 
session_start();

$reg_id = $_SESSION["reg_id"];
var_dump($reg_id);
if(isset($reg_id)){
    echo "session started";
}
?>