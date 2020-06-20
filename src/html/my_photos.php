<?php
session_start();
require_once('config.php');

$_SESSION['showNum'] = 18;

function generate($result)
{
    $i = 0;
    while (($row = $result->fetch()) && $i < $_SESSION['showNum']) {
        echo '<li class="thumbnail" title="' . $row['Title'] . '">
                <a href="details.php?imageid=' . $row['ImageID'] . '">
                    <div class="img-box">
                        <img src="../travel-images/small/' . $row['PATH'] . '" alt="图片">
                    </div>
                    <div><h3>' . $row['Title'] . '</h3>
                        <p>' . $row['Description'] . '</p>
                    </div>
                </a>
                <div class="editBox">
                     <a href="upload.php?imageid=' . $row['ImageID'] . '">编辑</a>
                     <a href="delete.php?imageid=' . $row['ImageID'] . '">删除</a>
                </div>
            </li>';
        $i++;
    }
}

function generateMine()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $num = $_SESSION['page'] * 18;
        $max = $_SESSION['showNum'];
        $sql = "SELECT ImageID,PATH,Title,Description FROM travelimage WHERE UID=:id LIMIT $num,$max";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION['id']);
        $statement->execute();
        if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
            generate($statement);
        } else {
            echo '<h4>您还未上传过图片</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo '<h4>服务器连接错误</h4>';
    }
}

function countSum()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql = 'SELECT count(*) FROM travelimage WHERE UID=:id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION['id']);
        $statement->execute();
        $row = $statement->fetch();
        $_SESSION['sum'] = $row[0];
        $_SESSION['page'] = 0;
        generateMine();
        $pdo = null;
    } catch (PDOException $e) {
        echo '<h4>服务器连接错误</h4>';
    }
}

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>我的图片-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/my_ImageGroup.css" rel="stylesheet" type="text/css">
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
        <a href="browse.php" class="link">浏览</a>
        <div class="dropdown-menu highlight">个人中心
            <ul>
                <li class="menu_item"><a href="upload.php" class="upload">上传图片</a></li>
                <li class="menu_item highlight"><a href="my_photos.php" class="my-pictures">我的图片</a></li>
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
    <h2>我的图片</h2>
    <section class="imgGroup">
        <ul>
            <?php
            if (!isset($_GET['page'])) {
                countSum();
            } else {
                $_SESSION['page'] = $_GET['page'];
                generateMine();
            }
            ?>
        </ul>
    </section>
</main>

<div id="pagination" class="pagination">
    <ul>
        <?php
        creatPageNumber();

        function isActive($num)
        {
            if ($num == $_SESSION['page']) {
                return "active";
            } else {
                return "";
            }
        }

        function creatPageNumber()
        {
            $total = floor(($_SESSION['sum'] / $_SESSION['showNum']) + 1);
            if ($total > 1 && $total < 6) {
                if ($_SESSION['page'] > 0) {
                    echo '<a href="' . changePage(0) . '">首页</a>
                <a href="' . changePage($_SESSION['page'] - 1) . '"><</a>';
                }
                for ($i = 0; $i < $total; $i++) {
                    echo '<a href="' . changePage($i) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
                }
                if ($_SESSION['page'] < $total - 1) {
                    echo '<a href="' . changePage($_SESSION['page'] + 1) . '"> > </a>';
                    echo '<a href="' . changePage($total - 1) . ' ">尾页</a>';
                }
            } elseif ($total > 5) {
                if ($_SESSION['page'] > 1) {
                    echo '<a href="' . changePage(0) . '">首页</a>
                <a href="' . changePage($_SESSION['page'] - 1) . '"> < </a>';
                }
                if ($_SESSION['page'] < 2) {
                    for ($i = 0; $i < 5; $i++) {
                        echo '<a href="' . changePage($i) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
                    }
                } else {
                    for ($i = $_SESSION['page'] - 2; $i <= $i = $_SESSION['page'] + 2; $i++) {
                        echo '<a href="' . changePage($i) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
                    }
                }
                if ($_SESSION['page'] < $total - 1) {
                    echo '<a href="' . changePage($_SESSION['page'] + 1) . '"> > </a>';
                    echo '<a href="' . changePage($total - 1) . ' ">尾页</a>';
                }
            }
        }

        function changePage($num)
        {
            $url = "my_photos.php?page=" . $num;
            return $url;
        }

        ?>
    </ul>
</div>

<footer>
    <!--    页脚横幅-->
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