<?php

require_once 'db.php';

$q =  $conn->prepare("UPDATE `posts` SET `isPublic` = '1' WHERE `data_p` < :date");
$q->bindParam(':date', time());
$q->execute();
$d0 = $q->fetch(PDO::FETCH_ASSOC);
echo 'f';
?>