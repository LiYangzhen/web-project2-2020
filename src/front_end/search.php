<?php
session_start();
require_once(dirname(dirname(__FILE__)).'/rear_end/config.php');
require_once(dirname(dirname(__FILE__)).'/rear_end/generate.php');
require_once(dirname(dirname(__FILE__)) . '/rear_end/pageNum.php');

$_SESSION['title'] = null;
$_SESSION['description'] = null;
$_SESSION['showNum'] = 18;

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>搜索页-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/my_ImageGroup.css" rel="stylesheet" type="text/css">
    <link href="../css/search.css" rel="stylesheet" type="text/css">
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
        <a href="search.php" class="highlight link">搜索</a>
        <a href="browse.php" class="link">阅览</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<div class="dropdown-menu">
                      <a href="">个人中心</a>
                      <ul>
                <li class="menu_item"><a href="upload.php" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="my_photos.php" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="my_favourite.php" class="collections">我的收藏</a></li>
                 <li class="menu_item"><a href="../rear_end/logout.php" class="logout">退出登录</a></li>
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
    <form method="post" action="search.php">
        <h1>搜索</h1>
        <div class="search_box">
            <input type="radio" value="search_by_title" name="search_type" id="search_by_title" checked>
            <label for="search_by_title">按标题查询</label>
            <input type="search" name="title">
            <input type="radio" value="search_by_description" name="search_type" id="search_by_description">
            <label for="search_by_description">按描述查询</label>
            <textarea type="search" name="description"></textarea>
            <input type="submit" value="搜索" name="search" id="search">
        </div>
    </form>
    <section class="imgGroup">
        <?php
        if (isset($_GET['submit']) || $_SERVER["REQUEST_METHOD"] == "POST") {
            echo '<h2>结果</h2>';
        }
        ?>
        <ul>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['search_type'] == "search_by_title" && $_POST['title'] != "") {
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['description'] = null;
                $_SESSION['page'] = 0;
                $_SESSION['sum'] = 0;
                fuzzyQueryFirst();
            } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['search_type'] == "search_by_description" && $_POST['description'] != "") {
                $_SESSION['title'] = null;
                $_SESSION['description'] = $_POST['description'];
                $_SESSION['page'] = 0;
                $_SESSION['sum'] = 0;
                fuzzyQueryFirst();
            } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<h4>请输入内容</h4>";
            }
            if (isset($_GET['title'])) {
                $_SESSION['title'] = $_GET['title'];
            }
            if (isset($_GET['description'])) {
                $_SESSION['description'] = $_GET['description'];
            }
            if (isset($_GET['submit'])) {
                if (isset($_GET['page']) && $_GET['page'] != "") {
                    $_SESSION['page'] = $_GET['page'];
                    if ($_SESSION['page'] == 0) {
                        fuzzyQueryFirst();
                    } else {
                        fuzzyQueryAgain();
                    }
                } else {
                    fuzzyQueryFirst();
                }
            }

            ?>
    </section>
</main>
<div id="pagination" class="pagination">
    <ul>
        <?php
        if (($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST['description'] != "" || $_POST['title'] != "")) || isset($_GET['submit'])) {
            creatPageNumber("search");
        }
        ?>
    </ul>
</div>
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
                <a class="link" href="" onclick="alert('别攻击就行')">使用条款</a>
            </li>
            <li>
                <a class="link" href="" onclick="alert('我们没有隐私政策')">隐私政策</a>
            </li>
            <li>
                <a class="link" href="">许可证书</a>
            </li>
            <li>
                <a class="link" href="" onclick="alert('盘古开天地1.0版')">版本说明</a>
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