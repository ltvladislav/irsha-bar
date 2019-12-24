<?
class Livlag {
    public static function NewGuid() {
        if (function_exists('com_create_guid')) {
			return com_create_guid();
		}
		else {
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = substr($charid, 0, 8).$hyphen
					.substr($charid, 8, 4).$hyphen
					.substr($charid,12, 4).$hyphen
					.substr($charid,16, 4).$hyphen
					.substr($charid,20,12);
			return $uuid;
		}
	}
	static function IsString($valueS) {
		return $valueS . "" === $valueS;
	}
	static function Containt($string, $reg) {
		return strpos($string, $reg) !== false;
	}
	static function FieldsCount($object) {
		$c = 0;
		foreach ($object as $key => $value) {
			$c++;
		}
		return $c;
	}


	public static function getTableFromEntityCollection($entityCollection) {
		$generateClass = "tblclass" . rand();
		$html = "<style>." . $generateClass . " td { border: 1px solid; }</style>";
		$html .= "<table class=\"" . $generateClass . "\">";
		$firstRow = is_array($entityCollection) ? $entityCollection[0] : $entityCollection;

		$html .= "<thead><tr>";
		foreach($firstRow->GetValuesCollection() as $key => $value) {
			$html .= "<td>" . $key . "</td>";
		}
		$html .= "</tr></thead>";
		$html .= "<tbody>";
		if (is_array($entityCollection)) {
			foreach($entityCollection as $rowKey => $rowValue) {
				$html .= "<tr>";
				foreach($rowValue->GetValuesCollection() as $key => $value) {
					$html .= "<td>" . $value . "</td>";
				}
				$html .= "</tr>";
			}
		}
		else {
			$html .= "<tr>";
			foreach($entityCollection->GetValuesCollection() as $key => $value) {
				$html .= "<td>" . $value . "</td>";
			}
			$html .= "</tr>";
		}
		$html .= "</tbody>";
		$html .= "</table>";
	}
    
    public static function GetNowTime() {
		date_default_timezone_set('Europe/Kiev');
		return date('Y-m-d H:i:s');
    }
}

?>