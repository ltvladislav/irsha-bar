<?

require_once "Connection.php";
require_once 'Livlag.php';

class OrderDirection {
    const ASC = 1;
    const DESC = 2;
	
	static function GetSql($expressionType) {
        switch ($expressionType) {
            case self::ASC:
                return "ASC";
            case self::DESC:
                return "DESC";
        }
    }
}
class ArithmeticOperation {
    const SUM = 1;
    const SUBTRACT = 2;
    const MULTIPLICATION = 3;
    const DEVIDE = 4;

	static function GetSql($arithmeticOperation) {
        switch ($arithmeticOperation) {
            case self::SUM:
                return "+";
            case self::SUBTRACT:
                return "-";
            case self::MULTIPLICATION:
                return "*";
            case self::DEVIDE:
                return "/";
        }
    }
}
class ExpressionType {
    const AND = 1;
    const OR = 2;

    static function GetSql($expressionType) {
        switch ($expressionType) {
            case self::AND:
                return "AND";
            case self::OR:
                return "OR";
        }
    }
}
class ConditionType {
    const Equal = 1;
    const NotEqual = 2;
    const More = 3;
    const MoreEqual = 4;
    const Less = 5;
    const LessEqual = 6;

    static function GetSql($conditionType) {
        switch($conditionType) {
            case self::Equal:
                return "=";
            case self::NotEqual:
                return "<>";
            case self::More:
                return ">";
            case self::MoreEqual:
                return ">=";
            case self::Less:
                return "<";
            case self::LessEqual:
                return "<=";
        }
    }
}
class AggregationFunction {
    const SUM = 1;
    const COUNT = 2;
    const MAX = 3;
    const MIN = 4;

    static function GetSql($aggregationFunction) {
        switch ($aggregationFunction) {
            case self::SUM:
                return "SUM";
            case self::COUNT:
                    return "COUNT";
            case self::MAX:
                    return "MAX";
            case self::MIN:
                    return "MIN";
        }
    }
}


abstract class IColumn {
	const ColumnType = 1;
    const ArithmeticColumnType = 2;
    const AggregationColumnType = 3;
	
	public $type;
	
	public $name;
	public $OrderDirection;
    public $OrderPosition;
	
	function __construct($type, $name = null) {
		$this->type = $type;
        $this->setAlias($name);
    }
	function getNameInDB() {
        return "";
    }
	function getSql() {
        return $this->getNameInDB() . " " . $this->name;
    }
    function setAlias($value) {
        $this->name = $value;
    }
	function isOrder() {
		return isset($this->OrderDirection) && !is_null($this->OrderDirection) && !empty($this->OrderDirection);
	}
	function orderPositionCompare($a, $b) {
		$ap = !is_null($a->OrderPosition) ? $a->OrderPosition : 100;
		$bp = !is_null($b->OrderPosition) ? $b->OrderPosition : 100;
		return $ap == $bp ? 0 : $ap > $bp ? 1 : -1;
	}
}
class Column extends IColumn {
	
	public $nameInDB;
	
	function __construct($nameInDB, $name = null) {
		parent::__construct(IColumn::ColumnType, $name);
		$this->nameInDB = $nameInDB;
		$this->setAlias($name);
    }
	
	function getNameInDB() {
        return $this->nameInDB;
    }
	
	private function _getAliasFromDBName($alias) {
		return (!is_null($alias) && $alias != "") ? $alias : 
				(Livlag::Containt($this->nameInDB, ".") ? end(explode(".", $this->nameInDB)) : $this->nameInDB);
	}
    function setAlias($value) {
        $this->name = $this->_getAliasFromDBName($value);
    }
}
class ArithmeticColumn extends IColumn {
	public $column1; // IColumn
	public $column2; // IColumn
	public $arithmeticOperation;
	
	function __construct($column1, $column2, $arithmeticOperation, $name = null) {
        parent::__construct(IColumn::ArithmeticColumnType, $name);
		$this->column1 = $column1;
		$this->column2 = $column2;
		$this->setArithmeticOperation($arithmeticOperation);
    } 
	function setArithmeticOperation($value) {
        $this->arithmeticOperation = $value;
    }
	function getNameInDB() {
		return $this->column1->getNameInDB() . ArithmeticOperation::GetSql($this->arithmeticOperation) . $this->column2->getNameInDB();
    }
}
class AggregationColumn extends IColumn {
	
