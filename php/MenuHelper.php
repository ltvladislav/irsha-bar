<?php

class MethodResult {
    public $success; // bool
    public $message; // string
    public $code; // int
   
    function __construct($succ = true, $mess = null, $cod = null) {
        $this->success = $succ;
        $this->message = $mess;
        $this->code = $cod;
    }
}

class MenuHelper {
    
	private static function addChildType($fathers, $child) {
		for ($j = 0; $j < count($fathers); $j++) {
			if ($fathers[$j]["Id"] == $child["FatherTypeId"]) {
				if (!isset($fathers[$j]["ChildTypes"])) {
					$fathers[$j]["ChildTypes"] = [];
				}
				$fathers[$j]["ChildTypes"][count($fathers[$j]["ChildTypes"])] = $child;
				return $fathers;
			}
		}
		return $fathers;
	}

	public static function GetAllDishTypes() {
		require_once 'ESQ.php';

		$esq = new EntitySchemaQuery("DishType");
		$esq->allColumn = false;
		$esq->AddColumn("Id");
		$esq->AddColumn("Name");
		$fatherColumn = $esq->AddColumn("DishFatherTypeId", "FatherTypeId");
		
		$fatherColumn->OrderDirection = OrderDirection::ASC;
		$fatherColumn->OrderPosition = 1;
		
		$collection = $esq->GetEntityCollection();
		
		$data = array();
		
		
		foreach($collection as $key => $value) {
			$row = $value->getValuesCollection();
			if (is_null($row["FatherTypeId"])) {
				array_push($data, $row);
			}
			else {
				$data = self::addChildType($data, $row);
			}
		}
		return $data;
	}
    
    public static function OrderConfirm($orderProducts, $userId) {
        require_once 'ESQ.php';
        require_once 'Livlag.php';
        require_once 'constants.php';

        if (is_null($orderProducts)) {
            return new MethodResult(false, "Корзина пуста");
        }
        if (is_null($userId) || empty($userId)) {
            return new MethodResult(false, "Помилка авторизації", 22);
        }
        
        $order = EntitySchemaQuery::CreateEntity("Ord");
        $order->SetDefaultValues();
        $order->SetColumnValue("ClientId", $userId);
        $order->SetColumnValue("OrdStatusId", OrderStatus::$registerId);
        $order->SetColumnValue("Datetime", Livlag::GetNowTime());
        
        $order->Save();
        
        $orderId = $order->GetColumnValue("Id");
        foreach ($orderProducts['collection'] as $key => $value) {
            
            $orderDish = EntitySchemaQuery::CreateEntity("DishInOrd");
            $orderDish->SetDefaultValues();
            $orderDish->SetColumnValue("Price", +$value['price']);
            $orderDish->SetColumnValue("Quantity", +$value['count']);
            $orderDish->SetColumnValue("OrdId", $orderId);
            $orderDish->SetColumnValue("DishId", $value['id']);
            
            $orderDish->Save();
    	}
        
        return new MethodResult(true);
    }
}

?>