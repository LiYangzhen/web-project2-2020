<?php
session_start();
require_once('src/rear_end/config.php');

function generate($result)
{
    while ($result->rowCount() > 0 && $row = $result->fetch()) {
        echo '<li class="thumbnail" title="' . $row['Title'] . '">
                <a href="src/front_end/details.php?imageid=' . $row['ImageID'] . '">
                    <div class="img-box">
                        <img src="src/travel-images/small/' . $row['PATH'] . '" alt="图片" width="260" height="200">
                    </div>
                    <div><h3>' . $row['Title'] . '</h3>
                        <p>' . $row['Description'] . '</p>
                    </div>
                </a>
            </li>';
    }
}

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
        <a href="src/front_end/search.php" class="link">搜索</a>
        <a href="src/front_end/browse.php" class="link">阅览</a>
        <?php
        if (isset($_SESSION['id'])) {
            echo '<div class="dropdown-menu">
                      <a href="">个人中心</a>
                      <ul>
                <li class="menu_item"><a href="src/front_end/upload.php" class="upload">上传图片</a></li>
                <li class="menu_item"><a href="src/front_end/my_photos.php" class="my-pictures">我的图片</a></li>
                <li class="menu_item"><a href="src/front_end/my_favourite.php" class="collections">我的收藏</a></li>
                 <li class="menu_item"><a href="src/rear_end/logout.php" class="logout">退出登录</a></li>
                </ul>
                </div>';
        } else {
            echo '<a href="src/front_end/login.php" class="link"></span>登录</a>';
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
    <a href="src/rear_end/refresh.php">刷 新</a>
</aside>
<main>
    <h2>免费精美图片</h2>
    <section class="imgGroup">
        <ul>
            <?php
            try {
                if (!$_SESSION['refresh']) {
                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                    $sql = "select travelimagefavor.ImageID ,travelimage.PATH,travelimage.Title,travelimage.Description ,count(*) from travelimagefavor JOIN travelimage ON travelimage.ImageID=travelimagefavor.ImageID group by travelimage.ImageID order by count(*) DESC LIMIT 10";
                    $result = $pdo->query($sql);
                    generate($result);
                } else {
                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                    $sql = "SELECT ImageID ,PATH,Title,Description FROM travelimage ORDER BY RAND() LIMIT 10";
                    $result = $pdo->query($sql);
                    generate($result);
                }
                $pdo = null;
            } catch (PDOException $e) {
                $pdo = null;
                echo '<script>alert("服务器错误！")</script>';
            }
            ?>
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
                        <a class="weChat" href="src/front_end/myWeChat.php"> </a>
                        <a class="qq" href="src/front_end/myQQ.php"> </a>
                        <a class="github" href="https://github.com/LiYangzhen/web-project2-2020"> </a>
                        <a class="email" href="mailto:193002010059@fudan.edu.cn"> </a>
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
                    <a class="link" href="browse.php?theme=building&country=placeholder&city=placeholder&page=0&submit1=筛+选">自然动物</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=building&country=placeholder&city=placeholder&page=0&submit1=筛+选">建筑美学</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=scenery&country=placeholder&city=placeholder&page=0&submit1=筛+选">山水植物</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=people&country=placeholder&city=placeholder&page=0&submit1=筛+选">唯美人像</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=wonder&country=placeholder&city=placeholder&page=0&submit1=筛+选">世界奇观</a>
                </li>
                <li>
                    <a class="link" href="browse.php?page=0&title=壁纸&submit2=搜索">精美壁纸</a>
                </li>
            </ul>
        </li>
        <!--        第三列-->
        <li>
            <ul class="footer__body__column">
                <li>
                    <div class="footer__body__column__title">国家</div>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=China&city=placeholder&page=0&submit1=筛+选">中国</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=United+States&city=placeholder&page=0&submit1=筛+选">美国</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=United+Kingdom&city=placeholder&page=0&submit1=筛+选">英国</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=Italy&city=placeholder&page=0&submit1=筛+选">意大利</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=Canada&city=placeholder&page=0&submit1=筛+选">加拿大</a>
                </li>
                <li>
                    <a class="link" href="browse.php?theme=placeholder&country=France&city=placeholder&page=0&submit1=筛+选">法国</a>
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