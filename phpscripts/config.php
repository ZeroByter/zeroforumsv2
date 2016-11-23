<?php
$sql_ip = "";
$sql_username = "";
$sql_password = "";
$sql_database = "";

function sql_connect(){
	$conn = mysqli_connect($sql_ip, $sql_username, $sql_password, $sql_database);
	return $conn;
}
