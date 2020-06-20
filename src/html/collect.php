<?php
session_start();    //启动会话
require_once('config.php');
if (isset($_SESSION['id'])) {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        if ($_GET['collected']) {
            $sql = 'DELETE FROM travelimagefavor WHERE ImageID=:imageid AND UID=:id';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':imageid', $_GET['imageid']);
            $statement->bindValue(':id', $_SESSION['id']);
            $statement->execute();
            if (!$statement) {
                echo '<script>alert("取消收藏失败")</script>';
            }
        } else {
            $sql = 'INSERT INTO travelimagefavor (UID,ImageID) VALUES (:uid,:imageid)';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':uid', $_SESSION['id']);
            $statement->bindValue(':imageid', $_GET['imageid']);
            $statement->execute();
            if (!$statement) {
                echo '<script>alert("收藏失败")</script>';
            }
        }
        $pdo = null;
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } catch (PDOException $e) {
        echo '<script>alert("无法连接到服务器")</script>';
    }
} else {
    echo '<script>alert("请登陆后收藏")</script>';
}
