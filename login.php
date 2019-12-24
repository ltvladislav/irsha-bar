<?php

session_start();

if (isset($_POST["do_login"])) {
    require_once 'php/SiteHelper.php';
    
    $registerResult = SiteHelper::LoginUser($_POST);
    if ($registerResult->success) {
        $_SESSION['ErrorMessage'] = "Обліковий запис створено. Підтвердіть електронну адресу, щоб увійти.";
        header( "Location: login.php" );
    }
    $_SESSION['ErrorMessage'] = $registerResult->message;
    
}



if ($_SESSION["isLogin"]) {
	header( "Location: profile.php" );
}

?>

<!DOCTYPE html>
<html lang="ua">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Prata" rel="stylesheet">
</head>
<body class="login">
	<header class="login-header">
		<div class="dark-fon">
			<?php 
			include "navigation.php";
			?>
			<div class="container">
				<div class="login-form">
					<h1>Логінізація</h1>
					<form action="login.php" method="post">
						<label for="login">Логін
							<input type="text" id="email" name="email" value="<?php echo @$_POST['email'];  ?>">
						</label>
						<label for="password">Пароль
							<input type="password" id="password" name="password" >
						</label>
						<input type="submit" name="do_login" value="Увійти" id="login-button">
					</form>
					<div class="forgot">
						<p><a href="forgotPassword.php">Забули пароль?</a></p>
						<p>Не маєте облікового запису? <a href="register.php">Зареєструйся</a></p>
					</div>
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