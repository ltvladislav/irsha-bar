<?php
session_start();
if (isset($_POST["do_forgot"])) {
	require_once 'php/SiteHelper.php';
    
    $registerResult = SiteHelper::ForgotPassword($_POST);
    echo json_encode($registerResult);
    if ($registerResult->success) {
        $_SESSION['ErrorMessage'] = "Дані для входу надіслано на вказану адресу.";
        header( "Location: login.php" );
    }
    $_SESSION['ErrorMessage'] = $registerResult->message;
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
			include "contacts.php";
			?>
			<div class="container">
				<div class="login-form">
					<h1>Відновлення паролю</h1>
					<form action="forgotPassword.php" method="post">
						<label for="login">Email, для відновлення
							<input type="text" id="email" name="email" value="<?php echo @$_POST['email'];  ?>">
						</label>
						<input type="submit" name="do_forgot" value="Відновити пароль" id="login-button">
					</form>
					<div class="forgot">
						<p><a href="login.php">Повернутися до входу.</a></p>
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
