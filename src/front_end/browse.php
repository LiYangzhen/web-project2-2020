<?php
session_start();
require_once(dirname(dirname(__FILE__)) . '/rear_end/config.php');
require_once(dirname(dirname(__FILE__)) . '/rear_end/generate.php');
require_once(dirname(dirname(__FILE__)) . '/rear_end/pageNum.php');

$_SESSION['showNum'] = 18;

?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>浏览页-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/browse.css" rel="stylesheet" type="text/css">
    <script src="../js/prefixfree.min.js" rel="script" type="text/javascript"></script>
</head>
<body class="browse">
<header class="mainImg">
    <nav class="banner">
        <a href="../../index.php" class="logo">
            <img src="../image/logo.svg" alt="logo">
            <p>ImgShow</p>
        </a>
        <a href="../../index.php" class="link">首页</a>
        <a href="search.php" class="link">搜索</a>
        <a href="browse.php" class="highlight link">阅览</a>
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

<div class="browse_box">
    <aside class="search_nav">
        <div class="search_bar">
            <form method="post" action="browse.php">
                <input name="title" placeholder="请输入关键字" type="text" required>
                <input name="submit2" type="submit" value="搜索" class="button">
            </form>
        </div>
        <div class="search_list">
            <nav>
                <ul>
                    <li>热门内容快速浏览</li>
                    <li>
                        <a href="browse.php?theme=scenery&country=placeholder&city=placeholder&page=0&submit1=筛+选">自然风景</a>
                    </li>
                    <li><a href="browse.php?theme=city&country=placeholder&city=placeholder&page=0&submit1=筛+选">城市建筑</a>
                    </li>
                    <li>
                        <a href="browse.php?theme=people&country=placeholder&city=placeholder&page=0&submit1=筛+选">唯美人像</a>
                    </li>
                    <li>
                        <a href="browse.php?theme=animal&country=placeholder&city=placeholder&page=0&submit1=筛+选">自然动物</a>
                    </li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门国家快速浏览</li>
                    <li><a href="browse.php?theme=placeholder&country=China&city=placeholder&page=0&submit1=筛+选">中国</a>
                    </li>
                    <li><a href="browse.php?theme=placeholder&country=Italy&city=placeholder&page=0&submit1=筛+选">意大利</a>
                    </li>
                    <li><a href="browse.php?theme=placeholder&country=Japan&city=placeholder&page=0&submit1=筛+选">日本</a>
                    </li>
                    <li>
                        <a href="browse.php?theme=placeholder&country=United+States&city=placeholder&page=0&submit1=筛+选">美国</a>
                    </li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门城市快速浏览</li>
                    <li><a href="browse.php?theme=placeholder&country=China&city=Shanghai&page=0&submit1=筛+选">上海</a>
                    </li>
                    <li>
                        <a href="browse.php?theme=placeholder&country=United+States&city=New+York&page=0&submit1=筛+选">纽约</a>
                    </li>
                    <li><a href="browse.php?theme=placeholder&country=France&city=Paris&page=0&submit1=筛+选">巴黎</a></li>
                    <li>
                        <a href="browse.php?theme=placeholder&country=United+Kingdom&city=London&page=0&submit1=筛+选">伦敦</a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <main>
        <p>图库浏览</p>
        <form action="browse.php" class="selects" method="post">
            <select name="theme">
                <option value="placeholder" selected>按主题筛选</option>
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
            <input name="submit1" type="submit" value="筛 选">
        </form>
        <ul>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1'])) {
                $_SESSION['theme'] = $_POST['theme'];
                $_SESSION['country'] = $_POST['country'];
                $_SESSION['city'] = $_POST['city'];
                $_SESSION['request'] = 'submit1';
                $_SESSION['page'] = 0;
                $_SESSION['sum'] = 0;
                search_first();
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2'])) {
                $_SESSION['title'] = $_POST['title'];
                $_SESSION['request'] = 'submit2';
                $_SESSION['page'] = 0;
                $_SESSION['sum'] = 0;
                fuzzyQueryFirst();
            }
            if (isset($_GET['submit1'])) {
                $_SESSION['request'] = 'submit1';
                if (isset($_GET['country'])) {
                    $_SESSION['country'] = $_GET['country'];
                }
                if (isset($_GET['city'])) {
                    $_SESSION['city'] = $_GET['city'];
                }
                if (isset($_GET['theme'])) {
                    $_SESSION['theme'] = $_GET['theme'];
                }
                if (isset($_GET['page'])) {
                    $_SESSION['page'] = $_GET['page'];
                    if ($_SESSION['page'] == 0) {
                        search_first();
                    } else {
                        search_again();
                    }
                } else {
                    search_first();
                }
            }
            if (isset($_GET['submit2'])) {
                $_SESSION['request'] = 'submit2';
                if (isset($_GET['title'])) {
                    $_SESSION['title'] = $_GET['title'];
                }
                if (isset($_GET['page'])) {
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
        </ul>
    </main>
</div>


<div id="pagination" class="pagination">
    <ul>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit1'])) {
            creatPageNumber("browse");
        }

        if (isset($_GET['submit1'])) {
            creatPageNumber("browse");
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit2'])) {
            creatPageNumber("browse");
        }

        if (isset($_GET['submit2'])) {
            creatPageNumber("browse");
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