	public $column; // IColumn
    public $aggregationType;
	
    
	function __construct($column, $name = null, $aggregationType) {
        parent::__construct(IColumn::AggregationColumnType, $name);
		if (gettype($column) == "string") {
			$this->column = new Column($column);
		}
		else {
			$this->column = $column;
		}
		$this->setAggregationFunction($aggregationType);
    }
	
    function setAggregationFunction($value) {
        $this->aggregationType = $value;
    }
    function isAggregation() {
        return !is_null($this->aggregationType);
    }
    function getNameInDB() {
		return AggregationFunction::GetSql($this->aggregationType) . "(" . $this->column->getNameInDB() . ")";
    }
	
}

class DataBaseHandler {
    static function GetCollectionByQuery($queryText) {
        $connection = Connection::getConnection();

        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }
        
        $result = $connection->query($queryText);
        
        if (!$result) {
            $connection->close();
            throw new Exception("Query execute error!");
        }
        $data = [];
        for ($i = 0; $i < $result->num_rows; $i++) {
            $data[$i] = $result->fetch_assoc();
        }
        $connection->close();
        return $data;
    }

    static function ExecuteQuery($queryText) {
        $connection = Connection::getConnection();

        if ($connection->connect_error) {
            throw new Exception("Connection failed: " . $connection->connect_error);
        }
        
        $result = $connection->query($queryText);
		
        if (!$result) {
            $connection->close();
            throw new Exception("Query execute error!" . $queryText);
        }
        $connection->close();
        return true;
    }
}

class Entity {
    public $values; // custom object
    public $schemaName; // string
    public $IsNew; // bool

    function __construct($schemaName, $collection = null) {
        $this->schemaName = $schemaName;
        $this->values = array();
        if (is_null($collection)) {
            $this->IsNew = true;
            return;
        }
        $this->IsNew = false;
        foreach ($collection as $key => $value) {
            $this->values[$key] = $value;
        }
    }

    function GetColumnValue($columnName) {
        return $this->values[$columnName];
    }
    function GetValuesCollection() {
        return $this->values;
    }
    function SetColumnValue($columnName, $value) {
        $this->values[$columnName] = $value;      
    }

    function SetDefaultValues() {
        $this->values["Id"] = Livlag::NewGuid();
    }

    static function Create($schemaName) {
        $entity = new Entity($schemaName);
        // Some code for set columns config;
        return $entity;
    }

    function Save() {
        if ($this->IsNew) {
            $sql = $this->_getInsertSql();
        }
        else {
            $sql = $this->_getUpdateSql();
        }
        //echo $sql;
        DataBaseHandler::ExecuteQuery($sql);
    }

    private function _getInsertSql() {
        $sqlInsert = "INSERT INTO " . $this->schemaName . " (";
        $sqlValues = "VALUES (";

        
        foreach ($this->values as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            $sqlInsert .= $key;
            $sqlValues .= (Livlag::IsString($value) ? "'" : "") . $value . (Livlag::IsString($value) ? "'" : "");
            $sqlInsert .= ", ";
            $sqlValues .= ", ";
        }
        $sqlInsert = substr($sqlInsert, 0, -2);
        $sqlValues = substr($sqlValues, 0, -2);
        $sqlInsert .= ")";
        $sqlValues .= ")";

        $sql = $sqlInsert . " " . $sqlValues;
        return $sql;
    }
    private function _getUpdateSql() {
        $sql = "UPDATE " . $this->schemaName . " SET ";

        foreach ($this->values as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            if ($key == "Id") {
                continue;
            }
            $sql .= $key . "=" . (Livlag::IsString($value) ? "'" : "") . $value . (Livlag::IsString($value) ? "'" : "");
            $sql .= ", ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE Id='" . $this->values["Id"] . "'";
        return $sql;
    }
}

class Filter {
    public $expressionType;
    private $filters;

    function __construct() {
        if (func_num_args() > 0) {
            $this->expressionType = func_get_arg(0);
        }
        else {
            $this->expressionType = ExpressionType::AND;
        }
        
        $this->filters = array();
    }
    
    function Add($name, $filter) {
        $this->filters[$name] = $filter;
    }
    function AddItem($filter) {
        $this->Add("f" . rand(), $filter);
    }
    function getSql() {
        if ($this->isEmpty()) {
            return "";
        }
        $sql = "";
        $expressSql = ExpressionType::GetSql($this->expressionType);
        foreach($this->filters as $key => $value) {
            $sql .= "(" . $value->getSql() . ") " . $expressSql . " ";
        }
        
        return substr($sql, 0, strlen($sql) - strlen($expressSql) - 2);
    }
    function isEmpty() {
        return empty($this->filters);
    }

