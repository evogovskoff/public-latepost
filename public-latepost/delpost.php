<?php
session_start();
if(isset($_GET['postid'])){
     $postid = $_GET['postid'];
 }
if ($_SESSION['auth']==true){
    require_once 'db.php';
    $q =  $conn->prepare("DELETE FROM `posts` WHERE `id`= :id AND `user_id` = :user_id");
    $q->bindParam(':id', $postid);
    $q->bindParam(':user_id', $_SESSION['id']);
    $q->execute();
    header("Location: /");
}
?>