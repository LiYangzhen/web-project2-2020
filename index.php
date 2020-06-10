<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>ImgShow-图片分享平台 ฅ( ̳• ◡ • ̳)ฅ</title>
    <link rel="icon" href="src/image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="src/css/base.css" rel="stylesheet" type="text/css">
    <link href="src/css/home.css" rel="stylesheet" type="text/css">
    <script src="src/js/prefixfree.min.js" rel="script" type="text/javascript"></script>
</head>

<body class="home">
<!--页眉-->
<header class="mainImg">
    <div class="scroll-img-box">
        <img src="src/travel-images/1080p.jpg" id="scroll-img1" alt="1">
        <img src="src/travel-images/240123409634303026.jpg" id="scroll-img2" alt="2">
        <img src="src/travel-images/202820355921412098%20(1).jpg" id="scroll-img3" alt="3">
    </div>
    <nav class="banner">
        <a href="index.php" class="logo">
            <img src="src/image/logo.svg" alt="logo">
            <p>ImgShow</p>
        </a>
        <a href="index.php" class="highlight link">首页</a>
        <a href="src/html/search.html" class="link">搜索</a>
        <a href="src/html/browse.html" class="link">阅览</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<div class="dropdown-menu">
                      <a href="">个人中心</a>
                      <ul>
                <li class="menu_item"><a href="src/html/upload.html" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="src/html/my_photos.html" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="src/html/my_favourite.html" class="collections">我的收藏</a></li>
                </ul>
                </div>';
        } else {
            echo '<a href="src/html/login.php" class="link">登录</a>';
        }
        ?>

    </nav>
    <h1>精彩的生活、宏伟的建筑与绚丽的风景在这里，一同分享</h1>
    <div class="scroll radius">
        <span class="active"></span>
        <span></span>
        <span></span>
    </div>
    <a href="javascript:scroll(-1)" class="arrowhead-left" id="al"><</a>
    <a href="javascript:scroll(1)" class="arrowhead-right" id="ar">></a>
</header>
<!--内容-->
<aside id="sidebar" class="sidebar">
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
    <a onclick="alert('图片已刷新')">刷 新</a>
</aside>
<main>
    <h2>免费精美图片</h2>

    <section class="imgGroup">
        <ul>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/5855174537.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>富强、民主、文明、和谐、自由 、平等、公正、法治、爱国、敬业、诚信、友善</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/6114850721.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>一个幽灵，共产主义的幽灵，在欧洲大陆徘徊。为了对这个幽灵进行神圣的围剿，旧欧洲的一切势力，
                            教皇和沙皇、梅特涅和基佐、法国的激进派和德国的警察，都联合起来了。
                        </p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/8710247776.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>中国是世界上历史最悠久的国家之一。中国各族人民共同创造了光辉灿烂的文化，具有光荣的革命传统。</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/9493997865.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>“我志愿加入中国共-产-党,拥护党的纲领,遵守党的章程,履行党员义务;执行党的决定,严守党的纪律,
                            保守党的秘密,对党忠诚,积极工作,为共-产主义奋斗终身,随时准备为党和人民牺牲一切,永不叛党。</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/222222.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>我志愿加入中国共产主义青年团,坚决拥护中国共产党的领导,遵守团的章程,执行团的决议,履行团员义务,
                            严守团的纪律,勤奋学习,积极工作,吃苦在前,享受在后,为共产主义事业而奋斗。</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/8645912379.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>"Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/6592317633.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>腾飞腾飞，振翅高飞</p>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="src/html/details.html">
                    <div class="img-box">
                        <img src="src/travel-images/small/6115548152.jpg" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>Title</h3>
                        <p>起来，饥寒交迫的奴隶！
                            起来，全世界受苦的人！
                            满腔的热血已经沸腾，
                            要为真理而斗争！
                        </p>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</main>

<!--页脚-->
<footer>
    <!--    导航阵列-->
    <ul class="footer__body">
        <!--        第一列-->
        <li>
            <ul class="footer__body__column">
                <li>
                    <div class="footer__body__column__title">ImgShow</div>
                </li>
                <li>
                    <a class="link" href="">关于</a>
                </li>
                <li>
                    <a class="link" href="">常见问题解答</a>
                </li>
                <li>
                    <a class="link" href="">成为爸爸</a>
                </li>
                <li>
                    <a class="link" href="">与ImgShow合作</a>
                </li>
                <li>
                    <a class="link" href="">开发人员</a>
                </li>
                <li>
                    <div class="icon-button-group">
                        <a class="weChat" href=""> </a>
                        <a class="qq" href=""> </a>
                        <a class="github" href=""> </a>
                        <a class="email" href=""> </a>
                    </div>
                </li>
            </ul>
        </li>
        <!--        第二列-->
        <li>
            <ul class="footer__body__column">
                <li>
                    <div class="footer__body__column__title">免费素材照片</div>
                </li>
                <li>
                    <a class="link" href="">黑白摄影</a>
                </li>
                <li>
                    <a class="link" href="">建筑美学</a>
                </li>
                <li>
                    <a class="link" href="">山水植物</a>
                </li>
                <li>
                    <a class="link" href="">校园风光</a>
                </li>
                <li>
                    <a class="link" href="">很酷的桌面</a>
                </li>
                <li>
                    <a class="link" href="">精美高清壁纸</a>
                </li>
            </ul>
        </li>
        <!--        第三列-->
        <li>
            <ul class="footer__body__column">
                <li>
                    <div class="footer__body__column__title">壁纸</div>
                </li>
                <li>
                    <a class="link" href="">银河壁纸</a>
                </li>
                <li>
                    <a class="link" href="">锁屏桌面</a>
                </li>
                <li>
                    <a class="link" href="">写真壁纸</a>
                </li>
                <li>
                    <a class="link" href="">4k 桌面</a>
                </li>
                <li>
                    <a class="link" href="">动态壁纸</a>
                </li>
                <li>
                    <a class="link" href="">手机桌面</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--    页脚横幅-->
    <div class="footer__nav">
        <ul>
            <li>
                <a class="footer__navbar__logo" href="index.php">
                    <img src="src/image/logo.svg" alt="logo"/>
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
                    <img src="src/image/CN.svg" alt="Chinese">
                    <p>简体中文</p></div>
            </li>
        </ul>
    </div>
</footer>
<style>
    @keyframes scroll-out-left {
        from {
            left: 0;
            opacity: 1;
        }

        99% {
            opacity: 1;
        }

        to {
            left: -100%;
            opacity: 0;
        }
    }

    @keyframes scroll-out-right {
        from {
            left: 0;
            opacity: 1;
        }

        99% {
            opacity: 1;
        }

        to {
            left: 100%;
            opacity: 0;
        }
    }

    @keyframes scroll-in-left {
        from {
            left: 100%;
            opacity: 0;
        }

        1% {
            opacity: 1;
        }

        to {
            left: 0;
            opacity: 1;
        }
    }

    @keyframes scroll-in-right {
        from {
            left: -100%;
            opacity: 0;
        }

        1% {
            opacity: 1;
        }

        to {
            left: 0;
            opacity: 1;
        }
    }</style>
<script src="src/js/UIscript.js" rel="script" type="text/javascript"></script>
</body>
</html>