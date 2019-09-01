<?php

require_once 'db.php';
session_start();

// text, text_if, data_p

if ($_SESSION['auth'] == true) {
} else {
    die('Не авторизованы');
}

$q = $conn->prepare("INSERT INTO posts (user_id, text, data_w, data_p, text_if, username, SpecialId, isIdSpeceal) 
  VALUES (:user_id, :text, :data_w, :data_p, :text_if, :username, :SpecialId, :isIdSpeceal)");

$q->bindParam(':user_id',$_SESSION['id']);
$q->bindParam('text',$_POST['text']);

$time = strtotime($_POST['data_p']);


if ($_POST['SpecialId']!= null) {

    $query = $conn->prepare('SELECT `id` FROM `posts` WHERE `id` = :id OR `SpecialId` = :id');
    $query->bindParam(':id', str_replace(' ','',$_POST['SpecialId']));
    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if(!$results) {
        $spec = $_POST['SpecialId'];
        $spec = str_replace(' ','',$spec);
        $q->bindParam(':SpecialId', $spec);
        $one = 1;
        $q->bindParam(':isIdSpeceal',$one);
    } else {
        $q->bindParam(':SpecialId', $spec);
        $zero = 0;
        $q->bindParam(':isIdSpeceal', $zero);
    }

} else {
    $q->bindParam(':SpecialId', $spec);
    $zero = 0;
    $q->bindParam(':isIdSpeceal', $zero);
}

$q->bindParam(':data_w',time());
$q->bindParam(':data_p', $time);
$q->bindParam(':text_if', $_POST['text_if']);
$q->bindParam(':username',$_SESSION['username']);
$q->execute();

header("Location: /");


?>