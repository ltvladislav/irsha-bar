function checkDownloadScript(url)
{
	var scripts = document.head.getElementsByTagName("script");
	for (let i = 0; i < scripts.length; i++) {
		if (scripts[i].src.replace(window.location.origin + "/js/", "") == url) {
			return true;
		}
	}
}


function downloadScript(path, callback) {
	
	path = (path.indexOf(".js") === -1) ?(path + ".js") : path;
	if (checkDownloadScript(path)) {
		return;
	}
	path = "js/" + path;

    var done = false;
    var scr = document.createElement('script');

    scr.onload = handleLoad;
    scr.onreadystatechange = handleReadyStateChange;
    scr.onerror = handleError;
    scr.src = path;
    document.body.appendChild(scr);

    function handleLoad() {
        if (!done) {
            done = true;
			console.log(`'${path}' is loaded`);
            if (callback) {
				callback(true);
			}
        }
    }

    function handleReadyStateChange() {
        var state;

        if (!done) {
            state = scr.readyState;
            if (state === "complete") {
                handleLoad();
            }
        }
    }
    function handleError() {
        if (!done) {
            done = true;
			console.log(`Error with '${path}' loaded`);
			if (callback) {
				callback(false);
			}
        }
    }
}


downloadScript("jquery-3.3.1.js");
downloadScript("Livlag");
downloadScript("EntitySchemaQuery");
downloadScript("BaseViewModel");


window.addEventListener("load", function(){
	
	if (window.location.href.includes('kl.com.ua')) {
		document.body.children[0].style.display = 'none';
		$(".cbalink").hide();
	}
	setIdInGlobal();
	
	
	$('nav a[href^="#"]').bind("click", function(e){
			var anchor = $(this);
			if($(this).attr('href') != '#cart') {
					console.log(anchor)
				$('html, body').stop().animate({
						scrollTop: $(anchor.attr('href')).offset().top - +70
				}, 1000);
				$('header nav a').removeClass('active');
				$(this).addClass('active');
				e.preventDefault();
			} else {
				
			}
			
	});
	addActions();
});
  	

function addActionsOnDish() {
	$('.toggle').off("click");
	$('.toggle').click(function(e) {
		e.preventDefault();
		
		if (window.isBuyButton) {
			window.isBuyButton = false;
			return;
		}

		var id = $(this).attr('data-dish');
		if (id) {
			MenuPage.loadModalInfo(id, function() {
				$('.modal').toggleClass('is-visible');
			})
			return;
		}
		$('.modal').toggleClass('is-visible');
	})
	
	$('.buy').click(function(e) {
		e.preventDefault();
		
		window.isBuyButton = true;

		var blockDiv = $(this).parent('.menu-item');
		
		var id = blockDiv.attr('data-dish');
		var name = blockDiv.children("h4")[0].innerHTML ;
		var price = blockDiv.children(".price").children("p")[0].innerHTML ;
		
		price = Number.parseFloat(price);
		var dish = {
			id: id,
			name: name,
			price: price
		}

		ShopingCart.add(dish);
	})
}


function addActions() {
	$("header .nav-container").removeClass("fixed");

	$(window).scroll(function() {
		if ($(this).scrollTop() > 60 || window.location.pathname == "/login.php"){
			$("header .nav-container").addClass("fixed").fadeIn("fast");
		} 
		else {
			$("header .nav-container").removeClass("fixed").fadeIn("fast");
		};
	});
	
	$('.menu-list-item.multiply a').click(function(e) {
		e.preventDefault();
		var category = $(this).attr('data-category');
		$('.menu-list-content[data-category='+category+']').slideToggle(250);
	})
	
	$(' .menu-list-item').click(function(e) {
		if (window.LvgGlobal && window.LvgGlobal.IsBPM) {
			if (!window.LvgGlobal) {
				window.LvgGlobal = {};
			}
			window.LvgGlobal.IsBPM = false;
			return;
		}
		$('.menu-list-item').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	})
	$('body.menu .menu-list-item a').click(function(e) {
		e.preventDefault();
		var category = $(this).attr('data-category');
		
		MenuPage.loadDishes(category, addActionsOnDish);
	})
	
	
	$('.toggle-cart').click(function(e) {
		console.log("Lick");
		e.preventDefault();
		if ($('.modal-cart').hasClass('is-visible')) {
			$('.modal-cart').toggleClass('is-visible');
		}
		else {
			ShopingCart.updateCatdItems(function() {
				$('.modal-cart').toggleClass('is-visible');
			});
		}
	});
	
	
	$('body.profile .menu-list-item.profile a').click(function(e) {
		e.preventDefault();
		var name = $(this).parent().attr('data-name');
		if (name == "purchaseHistory") {
			Profile.setOrderHistory(function() {
				$('body.profile .menu-content.profile .card').removeClass('visible');
				$('body.profile .menu-content.profile .card[data-name='+name+']').addClass('visible');
			});
			return;
		}
		if (name == "exit") {
			Profile.exit()
			return;
		}
		if (name == "toBPM") {
			window.open('http://v-lytvynchuk/TRAINING_SE7134_VLYTVYNCHUK');
			name = "personalInfo";
			if (!window.LvgGlobal) {
				window.LvgGlobal = {};
			}
			window.LvgGlobal.IsBPM = true;
			$('.menu-list-item').removeClass('active');
			$(' .menu-list-item[data-name=personalInfo]').addClass('active');
		}
		
		$('body.profile .menu-content.profile .card').removeClass('visible');
		$('body.profile .menu-content.profile .card[data-name='+name+']').addClass('visible');
		
	});
	
	
	$('body.profile .card[data-name="personalInfo"] .changePersonalInfo').click(function() {
		$('.profile .card[data-name="personalInfo"] .card-content input').removeAttr('readonly')
		$(this).toggleClass('hide');
		$(this).next().toggleClass('hide')
	})

	$('body.profile .card[data-name="personalInfo"]  .savePersonalInfo').click(function() {
		var scope = this;
		Profile.updateData(function(isSaved) {
			if (isSaved) {
				$('.profile  .card[data-name="personalInfo"] .card-content input').attr('readonly', 'readonly')
				$(scope).toggleClass('hide');
				$(scope).prev().toggleClass('hide')
			}
		});
	});
	$('body.profile .changePersonalPassw').click(function() {
		Profile.updatePassword();
	});
	
	
	

	$('#modal-add-to-cart').click(function() {
		
		var id = $('#modal-name').attr('data-dish');
		var name = Livlag.Get("modal-name");
		var price = Livlag.Get("modal-price");
		price = Number.parseFloat(price);
	
		var dish = {
			id: id,
			name: name,
			price: price
		}
		
		ShopingCart.add(dish);
	});
	
	$('#order-confirm').click(function() {
		ShopingCart.orderConfirm();
	});
}






function setIdInGlobal() {
	Livlag.getSession("userId", function(data) {
		if (!window.LvgGlobal) {
			window.LvgGlobal = {};
		}
		window.LvgGlobal.UserId = data;
	})
}




