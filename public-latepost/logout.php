<?php
		session_start();
        require_once 'db.php';
        $q =  $conn->prepare("DELETE FROM `logins` WHERE `user_id`= :id AND `user_hash` = :hash");
        $q->bindParam(':id', $_COOKIE['id']);
        $q->bindParam(':hash', $_COOKIE['hash']);
        $q->execute();
		session_destroy(); //разрушаем сессию для пользователя

		//Удаляем куки авторизации путем установления времени их жизни на текущий момент:
		setcookie('hash', '', time()); //удаляем логин
		setcookie('id', '', time()); //удаляем ключ
		header("Location: /");
	
?>