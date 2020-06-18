<?php
session_start();
require_once('config.php');


function upload()
{
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    echo $_FILES["file"]["size"];
    $extension = end($temp);     // 获取文件后缀名
    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 102400)   // 小于 10 MB
        && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "错误：: " . $_FILES["file"]["error"] . "<br>";
        } else {
            echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
            echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
            echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";

            // 判断当前目录下的 upload 目录是否存在该文件
            // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
            if (file_exists("upload/" . $_FILES["file"]["name"])) {
                echo '<script>alert(" 该文件已经存在! ")</script>';
            } else {
                try {
                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                    $iso = "";
                    $latitude = "";
                    $longitude = "";
                    $citycode = "";

                    if ($_POST['city'] != 'placeholder') {
                        $sql = 'SELECT Latitude,Longitude,GeoNameID,CountryCodeISO FROM geocities WHERE AsciiName=:city LIMIT 1';
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':city', $_POST['city']);
                        $statement->execute();
                        $row = $statement->fetch();
                        $iso = $row['CountryCodeISO'];
                        $latitude = $row['Latitude'];
                        $longitude = $row['Longitude'];
                        $citycode = $row['GeoNameID'];
                    } else {
                        $sql = 'SELECT ISO FROM geocountries WHERE CountryName=:countryname';
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':countryname', $_POST['country']);
                        $statement->execute();
                        $row = $statement->fetch();
                        $iso = $row['ISO'];
                    }

                    $sql = 'INSERT INTO travelimage (Title,Description,Latitude,Longitude,CityCode,CountryCodeISO,UID,PATH,Content) VALUES (:title,:description,:latitude,:longitude,:citycode,:iso,:uid,:path,:content)';
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':title', $_POST['title']);
                    $statement->bindValue(':description', $_POST['description']);
                    $statement->bindValue(':latitude', $latitude);
                    $statement->bindValue(':longitude', $longitude);
                    $statement->bindValue(':citycode', $citycode);
                    $statement->bindValue(':iso', $iso);
                    $statement->bindValue(':uid', $_SESSION['id']);
                    $statement->bindValue(':path', '../travel-images/large/' . $_FILES["file"]["name"]);
                    $statement->bindValue(':content', $_POST['content']);
                    $statement->execute();

                    if ($statement) {
                        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                        echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];

                        header("location: login.php");
                    } else {
                        '<script>alert("文件上传失败")</script>';
                    }

                    $pdo = null;
                } catch (PDOException $e) {
                    '<script>alert("服务器连接出错")</script>';
                }
            }
        }
    } else {
        echo '<script>alert("非法的文件格式，请使用jpg、jpeg、pjpeg、png、x-png、gif图片格式")</script>';
    }
}


?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>上传图片-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/upload.css" rel="stylesheet" type="text/css">
    <script src="../js/prefixfree.min.js" rel="script" type="text/javascript"></script>
</head>
<body>
<header class="mainImg">
    <nav class="banner">
        <a href="../../index.php" class="logo">
            <img src="../image/logo.svg" alt="logo">
            <p>ImgShow</p>
        </a>
        <a href="../../index.php" class="link">首页</a>
        <a href="search.php" class="link">搜索</a>
        <a href="browse.php" class="link">阅览</a>
        <div class="dropdown-menu highlight">个人中心
            <ul>
                <li class="menu_item highlight"><a href="upload.php" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="my_photos.php" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="my_favourite.php" class="collections">我的收藏</a></li>
                <li class="menu_item"><a href="login.php" class="log-in">登录</a></li>
            </ul>
        </div>
    </nav>
</header>

<aside id="sidebar" class="sidebar">
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
</aside>

<main>
    <form action="upload.php" method="post"><h3>选择图片</h3>
        <label for="ImagesUpload" id="ImgUpBtn" class="ImgUpBtnBox">
            <input type="file" accept="image/*" name="ImagesUpload" id="ImagesUpload" class="uploadHide">
            <img src="../image/add.svg" class="uploadBtnImg" width="32" height="32"/>
        </label>
        <div class="ImagesUpload" id="img-preview"></div>
        <div id="removeBox"></div>
        <label><p>图片标题</p>
            <input type="text" name="title" class="title" required>
        </label>
        <label><p>图片描述</p>
            <textarea name="description" class="description" required></textarea>
        </label>
        <label><p>拍摄国家</p>
            <input type="text" name="country" class="country" required>
        </label>
        <label><p>拍摄城市</p>
            <input type="text" name="city" class="city" required>
        </label>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            upload();
        }
        ?>
        <input type="submit" class="submit" value="上传">
    </form>
</main>

<footer>
    <div class="footer__nav">
        <ul>
            <li>
                <a class="footer__navbar__logo" href="../../index.php">
                    <img src="../image/logo.svg" alt="logo"/>
                    <div class="footer__logo__text">
                        <p><span>ImgShow</span> — 免费的精美照片由我们认真负责的TA们提供。</p>
                    </div>
                </a>
            </li>
        </ul>
        <p> © 2020-现在 版权所有 备案号19302010059</p>
        <ul class="footer__nav__list">
            <li>
                <a class="link" href="">使用条款</a>
            </li>
            <li>
                <a class="link" href="">隐私政策</a>
            </li>
            <li>
                <a class="link" href="">许可证书</a>
            </li>
            <li>
                <a class="link" href="">版本说明</a>
            </li>
            <li>
                <div class="languageChoose">
                    <img src="../image/CN.svg" alt="Chinese">
                    <p>简体中文</p>
                </div>
            </li>
        </ul>
    </div>
</footer>
<script src="../js/UIscript.js" rel="script" type="text/javascript"></script>
</body>
</html>