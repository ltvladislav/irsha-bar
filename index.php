<?php 
	session_start();
?>
<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<title>IRSHA BAR</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
</head>
<body>
	<header>
		<div class="dark-fon">
			<?php 
				include "navigation.php";
			?>
			<div class="container main-container">
				<div class="main-info">
					<h1>IRSHA BAR</h1>
					<p>НЕ ЗНАЄШ ДЕ ВІДПОЧИТИ, ТА ЯК ГАРНО ПРОВЕСТИ ЧАС? ТОДІ ТОБІ ДО НАС</p>
					<div class="main-buttons">
						<a href="#about" class="first-main-button">Дізнатись більше</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<main>
		<section class="about" id="about">
			<h2>Вітаємо в нашому кафе</h2>
			<p>Гарний заклад, де можна просто відпочити та поспілкуватись з друзями, або ж відірватись на повну. Посидіти попити сочку, чи спробувати унікальних коктейлів від наших барменів. Скуштувати найсмачнішу піццу, або просто погризти чіпсів.</p>
			<a class="button" href="menu">Переглянути меню</a>
		</section>
		<section class="techno-part">
			<div class="tecno-description">
				<div class="tecno-text">
					<h3>Чому ми особливі?</h3>
					<p>Ми не особливі, але у нас класно. Це місце де можна розслабитись та відпочити від сірих буднів. А наша атмосфера зроблять ваш настрій та залишать незабутні спогади на довго.</p>
				</div>
				<div class="tecno-photo">
					
				</div>
			</div>
			<div class="tecno-images">
				<div class="img-item"></div>
				<div class="img-item"></div>
				<div class="img-item"></div>
			</div>
		</section>
		
	</main>
	<?php 
		include "footer.php";
	?>
	
    <script src="js/main.js" ></script>
</body>
</html>

<?php
session_start();
	
if(!empty($_SESSION['ErrorMessage'])) {
	echo "<script>alert(\"" . $_SESSION['ErrorMessage'] . "\");</script>";
	$_SESSION['ErrorMessage'] = "";
}
?>