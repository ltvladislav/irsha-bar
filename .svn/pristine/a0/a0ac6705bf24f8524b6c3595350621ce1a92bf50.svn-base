class Livlag {
	static getHTMLTable(data) {
		if (data.length == 0) {
			return;
		}
		var output = '<table>';
		output += '<tr>';
		for (key in data[0]) {
			output += '<td><strong>' + key + '</strong></td>';
		}
		output += '</tr>';
		for (var i = 0; i < data.length; i++) {
			output += '<tr>';
			for (key in data[0]) {
				output += '<td><strong>' + data[0][key] + '</strong></td>';
			}
			output += '</tr>';
		}
		output += '</table>';
		return output;
	}
	
	static getSession(key, callback, scope) {
		Livlag.callService("sessionService", {
			method: "get",
            key: key
		}, function(data) {
			Livlag.callCallback(callback, data.value, scope);
		});
	}
	static setSession(key, value, callback, scope) {
		Livlag.callService("sessionService", {
			method: "set",
            key: key,
			value: value
		}, callback, scope);
	}
	
	static Set(key, value) {
        $('#' + key).text(value);
    }
    static Get(key) {
        return $('#' + key).text();
    }
	
	static callCallback(callback, args, scope) {
		if (!callback) {
			return;
		}
		if (!args) {
			if (scope) {
				callback.call(scope);
			}
			else {
				callback();
			}
			return;
		}
		if (args instanceof Array) {
			if (scope) {
				callback.apply(scope, args);
			}
			else {
				callback.apply(null, args);
			}
			return;
		}
		if (scope) {
			callback.call(scope, args);
		}
		else {
			callback(args);
		}
		
	}
	
	static callService(serviceName, args, callback, scope) {
		args = args || {};
		if (serviceName.includes(".") && !args.hasOwnProperty('action')) {
			[serviceName, args.action] = serviceName.split(".");
		}
		$.post(`../php/${serviceName}.php`, args, function(result) {
			Livlag.callCallback(callback, result, scope);
		})
	}
	
	static get sandbox() {
		if (!window.lvgsandbow) {
			window.lvgsandbow = new Sandbox();
		}
		return window.lvgsandbow;
	}
}

class Sandbox {
	constructor() {
		this.subscribers = {};
	}
	
	publish(msg, object) {
		if (!this.subscribers[msg]) {
			return;
		}
		if (this.subscribers[msg].length == 1) {
			let subscr = this.subscribers[msg][0];
			return Livlag.callCallback(subscr.callback, object, subscr.scope);
		}
		this.subscribers[msg].forEach(function(subscr) {
			Livlag.callCallback(subscr.callback, object, subscr.scope);
		});
	}
	subscribe(msg, callback, scope) {
		if (!this.subscribers[msg]) {
			this.subscribers[msg] = [];
		}
		this.subscribers[msg].push({
			callback: callback,
			scope: scope
		});
	}
}







