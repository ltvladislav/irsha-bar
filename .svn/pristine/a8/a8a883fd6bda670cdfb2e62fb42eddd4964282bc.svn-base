<?
require_once "ESQ.php";

$clientEsq = $_POST["esq"];
$esq = EntitySchemaQueryConverter::GetFromCustomObject($clientEsq);

$DataJSON = array();
            $DataJSON["clientesq"] = json_encode($clientEsq);
 			//$DataJSON["sql"] = $esq->_getFilterSql();
			$DataJSON["serveresq"] = json_encode($esq);
$method = $_POST["method"];
try {
    $DataJSON["success"] = true;
    switch ($method) {
        case "getEntityCollection":
            $DataJSON["collection"] = $esq->GetEntityCollection();
            break;
        case "getEntity":
            $id = $_POST["recordId"];
            $DataJSON["entity"] = $esq->GetEntity($id);
            break;
        case "queryExecute":
            $id = $_POST["id"];
            $DataJSON["result"] = $esq->Execute();
            break;
        default:
            $DataJSON["success"] = false;
            $DataJSON["ErrorMessage"] = "Invalid method name.";
    }
}
catch (Exception $exception) {
    $DataJSON["success"] = false;
    $DataJSON["ErrorMessage"] = "Failed failed. " . $exception->getMessage(); //$exception->message;
}

header("Content-Type: application/json; charset=UTF-8");
$DataJSON = json_encode($DataJSON);
echo $DataJSON;

?>