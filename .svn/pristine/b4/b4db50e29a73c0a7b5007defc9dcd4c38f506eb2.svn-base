
class MenuPage {

	static loadModalInfo(dishId, callback) {
		
		var esq = new EntitySchemaQuery("Dish");
		esq.getEntity(dishId, function(data) {
            if (!data.success || !data.entity) {
                return;
            }
            var dish = data.entity.values;
			var id = $('#modal-name').attr('data-dish', dishId);
            Livlag.Set("modal-name", dish.Name);
            Livlag.Set("modal-components", dish.Note);
            Livlag.Set("modal-price", dish.CurrentPrice + "₴");
            Livlag.Set("modal-weight", dish.Volume);
			$('#modal-img')[0].src = "/img/" + (dish.ImageURL ? ("dishes/" + dish.ImageURL) : "pizza.png");
            callback();
        });
    }

    
	static loadDishes(typeId, callback) {
		
		var esq = new EntitySchemaQuery("Dish");

        esq.addColumn("Id");
        esq.addColumn("Name");
        esq.addColumn("TypeId");
        esq.addColumn("CurrentPrice");
        esq.addColumn("Note");
        esq.addColumn("Volume");
        esq.addColumn("ImageURL");
		
		if (typeId) {
			var typeFilt = esq.createFilterGroup(Enums.ExpressionType.OR);
			
			typeFilt.addItem(esq.createFilterWithParameter("TypeId", Enums.ConditionType.Equal, typeId));
			typeFilt.addItem(esq.createFilterWithParameter("TypeId(DishType).DishFatherTypeId", Enums.ConditionType.Equal, typeId));
			
			esq.Filters.add("TypeFilter", typeFilt);
		}
		
        esq.getEntityCollection(function(data) {
			if (!data.success) {
				return;
			}
			$(".menu-content").empty();
			for (var i = 0; i < data.collection.length; i++) {
				var dishItem = data.collection[i].values;
				var html = `<div class="menu-item toggle" data-dish="${dishItem.Id}">
								<img src="${(dishItem.ImageURL ? ("img/dishes/" + dishItem.ImageURL) : "img/pizza.png")}">
								<h4>${dishItem.Name}</h4>
								<div class="price">
									<p>${dishItem.CurrentPrice}₴</p>
									<p>${dishItem.Volume}</p>
								</div>
								<button class="buy">Купити</button>
							</div>`;
				$(".menu-content").append(html);
			}
			if (callback) {
				callback();
			}
		});
	}
	
}



class ShopingCart {
	
    static add(object) {
		ShopingCart.getCollection(function(value) {
			
			if (value.collection[object.id]) {
				return;
			}
			else {
				object.count = 1;
				value.collection[object.id] = object;
			}
			value.sum = +value.sum + object.price;
			Livlag.setSession("ShopCollection", value, function(data) {
				console.log("add" + data.success);
			})
		})
		
    }
	
    static getCollection(callback) {
		if (!callback || !(callback instanceof Function)) {
			return;
		}
		Livlag.getSession("ShopCollection", function(value) {
			callback(value || {
				collection: {},
				sum: 0
			});
		});
    }
	static setCollection(value, callback) {
		Livlag.setSession("ShopCollection", value, callback);
    }
	static clear() {
		Livlag.setSession("ShopCollection", {}, function() {
			
		});
    }
	static delete(objectId) {
		Livlag.getSession("ShopCollection", function(value) {
			if (!value) {
				return;
			}
			value.collection[objectId] = null;
			Livlag.setSession("ShopCollection", value, function(data) {
				console.log("del" + data.success);
			})
		})
	}
	
	static updateCatdItems(callback) {
		ShopingCart.getCollection(function(result) {

			
			var listHTML = '';
			var sum = 0;
			for (var key in result.collection) {
				if (!result.collection[key]) {
					continue;
				}
				var cardItem = new CardItem(result.collection[key]);
				listHTML += cardItem.getHtml();
			}
			listHTML += `<div class="total">
							<p>Сума замовлення: </p>
							<p>${result.sum}$</p>
						</div>`;

			$('.list-to-buy').html(listHTML);
			ShopingCart._addButtonsClick()
			
			Livlag.callCallback(callback);
		});
	}
	
	static _addButtonsClick() {
		$('.buy-item .minus').click(function(e) {
			ShopingCart.changeItemCount($(this).parents('.buy-item').attr('data-cardlist'), false);
		});
		$('.buy-item .plus').click(function(e) {
			ShopingCart.changeItemCount($(this).parents('.buy-item').attr('data-cardlist'), true);
		});
	}
	
	static changeItemCount(id, isPlus) {
		ShopingCart.getCollection(function(result) {
			var oldCount = result.collection[id].count;
			if (isPlus) {
				result.collection[id].count++
				result.sum = +result.sum + +result.collection[id].price;
			}
			else {
				if (oldCount > 0) {
					result.collection[id].count--;
					result.sum = +result.sum - +result.collection[id].price;
				}
			}
			
			if (oldCount !== result.collection[id].count) {
				ShopingCart.setCollection(result, function() {
					new CardItem(result.collection[id]).countUpdate();
					ShopingCart.updateSum(result.sum);
				})
			}
		})
	}
	static updateSum(sum) {
		$('.list-to-buy .total').children('p')[1].innerHTML = sum + '₴';
	}
	
	static orderConfirm(callback, scope) {
		Livlag.callService("SiteService.OrderConfirm", null, function(data) {
            if (!data.success) {
				if (data.ErrorCode == 22) {
					alert("Ввійдіть в систему, або зареєструйтесь!");
					document.location.href = "login.php";
					return;
				}
                alert("Помилка при оформленні замовлення!" + data.ErrorMessage);
            }
			else {
				alert("Замовлення успішно оформлено!");
				ShopingCart.updateCatdItems();
			}
			
			Livlag.callCallback(callback, data, scope);
			
        }, this);
	}
}


class CardItem extends BaseViewModel {
	
	constructor(itemConfig) {
		super(itemConfig);
	}
	
	get schemaName() {
		return "CardItem";
	}
	
	get sum() {
		return this.get("count") * this.get("price");
	}
	getHtml() {
		return `<div class="buy-item" data-cardlist="${this.get("id")}">
					<p><label id="qefgfed">${this.get("name")}</label></p>
					<div class="price-block">
						<div class="num-item-btn">
							<button class="minus">-</button>
							<p><label id="qefgfe">${this.get("count")}</label></p>
							<button class="plus">+</button>
						</div>
						<p class="price"><label id="qefgddfe">${this.get("price")}</label>₴</p>
					</div>
				</div>`;
	}
	countUpdate() {
		$(`.buy-item[data-cardlist=${this.get("id")}] .num-item-btn p`).html(this.get("count"));
	}
}
