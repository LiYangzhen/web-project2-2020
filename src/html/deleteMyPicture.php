<?php
session_start();    //启动会话
try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql = 'DELETE FROM travelimage WHERE ImageID=:imageid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':imageid', $_GET['imageid']);
    $statement->execute();
    if (!$statement) {
        echo '<script>alert("图片删除失败")</script>';
    }
} catch (PDOException $e) {
    echo '<script>alert("无法连接到服务器")</script>';
}
header("Location: " . $_SERVER['HTTP_REFERER']);