<?php
    $err = 0;
	function generateCode($length=6) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";

    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];  
    }

    return $code;
}

	require 'db.php';

	if (!empty($_POST['username']) and !empty($_POST['password']) and !empty($_POST['email'])){

		$query = $conn->prepare('SELECT `id` FROM `users` WHERE `username` = :username');
	    $query->bindParam(':username', $_POST['username']);
	    $query->execute();
	    $results = $query->fetch(PDO::FETCH_ASSOC);

	    if ($results) {
	        $message = 'Логин занят';

	    } else {

	    	$salt = generateCode(10);
	    	$password = $_POST['password'].$salt;

			$sql = "INSERT INTO users (username, password, email, salt) VALUES (:username, :password, :email, :salt)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':username', $_POST['username']);			
			$stmt->bindParam(':salt', $salt);
			$stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
			$stmt->bindParam(':email', $_POST['email']);

			$stmt->execute();
			header("Location: /");
		}
	} else { $message = 'Вы ввели не всё'; }?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Регистрация</title>
			<link rel="stylesheet" type="text/css" href="styles.css">
			<meta charset="utf-8">
            <link rel="shortcut icon" href ="/favicon.ico" type="image/x-icon">
            <link rel="icon" href ="/favicon.ico" type= "image/x-icon">
		</head>
		<body>
			<div>
	<div class="login-page">
		<div class="form">
			<form action="login.php" method="POST" class="login-form">
				<input id="input" type="text" name="username" placeholder="Логин"><br>
				<input id="input" type="password" name="password" placeholder="Пароль"><br>
				<input id="button" type="submit" name="submit" value="Войти">
			</form>
		</div>
	</div><br>

	<div class="reg-page">
		<div class="form">
			<form action="registration.php" method="POST">
				<input type="text" name="username" placeholder="Логин">
				<input type="password" name="password" placeholder="Пароль">
				<input type="text" name="email" placeholder="Почта">
				<input id="button" type="submit" name="submit" value="Регитрация">
				<p><?php echo "$message" ?></p>
			</form>
		</div>	
	</div>
	</div>

		<div class="Logo">
			<p id="logo">LatePost</p>
		</div>	

		</body>
		</html>
