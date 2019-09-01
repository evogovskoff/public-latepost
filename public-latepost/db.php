<?php
	$server = 'localhost';
	$username = 'root';
	$password = '';
	$database = 'sign.ru_db';

	//$username = 'host1656325';
	//$password = '2nW7bUQH';
	//$database = 'host1656325';

	try{
		$conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
	} catch(PDOException $e) {
		die ("Соединение не удалось" . $e->getMessage());
	}