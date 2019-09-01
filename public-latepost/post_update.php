<?php

session_start();
require_once 'db.php';
if(isset($_GET['postid'])){
    $postid = $_GET['postid'];
}

if (isset($_POST['submit'])) {
    if(isset($_POST['text'])){
        $text = $_POST['text'];
    }
    if(isset($_POST['text_if'])){
        $text_if = $_POST['text_if'];
    }
    if(isset($_POST['data_p'])){
        $time = strtotime($_POST['data_p']);
    }
        if ($_SESSION['auth']==true){
            require_once 'db.php';
            $q =  $conn->prepare("UPDATE `posts` SET `text`=:text, `text_if`=:text_if, `data_p`=:data_p, `data_w`=:data_w  WHERE `id`= :id AND `user_id` = :user_id AND `isPublic` = 0");
            $q->bindParam(':id', $postid);
            $q->bindParam(':user_id', $_SESSION['id']);
            $q->bindParam(':text', $text);
            $q->bindParam('text_if',$text_if);
            $q->bindParam('data_p', $time);
            $q->bindParam('data_w', time());
            $q->execute();
        }
        header("Location: /");
}
?>

