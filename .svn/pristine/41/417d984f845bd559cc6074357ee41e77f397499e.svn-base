


var Enums = {
    ExpressionType: {
        AND: 1,
        OR: 2
    },
    ConditionType: {
        Equal: 1,
        NotEqual: 2,
        More: 3,
        MoreEqual: 4,
        Less: 5,
        LessEqual: 6
    },
    AggregationFunction: {
        SUM: 1,
        COUNT: 2,
        MAX: 3,
        MIN: 4
    },
	OrderDirection: {
		ASC: 1,
    	DESC: 2
	},
	ArithmeticOperation: {
		SUM: 1,
    	SUBTRACT: 2,
    	MULTIPLICATION: 3,
    	DEVIDE: 4
	}
}

class IColumn {
    static get ColumnType() {return 1; }
    static get ArithmeticColumnType() {return 2; }
    static get AggregationColumnType() {return 3; }

    constructor(type, name) {
        this.type = type;
		this.setAlias(name);
        this.orderDirection = null;
        this.orderPosition = null;
    }
	setAlias(value) {
    	this.name = value;
    }
}
class Column extends IColumn {
    constructor(nameInDB, name) {
		super(IColumn.ColumnType, name);
        this.nameInDB = nameInDB;
    }
}
class ArithmeticColumn extends IColumn {
	constructor(column1, column2, arithmeticOperation, name = null) {
		super(IColumn.ArithmeticColumnType, name);
		this.column1 = column1;
		this.column2 = column2;
		this.setArithmeticOperation(arithmeticOperation);
    } 
	setArithmeticOperation(value) {
        this.arithmeticOperation = value;
    }
}
class AggregationColumn extends IColumn {
	constructor(column, name = null, aggregationType) {
		super(IColumn.AggregationColumnType, name);
		if (typeof(column) == "string") {
			this.column = new Column(column);
		}
		else {
			this.column = column;
		}
		this.setAggregationFunction(aggregationType);
    }
	
    setAggregationFunction(value) {
        this.aggregationType = value;
    }
}

class Filter {
    constructor(expressionType) {
        this.expressionType = expressionType || Enums.ExpressionType.AND;
        this.filters = {};
    }

    add(name, filter) {
        this.filters[name] = filter;
    }
    addItem(filter) {
        this.add("f" + (Math.random()+"").substr(2), filter);
    }
    
    get(filterKey) {
        return this.filters[filterKey];
    }
}

class IFilterItem {
    static get WithParamType() {return 1; }
    static get NullType() {return 2; }

    constructor(column, type) {
        this.column = column;
        this.type = type;
    }
}
class FilterItemWithParameter extends IFilterItem {
    constructor(column, conditionType, value) {
        super(column, IFilterItem.WithParamType);
        this.conditionType = conditionType;
        this.value = value;
    }
}
class FilterItemNull extends IFilterItem {
    constructor(column, isNull = true) {
        super(column, IFilterItem.NullType);
        this.isNull = isNull;
    }
}


class EntitySchemaQuery {

    constructor(schemaName) {
        this.schemaName = schemaName;
		this.allColumn = false;
        this.columns = [];
        this.Filters = new Filter();
    }
	
	addColumn(columnName, alias = null, config = null) {
		var col = this.createColumn(columnName, alias, config);
		this.columns.push(col);
        return col;
    }
    addAggregationColumn(aggregationType, columnName, alias = null, config = null) {
		var col = this.createAggregationColumn(aggregationType, columnName, alias, config);
        this.columns.push(col);
        return col;
    }
	addArithmeticColumn(columnName1, columnName2, arithmeticOperation, alias = null, config = null) {
		var col = this.createArithmeticColumn(columnName1, columnName2, arithmeticOperation, alias, config);
        this.columns.push(col);
        return col;
    }
	
