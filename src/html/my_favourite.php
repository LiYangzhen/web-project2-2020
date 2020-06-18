<?php
session_start();
require_once('config.php');

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
                <div class="editBox">
                     <a href="uncollect.php?imageid=' . $row['ImageID'] . '">取消收藏</a>
                </div>
            </li>';
        $i++;
    }
}

function generateFav()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql = 'SELECT travelimagefavor.ImageID,PATH,Title,Description FROM travelimagefavor JOIN travelimage ON travelimage.ImageID=travelimagefavor.ImageID WHERE travelimagefavor.UID=:id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION['id']);
        $statement->execute();
        if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
            generate($statement);
        } else {
            echo '<h4>您还没有收藏的图片</h4>';
        }
    }catch (PDOException $e){
        echo '<h4>服务器连接错误</h4>';
    }
}
?>


<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>我的收藏-ImgShow</title>
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
                <li class="menu_item"><a href="my_photos.php" class="my-pictures">我的图片</a></li>
                <li class="menu_item highlight"><a href="my_favourite.php" class="collections">我的收藏</a></li>
                <li class="menu_item"><a href="login.php" class="log-in">登录</a></li>
            </ul>
        </div>
    </nav>
</header>

<aside id="sidebar" class="sidebar">
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
</aside>

<main>
    <h2>我的收藏</h2>
    <section class="imgGroup">
        <ul>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/5855174537.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>富强、民主、文明、和谐、自由 、平等、公正、法治、爱国、敬业、诚信、友善</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6114850721.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>一个幽灵，共产主义的幽灵，在欧洲大陆徘徊。为了对这个幽灵进行神圣的围剿，旧欧洲的一切势力，
                            教皇和沙皇、梅特涅和基佐、法国的激进派和德国的警察，都联合起来了。
                        </p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/8710247776.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>中国是世界上历史最悠久的国家之一。中国各族人民共同创造了光辉灿烂的文化，具有光荣的革命传统。</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/9493997865.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>“我志愿加入中国共-产-党,拥护党的纲领,遵守党的章程,履行党员义务;执行党的决定,严守党的纪律,
                            保守党的秘密,对党忠诚,积极工作,为共-产主义奋斗终身,随时准备为党和人民牺牲一切,永不叛党。</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/222222.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>我志愿加入中国共产主义青年团,坚决拥护中国共产党的领导,遵守团的章程,执行团的决议,履行团员义务,
                            严守团的纪律,勤奋学习,积极工作,吃苦在前,享受在后,为共产主义事业而奋斗。</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/8645912379.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>"Lorem ipsum dolor sit amet, consectetaur adipisicing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat."</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6592317633.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>腾飞腾飞，振翅高飞</p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
            <li class="thumbnail">
                <a href="details.php">
                    <div class="img-box">
                        <img src="../travel-images/small/6115548152.jpg" alt="图片">
                    </div>
                    <div><h3>Title</h3>
                        <p>起来，饥寒交迫的奴隶！
                            起来，全世界受苦的人！
                            满腔的热血已经沸腾，
                            要为真理而斗争！
                        </p>
                        <form>
                            <input type="button" value="删除" name="delete" onclick="alert('已删除')">
                        </form>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</main>

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