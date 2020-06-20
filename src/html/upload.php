<?php
session_start();
require_once('config.php');


define('ROOT', dirname(dirname(__FILE__)) . '/travel-images/');

function upload()
{
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    echo '<script>alert(' . $_FILES["file"]["name"] . ')</script>';
    $extension = end($temp);     // 获取文件后缀名
    if (($_FILES["file"]["size"] < 5 * 1024 * 1024) && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "错误：: " . $_FILES["file"]["error"] . "<br>";
        } else {
            if (file_exists(ROOT . 'upload/' . $_FILES["file"]["name"])) {
                echo '<script>alert(" 该文件已经存在! ")</script>';
            } elseif (is_uploaded_file($_FILES['file']['tmp_name'])) {
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

                    $sql = 'SELECT MAX(ImageID) FROM travelimage';
                    $statement = $pdo->query($sql);
                    $row = $statement->fetch();
                    $imageid = $row[0] + 1;


                    $sql = 'INSERT INTO travelimage (ImageID,Title,Description,Latitude,Longitude,CityCode,CountryCodeISO,UID,PATH,Content) VALUES (:imageid,:title,:description,:latitude,:longitude,:citycode,:iso,:uid,:path,:content)';
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':imageid', (int)$imageid);
                    $statement->bindValue(':title', (string)$_POST['title']);
                    $statement->bindValue(':description', (string)$_POST['description']);
                    $statement->bindValue(':latitude', (double)$latitude);
                    $statement->bindValue(':longitude', (double)$longitude);
                    $statement->bindValue(':citycode', (double)$citycode);
                    $statement->bindValue(':iso', (string)$iso);
                    $statement->bindValue(':uid', (int)$_SESSION['id']);
                    $statement->bindValue(':path', (string)$_FILES["file"]["name"]);
                    $statement->bindValue(':content', (string)$_POST['content']);
                    $statement->execute();

                    if ($statement) {
                        $stored_path = ROOT . "upload/" . basename($_FILES['file']['name']);
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $stored_path)) {
                            compressedImage($stored_path, ROOT . "large/" . basename($_FILES['file']['name']), 1024);
                            compressedImage($stored_path, ROOT . "medium/" . basename($_FILES['file']['name']), 640);
                            compressedImage($stored_path, ROOT . "small/" . basename($_FILES['file']['name']), 320);
                            compressedImage($stored_path, ROOT . "thumb/" . basename($_FILES['file']['name']), 100);
                            echo '<script>alert("文件上传成功")</script>';
                            header("location: my_photos.php");
                        } else {
                            echo '<script>alert("文件转存失败")</script>';
                        }
                    } else {
                        echo '<script>alert("文件上传失败")</script>';
                    }

                    $pdo = null;
                } catch (PDOException $e) {
                    echo '<script>alert("服务器连接出错")</script>';
                }
            }
        }
    } else {
        echo '<script>alert("图片大小不能超过5MB")</script>';
    }
}

function edit()
{
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

        $sql = 'UPDATE travelimage SET Title=:title,Description=:description,Latitude=:latitude,Longitude=:longitude,CityCode=:citycode,CountryCodeISO=:iso,Content=:content WHERE ImageID=:imageid';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':imageid', $_GET['imageid']);
        $statement->bindValue(':title', $_POST['title']);
        $statement->bindValue(':description', $_POST['description']);
        $statement->bindValue(':latitude', $latitude);
        $statement->bindValue(':longitude', $longitude);
        $statement->bindValue(':citycode', $citycode);
        $statement->bindValue(':iso', $iso);
        $statement->bindValue(':content', $_POST['theme']);
        $statement->execute();
        $row = $statement->rowCount();

        if ($row) {
            echo '<script>alert("文件修改成功")</script>';
        } else {
            echo '<script>alert("文件修改失败")</script>';
        }
//        header("location: my_photos.php");
        $pdo = null;
    } catch (PDOException $e) {
        echo '<script>alert("服务器连接出错")</script>';
    }
}

