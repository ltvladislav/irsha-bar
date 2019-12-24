<?php

$config = $_POST;
session_start();
if ($config["method"] == "get") {
	$value = $_SESSION[$config["key"]];
	header("Content-Type: application/json; charset=UTF-8");
	$DataJSON["success"] = true;
	$DataJSON["value"] = $value;
	$DataJSON = json_encode($DataJSON);
	echo $DataJSON;
}
else if ($config["method"] == "set") {
	$_SESSION[$config["key"]] = $config["value"];
	
	header("Content-Type: application/json; charset=UTF-8");
	$DataJSON["success"] = true;
	$DataJSON = json_encode($DataJSON);
	echo $DataJSON;
}
else {
	header("Content-Type: application/json; charset=UTF-8");
	$DataJSON["success"] = false;
	$DataJSON["ErrorMessage"] = "Invalid method";
	$DataJSON = json_encode($DataJSON);
	echo $DataJSON;
}
?>