<?
session_start();
$action = $_POST["action"];

if ($action === "OrderConfirm") {
	require_once 'MenuHelper.php';
	
	$result = MenuHelper::OrderConfirm($_SESSION["ShopCollection"], $_SESSION["userId"]);

	if ($result->success) {
		$_SESSION["ShopCollection"] = null;
	}

	$DataJSON["success"]= $result->success;
	$DataJSON["ErrorCode"] = $result->code;
	$DataJSON["ErrorMessage"] = $result->message;
	
}


header("Content-Type: application/json; charset=UTF-8");
$DataJSON = json_encode($DataJSON);
echo $DataJSON;
?>