    function _containtsAggregationColumns() {
        foreach($this->filters as $key => $value) {
            if ($value->_containtsAggregationColumns()) {
                return true;
            }
        }
        return false;
    }
}

abstract class IFilterItem {
    const WithParamType = 1;
    const NullType = 2;

    protected $column;
    private $type;

    function __construct($column, $type) {
        $this->column = $column;
        $this->type = $type;
    }

    function _containtsAggregationColumns() {
        return $this->column->type == IColumn::AggregationColumnType;
    }
}

class FilterItemWithParameter extends IFilterItem {
    private $conditionType;
    private $value;

    function __construct($column, $conditionType, $value) {
        parent::__construct($column, IFilterItem::WithParamType);
        $this->conditionType = $conditionType;
        $this->value = $value;
    }
    function getSql() {
        $sql = $this->column->getNameInDB() . " ";
        $sql .= ConditionType::GetSql($this->conditionType) . " ";
        if ($this->value === true) {
            return $sql . "1";
        }
        if ($this->value === false) {
            return $sql . "0";
        }
        return $sql . (Livlag::IsString($this->value) ? "'" : "") . $this->value . 
            (Livlag::IsString($this->value) ? "'" : "");
    }
}

class FilterItemNull extends IFilterItem {
    private $isNull;

	function __construct($column, $isNull = true) {
        parent::__construct($column, IFilterItem::NullType);
        $this->isNull = $isNull;
    }
    function getSql() {
        return $this->column->getNameInDB() . " IS " . ($this->isNull ? "" : "NOT ") . "NULL";
    }
}

class JoinTable {
    private $schemaName; // String (example (main schema 'Ingredient'): DishId(Dish).Type(DishType).Name)
                        // (main schema 'ProductType'): [Product:TypeId:Id].Name
    private $schemaColumn; // String

    private $joinToSchema; // String
    private $joinToColumn; // String
    
    private $alias; // String
	private $joinSchema;

    function __construct($joinString = "", $joinToSchema = "") {
		
		if ($joinString == "") {
            return;
        }
		
		if ($joinString{0} == "=" || $joinString{0} == ">" || $joinString{0} == "<") {
			$this->joinSchema = $joinString{0} == "=" ? "INNER" :
				$joinString{0} == "<" ? "RIGHT" : "LEFT";
			$joinString = substr($joinString, 1);
		}
		else {
			$this->joinSchema = "LEFT";
		}
		
        
        if ($joinString{0} == "[") {
            $joinString = substr($joinString, 1, -1);
            
            $args = explode(":", $joinString);
            $this->schemaName = $args[0];
            $this->schemaColumn = $args[1];
            $this->joinToColumn = count($args) > 2 ? $args[2] : "Id";
        }
        else {
            $this->schemaColumn = "Id";
            $this->joinToColumn = substr($joinString, 0, strpos($joinString, "("));
            $this->schemaName = substr($joinString, strpos($joinString, "(")+1, - 1);
        }
        
        $this->joinToSchema = $joinToSchema;
    }

    function getAlias() {
        return $this->alias;
    }
    function setAlias($value) {
        $this->alias = $value;
    }

    function equals($joinTable) {
        return $this->schemaName === $joinTable->schemaName &&
                $this->schemaColumn === $joinTable->schemaColumn &&
                $this->joinToSchema === $joinTable->joinToSchema &&
                $this->joinToColumn === $joinTable->joinToColumn;
    }
    function getSql() {
        return " LEFT JOIN " . $this->schemaName . " " . $this->getAlias() . " ON " . 
            $this->getAlias() . "." . $this->schemaColumn . "=" . 
            $this->joinToSchema . "." . $this->joinToColumn;
    }
}

class EntitySchemaQuery {
	
    public $schemaName; // string
    public $columns; // array Column
    private $joinedTables; // array JoinTable
    public $Filters; // Filter
    public $allColumn = false; // bool

    function __construct($schemaName) {
        $this->schemaName = $schemaName;
        $this->columns = array();
        $this->Filters = new Filter();

        $this->joinedTables = array();
    }

