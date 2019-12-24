<?php

$msg = "";
if (isset($_GET["code"])) {
	$code = $_GET["code"];
	
	require_once 'ESQ.php';

	$esq = new EntitySchemaQuery("User");
	$esq->AddColumn("Id");
	$esq->AddColumn("ActivationStatus");

	$entity = $esq->GetEntity($code);
	
	if ($entity != NULL) {
		if ($entity->GetColumnValue("ActivationStatus") == 0) {
			$entity->SetColumnValue("ActivationStatus", 1);
			$entity->Save();
			$msg="Акаунт успішно активовано";
		}
		else {
			$msg="Ваш акаунт вже активовано";
		}
	}
	else {
		$msg="Невірний код активації";
	}
	
}
else {
	$msg = "Невірний URL";
}
session_start();
$_SESSION['ErrorMessage'] = $msg;
header( "Location: ../login.php" );

?>