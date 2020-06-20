<?php
session_start();
require_once('config.php');
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>图片详情-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/details.css" rel="stylesheet" type="text/css">
    <script src="../js/prefixfree.min.js" rel="script" type="text/javascript"></script>
</head>
<body class="details">
<header class="mainImg">
    <nav class="banner">
        <a href="../../index.php" class="logo">
            <img src="../image/logo.svg" alt="logo">
            <p>ImgShow</p>
        </a>
        <a href="../../index.php" class="link">首页</a>
        <a href="search.php" class="link">搜索</a>
        <a href="browse.php" class="link">阅览</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<div class="dropdown-menu">
                      <a href="">个人中心</a>
                      <ul>
                <li class="menu_item"><a href="upload.php" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="my_photos.php" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="my_favourite.php" class="collections">我的收藏</a></li>
                 <li class="menu_item"><a href="logout.php" class="logout">退出登录</a></li>
                </ul>
                </div>';
        } else {
            echo '<a href="login.php" class="link"></span>登录</a>';
        }
        ?>
    </nav>
</header>

<aside id="sidebar" class="sidebar">
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
</aside>

<main>

    <?php
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql = "select Content,Description,Title,PATH from travelimage where ImageID=:imageid";
        $result = $pdo->prepare($sql);
        $result->bindValue(':imageid', $_GET['imageid']);
        $result->execute();
        $figure = $result->fetch();
        $theme = $figure['Content'];
        $description = $figure['Description'];
        $title = $figure['Title'];
        $path = $figure['PATH'];

        $sql = "select travelimagefavor.UID from  travelimagefavor join travelimage on travelimage.ImageID=travelimagefavor.ImageID where travelimage.ImageID=:imageid";
        $result = $pdo->prepare($sql);
        $result->bindValue(':imageid', $_GET['imageid']);
        $result->execute();
        $favor = $result->rowCount();
        $collected = false;
        while ($row = $result->fetch()) {
            if ($row['UID'] == $_SESSION['id']) {
                $collected = true;
            }
        }
        $collectedClass = $collected ? "favor" : "";

        $sql = "select CountryName from travelimage join geocountries on travelimage.CountryCodeISO=geocountries.ISO where ImageID=:imageid";
        $result = $pdo->prepare($sql);
        $result->bindValue(':imageid', $_GET['imageid']);
        $result->execute();
        $figure = $result->fetch();
        $country = $figure['CountryName'];

        $sql = "select AsciiName from travelimage join geocities on travelimage.CityCode=geocities.GeoNameID where ImageID=:imageid";
        $result = $pdo->prepare($sql);
        $result->bindValue(':imageid', $_GET['imageid']);
        $result->execute();
        $figure = $result->fetch();
        $city = $figure['AsciiName'];


        echo '<h2>' . $title . '</h2>
        <figure>
        <img src="../travel-images/medium/' . $path . '" >
        <div class="content">
            <ul>
                <li>收藏人数</li>
                <li class="collection_number"><span>' . $favor . '</span><a href="collect.php?imageid=' . $_GET['imageid'] . '&collected='.$collected.'" class="' . $collectedClass . ' collect"></a></li>
            </ul>
            <ul>
                <li>图片信息</li>
                <li>主题:<span class="subject">' . $theme . '</span></li>
                <li>国家:<span class="country">' . $country . '</span> </li>
                <li>城市:<span class="city">' . $city . '</span> </li>
            </ul>
        </div>
    </figure>
    <article>
        <p>' . $description . '</p>
    </article>';
    } catch (PDOException $e) {
        echo '<script>alert("服务器错误！")</script>';
    }

    ?>
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