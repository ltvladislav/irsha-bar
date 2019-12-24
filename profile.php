<?php
session_start();
if (!$_SESSION["isLogin"]) {
	header( "Location: login" );
}


$id = $_SESSION["userId"];
require_once 'php/ESQ.php';


$esq = new EntitySchemaQuery("User");
$esq->AddColumn("Id");
$esq->AddColumn("Name");
$esq->AddColumn("PhoneNumber");
$esq->AddColumn("Email");
$esq->AddColumn("Birdthday");

$user = $esq->GetEntity($id);

if ($user == null) {
    echo "<script>alert('Облікового запису не знайдено');</script>";
}
else {
    $info = array();
    $info["Name"] = $user->GetColumnValue("Name");
	$info["Email"] = $user->GetColumnValue("Email");
	$info["PhoneNumber"] = $user->GetColumnValue("PhoneNumber");
	$info["Birdthday"] = $user->GetColumnValue("Birdthday");
}


?>
<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<title>Programming</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
	
</head>
<body class="profile">
	<header>
		<div class="dark-fon">
			<?php 
			include "navigation.php";
			?>
			<div class="container main-container">
				<div class="main-info">
					<h1>TECHNO CAFE</h1>
					<p>Персональний кабінет</p>
				</div>
			</div>
		</div>
	</header>
	<main>
		<section class="menu-main profile">
			<div class="menu-bar profile">
				<div class="menu-list-item profile active" data-name="personalInfo">
					<a href="#" >Особиста інформація</a>
				</div>
				<div class="menu-list-item profile" data-name="purchaseHistory">
					<a href="#">Історія покупок</a>
				</div>
				<div class="menu-list-item profile" data-name="privacy">
					<a href="#">Приватність</a>
				</div>
				<?php
				require_once 'php/constants.php';
				
				if ($_SESSION["userId"] == "0efe06fa-76fb-11e9-9ff9-faf15d0c4514") {
					print '<div class="menu-list-item profile" data-name="toBPM"><a href="http://v-lytvynchuk/TRAINING_SE7134_VLYTVYNCHUK" target="_blank">Адміністрування</a></div>';
				}
				?>
				
				<div class="menu-list-item profile" data-name="exit">
					<a href="#">Вийти</a>
				</div>
				
			</div>
			<div class="menu-content profile">
				<div class="personal-card card visible" data-name="personalInfo">
					<div class="title">
						<h3>Особиста інформація</h3>
						<button class="changePersonalInfo">Змінити</button>
						<button class="savePersonalInfo hide">Зберегти</button>
					</div>
					<div class="card-content">
						<label for="name">Ім'я
							<input type="text" value="<?php echo @$info["Name"];  ?>"  id="name" readonly="readonly">
						</label>
						<label for="email">E-mail
							<input type="email" value="<?php echo @$info["Email"];  ?>" id="email" readonly="readonly">
						</label>
						<label for="phone">Телефон
							<input type="tel" id="phone" value="<?php echo @$info["PhoneNumber"];  ?>" readonly="readonly">
						</label>
						<label for="birth">Дата народження
							<input type="date" id="birth" value="<?php echo @$info["Birdthday"];  ?>" readonly="readonly">
						</label>
					</div>
				</div>

				<div class="personal-card card " data-name="privacy">
					<div class="title">
						<h3>Приватність</h3>
						<button class="changePersonalPassw">Зберегти</button>
					</div>
					<div class="card-content">
						<label for="password">Старий пароль
							<input type="password"   id="password" >
						</label>
						<label for="newPassword">Новий пароль
							<input type="password"   id="newPassword" >
						</label>
						<label for="newSPassword" class="passwConfirm">Підтвердження паролю
							<input type="password"   id="newSPassword" >
						</label>
					</div>
				</div>

				<div class="history-card card" data-name="purchaseHistory">
					<div class="title">
						<h3>Історія покупок</h3>
					</div>
					<div class="history-content"></div>
					
				</div>
			</div>
		</section>
	</main>
		
	
	<?php 
		include "footer.php";
	?>
	
	<script src="js/main.js" ></script>
	<script src="js/Profile.js" ></script>
</body>
</html>

<?php
session_start();
	
if(!empty($_SESSION['ErrorMessage'])) {
	echo "<script>alert(\"" . $_SESSION['ErrorMessage'] . "\");</script>";
	$_SESSION['ErrorMessage'] = "";
}
?>