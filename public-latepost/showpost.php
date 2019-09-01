<?php

require_once 'db.php';
session_start();

if(isset($_GET['postid'])){
    $postid = $_GET['postid'];
}

if(isset($_POST['postid'])){
    $postid= $_POST['postid'];
}

$q =  $conn->prepare("SELECT * FROM `posts` WHERE (`id`= :id AND `isIdSpeceal`= 0) OR (`SpecialId` = :id AND `isIdSpeceal`=1)");
$q->bindParam(':id', $postid);
$q->execute();
$post = $q->fetch(PDO::FETCH_ASSOC);

if($post['data_p']<time()) {
    $text = $post['text'];
    $text_if = $post['text_if'];
    $time = $post['data_p'];
    $date = date('d.m.Y ', $time);
    $min = date('   H:i', $time);
    $time = "Опубликовано $date в $min";
} else {
    $text_if = $post['text_if'];
    $time = "Неопубликовано";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Пост с id <?php echo "$postid"?></title>
    <link rel="shortcut icon" href ="/favicon.ico" type="image/x-icon">
    <link rel="icon" href ="/favicon.ico" type= "image/x-icon">

    <script type="text/javascript" src="jquery-3.0.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.min.css">
    <meta charset="utf-8">
    <script type="text/javascript" src="jquery.datetimepicker.full.js"></script>
</head>
<body>
    <div class="search">
        <div class="form">
            <form action="showpost.php" method="POST">
                <p id="searchP">Поиск поста</p>
                <input id="searcher" type="text" name="postid" placeholder="Введите id поста">
                <input id="searchB" type="submit" name="submit" value="Поиск">
            </form>
        </div>
    </div>

    <?php if ($_SESSION['auth']==true) {  ?>
    <div class="post-page">
        <div class="form">
            <form action="post.php" method="POST">
                <input type="text" class="js-elasticArea" name="SpecialId" placeholder="ID поста(необязательно)">
                <textarea class="js-elasticArea"   name="text" placeholder="Пост"></textarea>
                <script type="text/javascript" src="js-elasticArea.js"></script>
                <textarea class="js-elasticArea"   name="text_if" placeholder="Условие"></textarea>
                <script type="text/javascript" src="js-elasticArea.js"></script>

                <input type="text" autocomplete="off"  name="data_p" placeholder="Время и дата" id="date">
                <script>
                    $("#date").datetimepicker({
                        globalLocale:'ru',
                        format: 'Y-m-d H:i',
                    });
                </script>
                <input id="button" type="submit" name="submit" value="Публикация">
            </form>
        </div>  
    </div>
    <?php } ?>

    <div class="card">
        <div id="card-content">
            <span class="card-title">
                <?php echo "$time"?>
            </span>
            <?php if ($post['data_p']<time()) { ?>
            <p class="card-p"><?php echo "$text"?></p>
            <?php } ?>
            <p class="card-p"><?php echo "$text_if"?></p>
            <a class="card-time"><?php
                $time = $post['data_w'];
                echo date('d.m.Y ', $time);
                echo date('   H:i', $time); ?></a>
            <a class="card-button" href="/">На главную</a>
        </div>
    </div>
</body>
</html>