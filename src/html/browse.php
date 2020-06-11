<?php
session_start();
require_once('config.php');
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
    <a href="javascript:toTop()" id="toTop" ><span>︿</span><span>Top</span></a>
</aside>

<div class="browse_box">
    <aside class="search_nav">
        <div class="search_bar">
            <form method="post" action="">
                <input placeholder="请输入关键字" type="text">
                <button type="submit" onclick="alert('?????')">搜索</button>
            </form>
        </div>
        <div class="search_list">
            <nav>
                <ul>
                    <li>热门内容快速浏览</li>
                    <li><a href="#" onclick="alert('你还想看啥？')">自然风景</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">城市建筑</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">唯美人像</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">自然动物</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">世界奇观</a></li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门国家快速浏览</li>
                    <li><a href="#" onclick="alert('你还想看啥？')">中国</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">意大利</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">日本</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">美国</a></li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门城市快速浏览</li>
                    <li><a href="#" onclick="alert('你还想看啥？')">上海</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">纽约</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">巴黎</a></li>
                    <li><a href="#" onclick="alert('你还想看啥？')">伦敦</a></li>
                </ul>
            </nav>
        </div>
    </aside>
    <main>
        <p>图库浏览</p>
        <div class="selects">
            <select>
                <option value="placeholder" selected disabled style="display: none;">按主题筛选</option>
                <option value="people">人物</option>
                <option value="nature">自然</option>
                <option value="building">建筑</option>
                <option value="food">美食</option>
                <option value="universe">宇宙</option>
                <option value="geometry">几何</option>
            </select>
            <select id="country" onchange="addOption()">
                <option selected>按国家筛选</option>
            </select>
            <select id="city"></select>
        </div>
        <ul>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/5855174537.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6114850721.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/8710247776.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/9493997865.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/222222.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/8645912379.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6592317633.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6115548152.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/9498388516.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/9498368556.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/5856616479.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/5855729828.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6592902825.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/8710247776.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6115548152.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/5855174537.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/9498368556.jpg" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>
        </ul>
    </main>
</div>
<div id="pagination" class="pagination">
    <ul>
        <li>首页</li>
        <li><</li>
        <li class="active">1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
        <li>5</li>
        <li>></li>
        <li>尾页</li>
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