    function AddColumn($columnName, $alias = null) {
		$col = $this->CreateColumn($columnName, $alias);
        array_push($this->columns, $col);
        return $col;
    }
    function AddAggregationColumn($aggregationType, $columnName, $alias = null) {
		$col = $this->CreateAggregationColumn($aggregationType, $columnName, $alias);
        array_push($this->columns, $col);
        return $col;
    }
	function AddArithmeticColumn($columnName1, $columnName2, $arithmeticOperation, $alias = null) {
		$col = $this->CreateArithmeticColumn($columnName1, $columnName2, $arithmeticOperation, $alias);
        array_push($this->columns, $col);
        return $col;
    }
	
	function CreateColumn($columnName, $alias = null) {
		$col = $this->_getColumnByColumnOrName($columnName);
		if (!is_null($alias)) {
			$col->setAlias($alias);
		}
		return $col;
	}
	function CreateAggregationColumn($aggregationType, $columnName, $alias = null) {
		$col = $this->_getColumnByColumnOrName($columnName);
		$aCol = new AggregationColumn($col, $alias, $aggregationType);
		return $aCol;
	}
	function CreateArithmeticColumn($columnName1, $columnName2, $arithmeticOperation, $alias = null) {
		$col1 = $this->_getColumnByColumnOrName($columnName1);
		$col2 = $this->_getColumnByColumnOrName($columnName2);
		$aCol = new ArithmeticColumn($col1, $col2, $arithmeticOperation, $alias);
		return $aCol;
	}
	
	private function _getColumnByColumnOrName($column) {
		if (gettype($column) == "string") {
			return $this->_getColumnWithAddJoinTable($column);
		}
		else {
			return $column;
		}
	}
    private function _getColumnWithAddJoinTable($columnName, $joinTbl = "mntbl") {
        if (!Livlag::Containt($columnName, ".")) {
            return new Column($joinTbl . "." . $columnName);
        }
        $tbl = substr($columnName, 0, strpos($columnName, "."));
        $table = $this->_addJoinTable(new JoinTable($tbl, $joinTbl));
        $column = substr($columnName, strpos($columnName, ".") + 1);
        return $this->_getColumnWithAddJoinTable($column, $table->getAlias());
    }
    function _addJoinTable($table) {
        foreach($this->joinedTables as $key => $value) {
            if ($table->equals($value)) {
                return $value;
            }
        }
        $table->setAlias("tbl" . count($this->joinedTables));
        array_push($this->joinedTables, $table);
        return $table;
    }

    function CreateFilterWithParameter($column, $conditionType, $value) {
        $col = $this->CreateColumn($column);
        return new FilterItemWithParameter($col, $conditionType, $value);
    }
    function CreateAggregationFilter($aggregationType, $column, $conditionType, $value) {
		$col = $this->CreateAggregationColumn($aggregationType, $column);
        return new FilterItemWithParameter($col, $conditionType, $value);
    }
    function CreateNullFilter($column) {
        $col = $this->CreateColumn($column);
        return new FilterItemNull($col, true);
    }
    function CreateNotNullFilter($column) {
        $col = $this->CreateColumn($column);
        return new FilterItemNull($col, false);
    }
    function CreateFilterGroup() {
        if (func_num_args() > 0) {
            return new Filter(func_get_arg(0));
        }
        return new Filter();
    }

    private function _getSelectSql() {
        $sql = "SELECT ";
        if ($this->allColumn || count($this->columns) == 0) {
            $sql .= "*";
        }
        else {
            for($i = 0; $i < count($this->columns); $i++) {
                $sql .= $this->columns[$i]->getSql() . ", ";
            }
            $sql = substr($sql, 0, -2);
        }
        $sql .= " FROM " . $this->schemaName . " mntbl";
        foreach($this->joinedTables as $key => $value) {
            $sql .= $value->getSql();
        }
        return $sql;
    }
    public function _getFilterSql() {
        $sql = $this->_getSelectSql();
        if (!$this->Filters->isEmpty()) {
            $sql .= " WHERE " . $this->Filters->getSql();
        }
        $sql .= $this->_groupSql();
        $sql .= $this->_orderSql();
        return $sql;
    }
    public function _getIdSql($id) {
        $sql = $this->_getSelectSql();
        $sql .= " WHERE ";
        if (!$this->Filters->isEmpty()) {
            $sql .= "(" . $this->Filters->getSql() . ")";
            $sql .= " AND ";
        }
        $sql .= "mntbl.Id='" . $id . "'";
        $sql .= $this->_groupSql();
        return $sql;
    }