function compressedImage($imgsrc, $imgdst, $goal)
{
    list($width, $height, $type) = getimagesize($imgsrc);
    $new_width = $width;//压缩后的图片宽
    $new_height = $height;//压缩后的图片高
    if ($width >= $goal) {
        $per = $goal / $width;//计算比例
        $new_width = $width * $per;
        $new_height = $height * $per;
    }
    switch ($type) {
        case 1:
            $giftype = check_gifcartoon($imgsrc);
            if ($giftype) {
                header('Content-Type:image/gif');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromgif($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                //90代表的是质量、压缩图片容量大小
                imagejpeg($image_wp, $imgdst, 90);
                imagedestroy($image_wp);
                imagedestroy($image);
            }
            break;
        case 2:
            header('Content-Type:image/jpeg');
            $image_wp = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefromjpeg($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            //90代表的是质量、压缩图片容量大小
            imagejpeg($image_wp, $imgdst, 90);
            imagedestroy($image_wp);
            imagedestroy($image);
            break;
        case 3:
            header('Content-Type:image/png');
            $image_wp = imagecreatetruecolor($new_width, $new_height);
            $image = imagecreatefrompng($imgsrc);
            imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            //90代表的是质量、压缩图片容量大小
            imagejpeg($image_wp, $imgdst, 90);
            imagedestroy($image_wp);
            imagedestroy($image);
            break;
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
                <li class="menu_item"><a href="logout.php" class="logout">退出登录</a></li>
            </ul>
        </div>
    </nav>
</header>

<aside id="sidebar" class="sidebar">
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
</aside>

<main>
        <?php
        if (isset($_GET['imageid'])) {
            try {
                $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                $sql = "select Description,Title,PATH from travelimage where ImageID=:imageid";
                $result = $pdo->prepare($sql);
                $result->bindValue(':imageid', $_GET['imageid']);
                $result->execute();
                $figure = $result->fetch();
                $description = $figure['Description'];
                $title = $figure['Title'];
                $path = $figure['PATH'];

                echo '<form action="upload.php?imageid='.$_GET['imageid'].'" method="post" enctype="multipart/form-data">
        <h3>选择图片</h3>
        <div class="ImagesUpload" id="img-preview"><img src="../travel-images/large/' . $path . '"></div>
        <label><p>图片标题</p>
            <input type="text" name="title" class="title" value="' . $title . '" required>
        </label>
        <label><p>图片描述</p>
            <textarea name="description" class="description">' . $description . '</textarea></label>';
            } catch (PDOException $e) {
                echo '<script>alert("服务器错误！")</script>';
            }
        } else {
            echo '<form action="upload.php" method="post" enctype="multipart/form-data">
        <h3>选择图片</h3>
        <label for="ImagesUpload" id="ImgUpBtn" class="ImgUpBtnBox">
            <input type="file" accept="image/*" name="file" id="ImagesUpload" class="uploadHide">
            <img src="../image/add.svg" class="uploadBtnImg" width="32" height="32"/>
        </label>
        <div class="ImagesUpload" id="img-preview"></div>
        <div id="removeBox"></div>
        <label><p>图片标题</p>
            <input type="text" name="title" class="title" required>
        </label>
         <label><p>图片描述</p>
            <textarea name="description" class="description"></textarea></label>';
        }
        ?>

        <label>
            <select name="theme" required>
                <option value="placeholder" selected disabled>按主题筛选</option>
                <option value="scenery">scenery</option>
                <option value="city">city</option>
                <option value="people">people</option>
                <option value="animal">animal</option>
                <option value="building">building</option>
                <option value="wonder">wonder</option>
                <option value="other">other</option>
            </select>
            <select name="country" id="country" onchange="addOption()">
                <option value="placeholder" selected>按国家筛选</option>
            </select>
            <select name="city" id="city"></select>
        </label>

        <?php
        if (isset($_GET['imageid'])) {
            echo '<input type="submit" name="edit" class="submit" value="修改">';
        } else {
            echo '<input type="submit" name="upload" class="submit" value="上传">';
        }
        ?>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
            upload();
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
            edit();
        }
        ?>
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
<script src="../js/UIscript.js" rel="script" type="text/javascript" defer></script>
</body>
</html>