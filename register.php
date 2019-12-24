<?php
session_start();
if (isset($_POST["do_register"])) {
    require_once 'php/SiteHelper.php';
    
    $registerResult = SiteHelper::RegisterUser($_POST);
    if ($registerResult->success) {
        $_SESSION['ErrorMessage'] = "Обліковий запис створено. Підтвердіть електронну адресу, щоб увійти.";
        header( "Location: login.php" );
    }
    $_SESSION['ErrorMessage'] = $registerResult->message;
}
?>

<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
	<title>Регистрация</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
	<script src="js/jquery-3.3.1.min.js"></script>
</head>
<body class="login reg">
	<header class="register-header">
		<div class="dark-fon">
			<?php
				include "navigation.php";
			?>
			
			<div class="container">
				<div class="login-form register-form">
					<h1>Створення облікового запису</h1>
					<form action="register.php" method="post">
						<div class="wrap">
							<div class="col">
								<label for="name">Ім'я'
									<input type="text" id="name" name="name" value="<?php echo @$_POST['name'];  ?>">
								</label>
								<label for="phone">Телефон
									<input type="phone" id="phone" name="phone" value="<?php echo @$_POST['phone'];  ?>">
								</label>
								<label for="e-mail">E-mail
									<input type="e-mail" name="email" id="email" required value="<?php echo @$_POST['email'];  ?>">
								</label>
							</div>
							
							<div class="col">
								<label for="password">Пароль
									<input type="password" id="password" name="password" >
								</label>
								<label for="password-repeat">Підтвердження паролю
									<input type="password" id="password-repeat" name="password-repeat">
								</label>
							</div>
						</div>
						<input type="submit" name="do_register" value="Зареєструватись" id="login-button">
					</form>
				</div>
				
			</div>
		</div>
	</header>
	<main>

	</main>
	<footer>

	</footer>
	<script src="js/main.js" ></script>
</body>
</html>

<?php
	
if(!empty($_SESSION['ErrorMessage'])) {
	echo "<script>alert(\"" . $_SESSION['ErrorMessage'] . "\");</script>";
	$_SESSION['ErrorMessage'] = "";
}
?>