<?php
session_start();    //启动会话
require_once('config.php');
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = 'DELETE FROM travelimage WHERE ImageID=:imageid AND UID=:id';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':imageid', $_GET['imageid']);
    $statement->bindValue(':id', $_SESSION['id']);
    $statement->execute();
    if (!$statement) {
        echo '<script>alert("删除图片失败")</script>';
    } else {
        $sql = 'DELETE FROM travelimagefavor WHERE ImageID=:imageid';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':imageid', $_GET['imageid']);
        $statement->execute();
    }
    $pdo = null;
    header("Location: " . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    echo '<script>alert("无法连接到服务器")</script>';
}