    private function _groupSql() {
        if (!$this->_containtsAggregationColumns()) {
            return "";
        }
        $sql = "";
        for($i = 0; $i < count($this->columns); $i++) {
            if (!isset($this->columns[$i]->aggregationType)) {
                $sql .= $this->columns[$i]->name . ", ";
            }
        }
		$sql = $sql == "" ? "" :substr($sql, 0, -2);
        return $sql === "" ? "" : (" GROUP BY " . $sql);
    }
	private function _orderSql() {
		$ordCols = array_filter($this->columns, function($col) {
			return $col->isOrder();
		});
        if (count($ordCols) == 0) {
            return "";
        }
        usort($ordCols, array("IColumn", "orderPositionCompare"));
        
		$sql = " ORDER BY ";
		foreach ($ordCols as $key => $column) {
			$sql .= $column->name . " " . OrderDirection::GetSql($column->OrderDirection) . ", ";
		}
        $sql = substr($sql, 0, -2);
        return $sql;
    }
	
    function _containtsAggregationColumns() {
        foreach($this->columns as $key => $value) {
            if ($value->type == IColumn::AggregationColumnType) {
                return true;
            }
        }
        return $this->Filters->_containtsAggregationColumns();
    }

    function GetEntityCollection() {
		//return $this->_getFilterSql();
		//
        $result = DataBaseHandler::GetCollectionByQuery($this->_getFilterSql());
        $entitys = array();
        for ($i = 0; $i < count($result); $i++) {
            $entitys[$i] = new Entity($this->schemaName, $result[$i]);
        }
        return $entitys;
    }

    function GetEntity($id) {
		//return $this->_getIdSql($id);
		//
        $result = DataBaseHandler::GetCollectionByQuery($this->_getIdSql($id));
        return (count($result) > 0) ? new Entity($this->schemaName, $result[0]) : NULL;
    }
	public $tempStr;
    static function CreateEntity($schemaName) {
        return Entity::Create($schemaName);
    }
}

class EntitySchemaQueryConverter {
	static function GetFromCustomObject($object) {
		if (isset($object["type"])) {
			return self::GetQueryFromCustomObject($object);
		}
        $current = new EntitySchemaQuery($object["schemaName"]);
        $current->allColumn = $object["allColumn"] == "true";
		foreach ($object["columns"] as $key => $column) {
			$col = self::_getColumnByColumnOrName($column, $current);
			$current->AddColumn($col);
		}
        $current->Filters = self::_AddFilterToEsq($object["Filters"], $current);
        return $current;
    }
	
	private static function _AddFilterToEsq($filter, $esq) {
		$newFilter = new Filter($filter["expressionType"]);
		
        foreach ($filter["filters"] as $key => $value) {
            if (is_null($value["filters"])) {
				
				$filt = self::_AddFilterItemToEsq($value, $esq);
						
				if (!is_null($filt)) {
					$newFilter->Add($key, $filt);
				}
            }
            else {
				$newFilter->Add($key, self::_AddFilterToEsq($value, $esq));
            }
        }
        return $newFilter;
    }
	private static function _AddFilterItemToEsq($value, $esq) {
		$col = self::_getColumnByColumnOrName($value["column"], $esq);
		
		if ($value["type"] == IFilterItem::WithParamType) {
			return $esq->CreateFilterWithParameter($col, $value["conditionType"], $value["value"]);
		}
		else if ($value["type"] == IFilterItem::NullType) {
			if ($value["isNull"] == "true") {
				return $esq->CreateNullFilter($col);
			}
			else {
				return $esq->CreateNotNullFilter($col);
			}		 
		}
		return null;
    }
	private static function _getColumnByColumnOrName($column, $esq) {
		if (gettype($column) == "string") {
			return $esq->CreateColumn($column);
		}
		
		if ($column["type"] == IColumn::ColumnType) {
			$col = $esq->CreateColumn($column["nameInDB"], $column["name"]);
		}
		else if ($column["type"] == IColumn::AggregationColumnType) {
			
			$col = $esq->CreateAggregationColumn($column["aggregationType"], self::_getColumnByColumnOrName($column["column"], $esq), $column["name"]);
		}
		else if ($column["type"] == IColumn::ArithmeticColumnType) {
			$col = $esq->CreateArithmeticColumn(self::_getColumnByColumnOrName($column["column1"], $esq), self::_getColumnByColumnOrName($column["column2"], $esq), $column["arithmeticOperation"], $column["name"]);
		}
		self::_addColumnConfig($col, $column);
		return $col;
	}
	private static function _addColumnConfig($column, $config) {
		if (isset($config["orderDirection"])) {
			$column->OrderDirection = $config["orderDirection"];
		}
		if (isset($config["orderPosition"])) {
			$column->OrderPosition = $config["orderPosition"];
		}
	}
	
