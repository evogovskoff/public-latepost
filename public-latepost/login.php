<?php
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

if(isset($_POST['submit'])) {
       if (!empty($_POST['username']) && !empty($_POST['password'])) {
            
            $query = $conn->prepare('SELECT * FROM `users` WHERE `username` = :username');
            $query->bindParam(':username', $_POST['username']);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_ASSOC);

            $password = $_POST['password'].$results['salt'];

            if (count($results) > 0 and password_verify($password, $results['password'])){

                $hash = md5(generateCode(10));

                $q = $conn->prepare("INSERT INTO `logins` (user_id, username, user_hash, time) VALUES (:id, :username, :hash, :time)");
                $q->bindParam(':hash', $hash);
                $q->bindParam(':username', $results['username']);
                $q->bindParam(':id', $results['id']);
                $q->bindParam(':time', time());
                $q->execute();

                session_start();
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $results['id'];
                $_SESSION['hash'] = $hash;
                $_SESSION['username'] = $results['username'];

                setcookie("hash", $hash,  time()+3600*24*30);
                setcookie("id", $results['id'], time()+3600*24*30);
            }   
        }
 	}
header("Location: /");
?>