<?php

session_start();
require_once 'db.php';
if(isset($_GET['postid'])){
    $postid = $_GET['postid'];
}

if ($_SESSION['auth']==true){
    require_once 'db.php';
    $q =  $conn->prepare("SELECT * FROM `posts` WHERE `id`= :id AND `user_id` = :user_id");
    $q->bindParam(':id', $postid);
    $q->bindParam(':user_id', $_SESSION['id']);
    $q->execute();
    $post = $q->fetch(PDO::FETCH_ASSOC);
}



if (count($post)!=0) {
    ?>

<!DOCTYPE html>
    <html>
    <head>
        <title>Авторизация</title>
        <script type="text/javascript" src="jquery-3.0.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.min.css">
        <meta charset="utf-8">
        <script type="text/javascript" src="jquery.datetimepicker.full.js"></script>
        <link rel="shortcut icon" href ="/favicon.ico" type="image/x-icon">
        <link rel="icon" href ="/favicon.ico" type= "image/x-icon">
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

    <div class="post-page">
        <div class="form">
            <form action=<?php echo "post_update.php?postid=$postid"?>" method="POST">
                <textarea class="js-elasticArea"   name="text" placeholder="Пост"><?php $text = $post['text']; echo "$text"?></textarea>
                <script type="text/javascript" src="js-elasticArea.js"></script>
                <textarea class="js-elasticArea"   name="text_if" placeholder="Условие"><?php $text = $post['text_if']; echo "$text"?></textarea>
                <script type="text/javascript" src="js-elasticArea.js"></script>
                <input type="text" autocomplete="off"  name="data_p" placeholder="Время и дата" id="date" value="<?php $text = $post['data_p']; echo date("Y-m-d h:i", $text)?>">
                <script>
                    $("#date").datetimepicker({
                        globalLocale:'ru',
                        format: 'Y-m-d H:i',
                    });
                </script>
                <input id="button" type="submit" name="submit" value="Публикация">
                <a id="back" href="/">Назад на главную</a>
            </form>
        </div>

        <?php
        for ($i=0; $i<count($posts); $i++){
            ?>
            <div class="card">
                <div id="card-content">
                            <span class="card-title">
                                <?php
                                if ($posts[$i][isPublic]==0) {
                                    echo "Опубликуется   ";
                                    $time = $posts[$i]['data_p'];
                                    echo date('d.m.Y ', $time);
                                    echo ' в ';
                                    echo date('   H:i', $time);
                                } else {
                                    echo "Опубликовано   ";
                                    $time = $posts[$i]['data_p'];
                                    echo date('d.m.Y ', $time);
                                    echo ' в ';
                                    echo date('   H:i', $time); }
                                ?></span>
                    <p class="card-p"><?php $cont=$posts[$i]['text']; echo "$cont"?></p>
                    <p class="card-p"><?php $cont=$posts[$i]['text_if']; echo "$cont"?></p>
                </div>
                <div class="card-action">
                    <a class="ref" href=<?php
                    if ($posts[$i]['isIdSpeceal']==0){
                        $adr="showpost.php?postid=".$posts[$i]['id'];
                    } else {
                        $adr="showpost.php?postid=".$posts[$i]['SpecialId'];
                    }
                    echo "$adr"?>><?php echo "www.latepost.gq/".$adr?></a><br>
                    <a class="card-button" href=<?php $adr="delpost.php?postid=".$posts[$i]['id']; echo "$adr"?>>Удалить</a>
                    <a class="card-button" href=<?php $adr="postupdate.php?postid=".$posts[$i]['id']; echo "$adr"?>>Изменить</a>
                    <a class="card-time"><?php
                        $time = $posts[$i]['data_w'];
                        echo date('d.m.Y ', $time);
                        echo date('   H:i', $time); ?></a>
                </div>
            </div>

        <?php   } ?>
    </div>
    </body>
    </html>

<?php
}
?>