	static function GetQueryFromCustomObject($object) {
		$type = $object["type"];
		if (strtolower($type) == "insert") {
			$current = new Insert($object["schemaName"], $object["values"]);
		}
		else if (strtolower($type) == "update") {
			$current = new Update($object["schemaName"], $object["values"]);
			$current->Filters = self::_AddFilterToEsq($object["Filters"], $current);
		}
        return $current;
    }
}




abstract class Query {
	public $schemaName; // string
    public $values; // array CustomObject

    function __construct($schemaName, $collection = null) {
        $this->schemaName = $schemaName;
        
		
		$this->values = array();
        if (is_null($collection)) {
            return;
        }
        foreach ($collection as $key => $value) {
            $this->values[$key] = $value;
        }
    }
	function SetColumnValue($columnName, $value) {
        $this->values[$columnName] = $value;      
    }
	
	function Execute() {
        $sql = $this->_getSql();
        //echo $sql;
        DataBaseHandler::ExecuteQuery($sql);
    }
	
	protected function _getSql() {
		return "";
	}
}
class Update extends Query {
    public $Filters; // Filter
	
	function __construct($schemaName, $collection = null) {
        parent::__construct($schemaName, $collection);
		$this->Filters = new Filter();
    } 
	
	
	function CreateColumn($columnName) {
		return $this->_getColumnByColumnOrName($columnName);
	}
	function CreateAggregationColumn($aggregationType, $columnName, $alias = null) {
		$col = $this->_getColumnByColumnOrName($columnName);
		$aCol = new AggregationColumn($col, $alias, $aggregationType);
		return $aCol;
	}
	function CreateArithmeticColumn($columnName1, $columnName2, $arithmeticOperation, $alias = null) {
		$col1 = $this->_getColumnByColumnOrName($columnName1);
		$col2 = $this->_getColumnByColumnOrName($columnName2);
		$aCol = new ArithmeticColumn($col1, $col2, $arithmeticOperation, $alias);
		return $aCol;
	}
	function CreateFilterWithParameter($column, $conditionType, $value) {
        $col = $this->CreateColumn($column);
        return new FilterItemWithParameter($col, $conditionType, $value);
    }
    function CreateAggregationFilter($aggregationType, $column, $conditionType, $value) {
		$col = $this->CreateAggregationColumn($aggregationType, $column);
        return new FilterItemWithParameter($col, $conditionType, $value);
    }
    function CreateNullFilter($column) {
        $col = $this->CreateColumn($column);
        return new FilterItemNull($col, true);
    }
    function CreateNotNullFilter($column) {
        $col = $this->CreateColumn($column);
        return new FilterItemNull($col, false);
    }
    function CreateFilterGroup() {
        if (func_num_args() > 0) {
            return new Filter(func_get_arg(0));
        }
        return new Filter();
    }
	
	private function _getColumnByColumnOrName($column) {
		if (gettype($column) == "string") {
			return new Column($column);
		}
		else {
			return $column;
		}
	}
	protected function _getSql() {
        $sql = "UPDATE " . $this->schemaName . " SET ";

        foreach ($this->values as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            if ($key == "Id") {
                continue;
            }
            $sql .= $key . "=" . (Livlag::IsString($value) ? "'" : "") . $value . (Livlag::IsString($value) ? "'" : "");
            $sql .= ", ";
        }
        $sql = substr($sql, 0, -2);
		if (!$this->Filters->isEmpty()) {
            $sql .= " WHERE " . $this->Filters->getSql();
        }
        return $sql;
    }
}
class Insert extends Query {
	protected function _getSql() {
        $sqlInsert = "INSERT INTO " . $this->schemaName . " (";
        $sqlValues = "VALUES (";

        
        foreach ($this->values as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            $sqlInsert .= $key;
            $sqlValues .= (Livlag::IsString($value) ? "'" : "") . $value . (Livlag::IsString($value) ? "'" : "");
            $sqlInsert .= ", ";
            $sqlValues .= ", ";
        }
        $sqlInsert = substr($sqlInsert, 0, -2);
        $sqlValues = substr($sqlValues, 0, -2);
        $sqlInsert .= ")";
        $sqlValues .= ")";

        $sql = $sqlInsert . " " . $sqlValues;
        return $sql;
    }
}


?>