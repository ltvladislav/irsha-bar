class Profile {

	static get Info() {
        return {
            Name: $('#name').val(),
            Email: $('#email').val(),
            Phone: $('#phone').val(),
            Birthday: $('#birth').val()
        }
    }
    static get Passwords() {
        return {
            old: $('#password').val(),
            new: $('#newPassword').val(),
            new2: $('#newSPassword').val()
        }
    }

    static updateData(callback, scope) {
        var info = Profile.Info;
		
		var quer = new Update("User");
		
		quer.setColumnValue('Name', info.Name);
		quer.setColumnValue('Email', info.Email);
		quer.setColumnValue('PhoneNumber', info.Phone);
		quer.setColumnValue('Birdthday', info.Birthday);
		
		quer.Filters.addItem(quer.createFilterWithParameter('Id', Enums.ConditionType.Equal, window.LvgGlobal.UserId));
		
		quer.Execute(function(data) {
            if (!data.success) {
                alert("Помилка при збереженні оновлених даних!" + data.ErrorMessage);
            }
            else {
                alert("Дані успішно збережено!");
            }
            Livlag.callCallback(callback, data.success, scope);
        });
    }

    static updatePassword(callback) {
        var pass = Profile.Passwords;
        if (pass.new !== pass.new2) {
            alert("Паролі не співпадають!");
            return;
        }
		Livlag.callService("AuthorisationService", {
            action: "ChangePassword",
            config: {
                oldPassword: pass.old,
                newPassword: pass.new
            }
        }, function(data) {
            if (!data.success) {
                alert(data.ErrorMessage);
            } else {
                alert("Новий пароль успішно збережено!");
            }
            if (callback) {
                callback(data.success);
            }
        }, this);
    }

    static clearPasswordFields() {
        $('#password').val('');
        $('#newPassword').val('');
        $('#newSPassword').val('');
    }
	
	static setOrderHistory(callback, scope) {
		var hist = $('.history-content').html();
		if (hist) {
			Livlag.callCallback(callback, null, scope);
			return;
        }
        
        var esq = new EntitySchemaQuery("Ord");

        esq.addColumn("Id");
        esq.addColumn("OrdStatusId(OrdStatus).Name", "Status");
        esq.addColumn("Datetime", "Datetime", {
			orderDirection: Enums.OrderDirection.DESC,
			orderPosition: 0
		});
		var sumCol = esq.createArithmeticColumn("[DishInOrd:OrdId].Price", "[DishInOrd:OrdId].Quantity", Enums.ArithmeticOperation.MULTIPLICATION);
        esq.addAggregationColumn(Enums.AggregationFunction.SUM, sumCol, "Sum");
		
        esq.Filters.addItem(esq.createFilterWithParameter("ClientId", Enums.ConditionType.Equal, window.LvgGlobal.UserId));

        esq.getEntityCollection(function(data) {
			if (!data.success) {
				Livlag.callCallback(callback, data.success, scope);
                return;
            }
            data.collection.forEach(function(order) {
				order = order.values;
				var str = `	<div class="history-item" data-order="${order.Id}">
								<div class="history-item-title" data-order="${order.Id}">
									<p class="plus">+</p>
									<p class="">${order.Status}</p>
									<p class="date">${order.Datetime}</p>
									<p class="">${order.Sum === null ? 0 : order.Sum} грн</p>
								</div>
								<div class="history-item-content" data-order="${order.Id}"></div>
							</div>`;
				$('.history-content').append(str);
			});
			Profile.setOrderAction();
			Livlag.callCallback(callback, data.success, scope);
        });
	}
	static setOrderAction() {
		$('body.profile .history-item-title').click(function(e) {
			e.preventDefault();
			var orderId = $(this).attr('data-order');
			var scope = this;
			Profile.setOrderDishes(orderId, function() {
				$(scope).find('.plus').toggleClass('rotate');
				$(scope).next().slideToggle(250);
			});
		});
	}
	static setOrderDishes(orderId, callback, scope) {
		var cont = $('body.profile .history-item-content[data-order="' + orderId + '"]').html();
		if (cont) {
			Livlag.callCallback(callback, null, scope);
			return;
        }
        var esq = new EntitySchemaQuery("DishInOrd");
        esq.addColumn("Id");
        esq.addColumn("DishId(Dish).Name");
        esq.addColumn("Quantity");
        esq.addColumn("Price");
        esq.Filters.addItem(esq.createFilterWithParameter("OrdId", Enums.ConditionType.Equal, orderId));
        esq.getEntityCollection(function(data) {
			if (!data.success) {
				Livlag.callCallback(callback, data.success, scope);
                return;
            }
            data.collection.forEach(function(item) {
				var dish = item.values;
				
				
				var str = `	<div class="content-item" data-order-dish="${dish.Id}">
								<p>${dish.Name}</p>
								<p>${dish.Quantity} x ${dish.Price} грн</p>
							</div>`;
				$(`body.profile .history-item-content[data-order="${orderId}"]`).append(str);
			});
			Livlag.callCallback(callback, data.success, scope);
        });
		
	}
	static exit() {
		Livlag.callService("AuthorisationService", {
            action: "Exit"
        }, function(data) {
            document.location.href = "index.php";
        }, this);
	}

}