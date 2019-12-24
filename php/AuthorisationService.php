<?
require_once "SiteHelper.php";


$action = $_POST["action"];

if ($action === "ChangePassword") {
    $result = SiteHelper::ChangePassword($_POST["config"]);

    $DataJSON["success"] = $result->success;
    if ($result->success) {
        $DataJSON["ErrorMessage"] = $result->message;
    }
}
else if ($action === "Exit") {
    SiteHelper::DeleteUserFromSession();
    $DataJSON["success"] = true;
}

header("Content-Type: application/json; charset=UTF-8");
$DataJSON = json_encode($DataJSON);
echo $DataJSON;

?>