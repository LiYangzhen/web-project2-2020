<?php
session_start();    //启动会话
require_once('config.php');

define('ROOT', dirname(dirname(__FILE__)) . '/travel-images/');

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $sql='SELECT PATH FROM travelimage WHERE ImageID=:imageid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':imageid', $_GET['imageid']);
    $statement->execute();
    $row = $statement->fetch();
    $filename = $row['PATH'];


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

        $res = unlink(ROOT."upload/".$filename);
        $res = $res && unlink(ROOT."large/".$filename);
        $res = $res && unlink(ROOT."medium/".$filename);
        $res = $res && unlink(ROOT."small/".$filename);
        $res = $res && unlink(ROOT."thumb/".$filename);
        if ($res) {
            echo '<script>alert("删除图片成功")</script>';
        } else {
            echo '<script>alert("成功删除图片记录，但图片文件删除失败")</script>';
        }
    }
    $pdo = null;
    header("Location: " . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
    echo '<script>alert("无法连接到服务器")</script>';
}