	createColumn(columnName, alias = null, config = null) {
		var col = this._getColumnByColumnOrName(columnName);
        col.setAlias(alias);
		this._addColumnConfig(col, config);
		return col;
	}
	createAggregationColumn(aggregationType, columnName, alias = null, config = null) {
		var col = this._getColumnByColumnOrName(columnName);
		var aCol = new AggregationColumn(col, alias, aggregationType);
		this._addColumnConfig(aCol, config);
		return aCol;
	}
	createArithmeticColumn(columnName1, columnName2, arithmeticOperation, alias = null, config = null) {
		var col1 = this._getColumnByColumnOrName(columnName1);
		var col2 = this._getColumnByColumnOrName(columnName2);
		var aCol = new ArithmeticColumn(col1, col2, arithmeticOperation, alias);
		this._addColumnConfig(aCol, config);
		return aCol;
	}
	_getColumnByColumnOrName(column) {
		if (typeof(column) == "string") {
			return new Column(column);
		}
		else {
			return column;
		}
	}
	_addColumnConfig(column, config) {
		if (!config) {
			return;
		}
		if (config.hasOwnProperty('orderDirection')) {
			column.orderDirection = config.orderDirection;
		}
		if (config.hasOwnProperty('orderPosition')) {
			column.orderPosition = config.orderPosition;
		}
	}


    createFilterWithParameter(column, conditionType, value) {
		var col = this.createColumn(column);
        return new FilterItemWithParameter(col, conditionType, value);
    }
    createAggregationFilter(aggregationType, column, conditionType, value) {
		var col = this.createAggregationColumn(aggregationType, column);
        return new FilterItemWithParameter(col, conditionType, value);
    }
    createNullFilter(column) {
        var col = this.createColumn(column);
        return new FilterItemNull(col, true);
    }
    createNotNullFilter(column) {
		var col = this.createColumn(column);
        return new FilterItemNull(col, false);
    }
	
    
    createFilterGroup(expressionType) {
        return new Filter(expressionType);
    }

    getEntityCollection(callback, scope) {
        if (!callback || !(callback instanceof Function)) {
            return;
        }
		
		Livlag.callService("DataService", {
			method: "getEntityCollection",
			esq: this
		}, function(data) {
			Livlag.callCallback(callback, data, scope);
        }, this);
    }
    getEntity(recordId, callback, scope) {
        if (!callback || !(callback instanceof Function)) {
            return;
        }
		Livlag.callService("DataService", {
            method: "getEntity",
            recordId: recordId,
			esq: this
		}, function(data) {
			Livlag.callCallback(callback, data, scope);
        }, this);
    }
}



class Query {
	constructor(schemaName) {
        this.schemaName = schemaName;
        this.values = {};
        
    }
	setColumnValue(columnName, value) {
        this.values[columnName] = value;      
    }
	
	Execute(callback, scope) {
		Livlag.callService("DataService", {
            method: "queryExecute",
			esq: this
		}, function(data) {
			Livlag.callCallback(callback, data, scope);
        }, this);
    }
}
class Update extends Query {
    constructor(schemaName) {
        super(schemaName, name);
		this.type = "update";
		this.Filters = new Filter();
    } 
	
	
	createColumn(columnName, alias = null, config = null) {
		var col = this._getColumnByColumnOrName(columnName);
        col.setAlias(alias);
		return col;
	}
	createAggregationColumn(aggregationType, columnName, alias = null, config = null) {
		var col = this._getColumnByColumnOrName(columnName);
		var aCol = new AggregationColumn(col, alias, aggregationType);		return aCol;
	}
	createArithmeticColumn(columnName1, columnName2, arithmeticOperation, alias = null, config = null) {
		var col1 = this._getColumnByColumnOrName(columnName1);
		var col2 = this._getColumnByColumnOrName(columnName2);
		var aCol = new ArithmeticColumn(col1, col2, arithmeticOperation, alias);
		return aCol;
	}
	_getColumnByColumnOrName(column) {
		if (typeof(column) == "string") {
			return new Column(column);
		}
		else {
			return column;
		}
	}

    createFilterWithParameter(column, conditionType, value) {
		var col = this.createColumn(column);
        return new FilterItemWithParameter(col, conditionType, value);
    }
    createAggregationFilter(aggregationType, column, conditionType, value) {
		var col = this.createAggregationColumn(aggregationType, column);
        return new FilterItemWithParameter(col, conditionType, value);
    }
    createNullFilter(column) {
        var col = this.createColumn(column);
        return new FilterItemNull(col, true);
    }
    createNotNullFilter(column) {
		var col = this.createColumn(column);
        return new FilterItemNull(col, false);
    }
	
    
    createFilterGroup(expressionType) {
        return new Filter(expressionType);
    }
}
class Insert extends Query {
	constructor(schemaName) {
        super(schemaName, name);
		this.type = "insert";
    } 
}