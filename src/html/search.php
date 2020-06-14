<?php
session_start();
require_once('config.php');

$_SESSION['title'] = null;
$_SESSION['description'] = null;

function generate($result)
{
    $i = 0;
    while (($row = $result->fetch()) && $i < 18) {
        echo '<li class="thumbnail" title="' . $row['Title'] . '">
                <a href="details.php?imageid=' . $row['ImageID'] . '">
                    <div class="img-box">
                        <img src="../travel-images/small/' . $row['PATH'] . '" alt="图片">
                    </div>
                    <div><h3>' . $row['Title'] . '</h3>
                        <p>' . $row['Description'] . '</p>
                    </div>
                </a>
            </li>';
        $i++;
    }
}

function fuzzyQueryFirst()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pattern = '/\b[a-zA-Z0-9]+\b/';
        if ($_POST['search_type'] == "search_by_title" || !empty($_GET['title'])) {
            preg_match_all($pattern, $_SESSION['title'], $res);
            $i = 0;
            $sql = 'SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Title LIKE "%' . $res[0][$i] . '%"';
            $i++;
            while ($i < count($res[0])) {
                $sql = $sql . 'UNION SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Title LIKE "%' . $res[0][$i] . '%"';
                $i++;
            }
        } elseif ($_POST['search_type'] == "search_by_description" || !empty($_GET['description'])) {
            preg_match_all($pattern, $_SESSION['description'], $res);
            $i = 0;
            $sql = 'SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Description LIKE "%' . $res[0][$i] . '%"';
            $i++;
            while ($i < count($res[0])) {
                $sql = $sql . 'UNION SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Description LIKE "%' . $res[0][$i] . '%"';
                $i++;
            }
        }
        $statement = $pdo->query($sql);
        if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
            generate($statement);
        } else {
            echo '<h4>搜索无结果</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo '<script>alert("错误：无法连接到服务器")</script>';
    }
}

function fuzzyQueryAgain()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pattern = '/\b[a-zA-Z0-9]+\b/';
        if ($_POST['search_type'] == "search_by_title" || !empty($_GET['title'])) {
            preg_match_all($pattern, $_SESSION['title'], $res);
            $i = 0;
            $sql = 'SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Title LIKE "%' . $res[0][$i] . '%"';
            $i++;
            while ($i < count($res[0])) {
                $sql = $sql . 'UNION SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Title LIKE "%' . $res[0][$i] . '%"';
                $i++;
            }
        } elseif ($_POST['search_type'] == "search_by_description" || !empty($_GET['description'])) {
            preg_match_all($pattern, $_SESSION['description'], $res);
            $i = 0;
            $sql = 'SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Description LIKE "%' . $res[0][$i] . '%"';
            $i++;
            while ($i < count($res[0])) {
                $sql = $sql . 'UNION SELECT ImageID,PATH,Title,Description FROM travelimage WHERE Description LIKE "%' . $res[0][$i] . '%"';
                $i++;
            }
        }
        $num = $_SESSION['page'] * 18;
        $sql = $sql . "LIMIT $num,18";
        $statement = $pdo->query($sql);
        if ($statement->rowCount() > 0) {
            generate($statement);
        } else {
            echo '<h4>搜索无结果</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo '<script>alert("错误：无法连接到服务器")</script>';
    }
}

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
                <li class="menu_item"><a href="upload.html" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="my_photos.html" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="my_favourite.html" class="collections">我的收藏</a></li>
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
            creatPageNumber();
        }

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
            $total = floor(($_SESSION['sum'] / 18) + 1);
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
            if (isset($_GET['title']) || $_POST['search_type'] == "search_by_title") {
                $url = "search.php?page=" . $num;
                $url = $url . "&title=" . $_SESSION['title'];
                $url = $url . "&submit=搜索";
                return $url;
            } elseif (isset($_GET['description']) || $_POST['search_type'] == "search_by_description") {
                $url = "search.php?page=" . $num;
                $url = $url . "&description=" . $_SESSION['description'];
                $url = $url . "&submit=搜索";
                return $url;
            }
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