<?php
session_start();
$_SESSION['pageName'] = "menu";
?>
<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<title>Programming</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
	
</head>
<body class="menu">
	<header>
		<div class="dark-fon">
			<?php 
			include "navigation.php";
			?>
			<div class="container main-container">
				<div class="main-info">
					<h1>TECHNO CAFE</h1>
					<p>Меню</p>
				</div>
			</div>
		</div>
	</header>
	<main>
		<section class="menu-main">
			<div class="menu-bar">
				<?php 
				require_once 'php/MenuHelper.php';
				$types = MenuHelper::GetAllDishTypes();
				for ($i = 0; $i < count($types); $i++) {
					$typeHtml = "<div class=\"menu-list-item";
					$typeHtml .= isset($types[$i]["ChildTypes"]) ? " multiply" : "";
					$typeHtml .= "\"><a href=\"#\"";
					$typeHtml .= " data-category=\"" . $types[$i]["Id"] . "\"";
					$typeHtml .= ">";
					$typeHtml .= $types[$i]["Name"];
					$typeHtml .= "</a>";
					
					if (isset($types[$i]["ChildTypes"])) {
						$typeHtml .= "<ul class=\"menu-list-content\" data-category=\"" . $types[$i]["Id"] . "\">";
						
						for ($j = 0; $j < count($types[$i]["ChildTypes"]); $j++) {
							$typeHtml .= "<li><a href=\"#\"";
							$typeHtml .= " data-category=\"" . $types[$i]["ChildTypes"][$j]["Id"] . "\"";
							$typeHtml .= ">";
							$typeHtml .= $types[$i]["ChildTypes"][$j]["Name"];
							$typeHtml .= "</a></li>";
						}
						
						$typeHtml .= "</ul>";
					}
					
					$typeHtml .= "</div>";
					echo $typeHtml;
				}
				?>
			</div>
			<div class="menu-content">
				<!--<div class="menu-item toggle">
					<img src="img/pizza.png" alt="">
					<h4>Піца Лос-Анджелес</h4>
					<div class="price">
						<p>5$</p>
						<p>120g</p>
					</div>
					<button class="buy">Купити</button>
				</div>-->
			</div>
		</section>
	</main>
		
	
	<?php 
		include "footer.php";
	?>
	<!-- Modal for menu item -->
	<div class="modal">
		<div class="modal-overlay toggle"> </div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-content ">
				<div class="title">
					<p id="modal-name">Піца Лос Анджелес</p>
					<button class="toggle modal-close ">X</button>
				</div>
				<div class="modal-form">
					<img src="img/pizza.png" alt="" id="modal-img">

					<p id="modal-components">Склад: Курка, гриби, помідори, пармезан, соус, зелень</p>
					<div class="price">
						<p id="modal-price">Ціна: 5$</p>
						<p id="modal-weight">Вага: 240г</p>
					</div>
					<button class="add-to-cart" id="modal-add-to-cart">Додати до корзини</button>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal for cart -->
	<div class="modal-cart">
		<div class="modal-overlay toggle-cart"> </div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-content ">
				<div class="title">
					<p>Ваше замовлення:</p>
					<button class="toggle-cart modal-close ">X</button>
				</div>
				<div class="modal-form">

					<div class="list-to-buy">
						
						<div class="buy-item">
							<p>Pizza some</p>
							<div class="price-block">
								<div class="num-item-btn">
									<button>-</button>
									<p>1</p>
									<button class="last">+</button>
								</div>
								<p class="price">5$</p>
							</div>
						</div>
					
						<div class="total">
							<p>Сума замовлення: </p>
							<p>15$</p>
						</div>
					
					</div>
					<button class="add-to-cart" id="order-confirm">Підтвердити замовлення</button>
				</div>
			</div>
		</div>
	</div>

	<script src="js/main.js" ></script>
	<script>
		downloadScript("MenuPage");
		
		
		window.addEventListener("load", function(){
			downloadScript("constants.js", function() {
				$('.menu-list-item a[data-category="' + Constants.DishType.MainType + '"]').click();
			});
			
			
		});
	</script>
</body>
</html>

<?php
session_start();
	
if(!empty($_SESSION['ErrorMessage'])) {
	echo "<script>alert(\"" . $_SESSION['ErrorMessage'] . "\");</script>";
	$_SESSION['ErrorMessage'] = "";
}
?>