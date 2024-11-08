<?php
$dbhost = "localhost";
$db_user = "root";
$db_password = "root";
$db_name = "autoread_api";
$db = new mysqli($dbhost, $db_user, $db_password, $db_name);
if($db->connect_errno > 0) {
	die('Unable to connect to database [' . $db->connect_error . ']');
}
?>