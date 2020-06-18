<?php
session_start();    //启动会话
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = 'DELETE FROM travelimagefavor WHERE ImageID=:imageid AND UID=:id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':imageid', $_GET['imageid']);
    $statement->bindValue(':imageid', $_SESSION['id']);
    $statement->execute();
    if (!$statement) {
        echo '<script>alert("取消收藏失败")</script>';
    }
} catch (PDOException $e) {
    echo '<script>alert("无法连接到服务器")</script>';
}
header("Location: " . $_SERVER['HTTP_REFERER']);