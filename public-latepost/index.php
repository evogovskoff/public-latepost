<?php
		session_start();
		/*if(isset($_GET['postid'])) {
            $postid = $_GET['postid'];
            require_once 'db.php';
            $q =  $conn->prepare("SELECT * FROM `posts` WHERE `id`= :id");
            $q->bindParam(':id', $postid);
            $q->execute();
            $post = $q->fetch(PDO::FETCH_ASSOC);
            if ($post['isPublic']==1) {
                $postText = $post['text'];
            }
            $textIf= $post['text_if'];
        } */

//Если нет сессии, но есть куки
		if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
					if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
						require_once 'db.php';

						$q =  $conn->prepare("SELECT * FROM `logins` WHERE `user_id`= :id AND `user_hash` = :hash");
						$q->bindParam(':id', $_COOKIE['id']);
						$q->bindParam(':hash', $_COOKIE['hash']);
						$q->execute();
						$d0 = $q->fetch(PDO::FETCH_ASSOC);

						$q1 =  $conn->prepare("SELECT * FROM `users` WHERE `username` = :username");
						$q1->bindParam(':username', $d0['username']);
						$q1->execute();
						$d = $q1->fetch(PDO::FETCH_ASSOC);

						$_SESSION['auth'] = true;
						$_SESSION['id'] = $d['id'];
						$_SESSION['username'] = $d['username'];
						$_SESSION['password'] = $d['password'];
						$_SESSION['hash'] = $d0['user_hash'];	
					}
				}		

//Если есть сессия
		if (!empty($_SESSION['id']) and !empty($_SESSION['hash'])) {


			require_once 'db.php';

            $query =  $conn->prepare("SELECT * FROM `logins` WHERE `user_id`= :id AND `user_hash` = :hash");
			$query->bindParam(':id', $_SESSION['id']);
			$query->bindParam(':hash', $_SESSION['hash']);
			$time = time() - 1209600;
			$query->execute();
			$data0 = $query->fetch(PDO::FETCH_ASSOC);

			$query1 =  $conn->prepare("SELECT * FROM `users` WHERE `username` = :username");
			$query1->bindParam(':username', $data0['username']);
			$query1->execute();
			$data = $query1->fetch(PDO::FETCH_ASSOC);

			if ($data['id']==$_SESSION['id'] AND $data0['user_hash']==$_SESSION['hash'] AND $data0['time'] > $time) {
			$email = $data['email'];

			$query2 =  $conn->prepare("SELECT * FROM `posts` WHERE `user_id`= :id");
			$query2->bindParam(':id', $_SESSION['id']);
			$query2->execute();
			$posts = $query2->fetchAll();
            $len=count($posts);

			?>

<!DOCTYPE html>
<html>
<head>
	<title>LatePost</title>

    <link rel="shortcut icon" href ="/favicon.ico" type="image/x-icon">
    <link rel="icon" href ="/favicon.ico" type= "image/x-icon">

    <script type="text/javascript" src="jquery-3.0.0.min.js"></script>

    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.min.css">
	<meta charset="utf-8">
    <script type="text/javascript" src="jquery.datetimepicker.full.js"></script>

</head>
<body>
	<div id="head">
        <a class="card-button exit" href="/logout.php">Выйти</a>
    </div>
	<?php if ($_SESSION['auth']==true) { ?>


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
                    <?php
                    for ($i=0; $i<count($posts); $i++){
                        ?>
                        <div class="card">
                        <div id="card-content">
                            <span class="card-title">
                                <?php
                                if ($posts[$i]['data_p']>time()) {
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
                            echo "$adr"?>><?php echo "www.latepost.ru/".$adr?></a><br>
                            <a class="card-button onPost" href=<?php $adr="delpost.php?postid=".$posts[$i]['id']; echo "$adr"?>>Удалить</a>
                            <?php if ($posts[$i][data_p]>time()) { ?>
                            <a class="card-button onPost" href=<?php $adr="postupdate.php?postid=".$posts[$i]['id']; echo "$adr"?>>Изменить</a>
                            <?php }?>
                            <a class="card-time"><?php
                            $time = $posts[$i]['data_w'];
                            echo date('d.m.Y ', $time);
                            echo date('   H:i', $time); ?></a>
                        </div>
                        </div>

                     <?php   } ?>

                <?php } } } else { ?>


<!-- Если неавторизован -->
<!DOCTYPE html>
<html>
<head>
	<title>LatePost</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<meta charset="utf-8">
    <link rel="shortcut icon" href ="/favicon.ico" type="image/x-icon">
    <link rel="icon" href ="/favicon.ico" type= "image/x-icon">
</head>
<body>
	<div class="login-page">
		<div class="form">
			<form action="login.php" method="POST" class="login-form">
				<input id="input" type="text" name="username" placeholder="Логин"><br>
				<input id="input" type="password" name="password" placeholder="Пароль"><br>
				<input id="button" type="submit" name="submit" value="Войти">
			</form>
		</div>
	</div><br>

    <div class="indexSearch">
        <div class="search">
            <div class="form">
                <form action="showpost.php" method="POST">
                    <p id="searchP">Поиск поста</p>
                    <input id="searcher" type="text" name="postid" placeholder="Введите id поста">
                    <input id="searchB" type="submit" name="submit" value="Поиск">
                </form>
            </div>
        </div>
    </div>
    
    <div class="descript">
        <p class="descriptP">
            Это LatePost, сервис отложенной публикации сообщений.</br>
            Здесь ты можешь написать сообщение(пост), дать ему свой уникальный ID и выбрать, когда оно будет опубликовано.</br>
            Любой, кто знает ID твоего поста или владеет ссылкой на него может увидеть его содержание.</br>
            Но полностью увидеть это сообщение станет возможно лишь тогда, когда наступит то время публикации, которое ты выбрал. До него - лишь "условие" поста.</br>
            <a class="descriptP" href="https://vk.com/evodovskov" target="_blank">Автор</a> 
        </p>
    </div>

	<div class="reg-page">
		<div class="form">
			<form action="registration.php" method="POST">
				<input type="text" name="username" placeholder="Логин">
				<input type="password" name="password" placeholder="Пароль">
				<input type="text" name="email" placeholder="Электропочта">
				<input id="button" type="submit" name="submit" value="Регистрация">
			</form>
		</div>	
	</div>



	<div class="Logo">
		<p id="logo">LatePost</p>
	</div>




</body>
</html>
<?php } ?>