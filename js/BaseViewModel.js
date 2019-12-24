class BaseViewModel {
	constructor(config = {}) {
		this.values = config;
		this.dependency=[];
		for(let key in this.values) {
			this._fireChangePage(key);
		}
	}
	
	get(key) {
		return this.values[key] || this._getDomIdByKey(key);
	}
	set(key, value) {
		this.values[key] = value;
		this._fireChangePage(key);
		this._fireChange(key);
	}
	_getDomIdByKey(key) {
		return (this.objectId || (this.schemaName + this.values.Id)) + key;
	}
	
	_fireChangePage(key) {
		let el = document.getElementById(this._getDomIdByKey(key));
		if (el) {
			el.innerHTML = this.get(key);
		}
	}
	
	get schemaName() {
		return "BaseViewModel";
	}
	
	addDependecsy(keys, method) {
		var depend = {
			columns: keys instanceof Array ? keys : [keys]
		};
		if (method instanceof Function) {
			depend.method = method;
		}
		if (method + "" === method) {
			depend.methodName = method;
		}
		
	}
	
	_fireChange(key) {
		this.dependency.forEach(function(depend) {
			if (depend.columns.includes(key)) {
				if (depend.methodName && this[methodName] && this[methodName] instanceof Function) {
					this[methodName]();
				}
				if (depend.method && depend.method instanceof Function) {
					depend.method();
				}
			}
		});
	}
}
