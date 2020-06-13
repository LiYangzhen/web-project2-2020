<?php
session_start();
require_once('config.php');

function generate($result)
{
    $i = 0;
    while (($row = $result->fetch()) && $i < 18) {
        echo '<li class="thumbnail">
                <a href="details.php?imageid=' . $row['ImageID'] . '">
                    <div class="img-box">
                        <img src="../travel-images/small/' . $row['PATH'] . '" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>';
        $i++;
    }
}

function getURL(){
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $url;
}

function changePage($num)
{
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (preg_match_all("&page=[0-9]+", $url)) {
        $a = preg_match_all("/page=[0-9]+/", "$url");
        $url = str_replace(join("", $a), "page=" . $num, $url);
    }
    if (preg_match_all("&sum=[0-9]+", $url)) {
        $a = preg_match_all("/sum=[0-9]+/", "$url");
        $url = str_replace(join("", $a), "sum=" . $_GET['sum'], $url);
    }
    if (!preg_match_all("&page=[0-9]+", $url)) {
        $url = $url . "&page=" . $num;
    }
    if (!preg_match_all("&sum=[0-9]+", $url)) {
        $url = $url . "&sum=" . $_GET['sum'];
    }
}

function search_first()
{
    if (!$_GET['page']) {
        $_GET['page'] = 0;
    }
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        if ($_GET['theme'] != "placeholder" && $_GET['theme'] != null && $_GET['country'] == "placeholder") {
            $sql = 'SELECT ImageID,PATH FROM travelimage WHERE Content=:theme ORDER BY ImageID';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':theme', $_GET['theme']);
            $statement->execute();
            if ($_GET['sum'] = $statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif (($_GET['theme'] == "placeholder" || $_GET['theme'] == null) && $_GET['country'] != "placeholder") {
            if ($_GET['city'] == "placeholder" || $_GET['city'] == null) {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE CountryName=:countryname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_GET['country']);
            } else {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE AsciiName LIKE :cityname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_GET['city'] . "%");
            }
            $statement->execute();
            if ($_GET['sum'] = $statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif ($_GET['theme'] != "placeholder" && $_GET['theme'] != null && $_GET['country'] != "placeholder") {
            if ($_GET['city'] == "placeholder" || $_GET['city'] == null) {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE Content=:theme AND CountryName=:countryname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':theme', $_GET['theme']);
                $statement->bindValue(':countryname', $_GET['country']);
            } else {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE Content=:theme AND AsciiName LIKE :cityname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':theme', $_GET['theme']);
                $statement->bindValue(':cityname', "%" . $_GET['city'] . "%");
            }
            $statement->execute();
            if ($_GET['sum'] = $statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } else {
            echo '<h4>请选择筛选条件</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo '<script>alert("错误：无法连接到服务器")</script>';
    }
}

function search_again()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        if ($_GET['theme'] != "placeholder" && $_GET['theme'] != null && $_GET['country'] == "placeholder") {
            $sql = 'SELECT ImageID,PATH FROM travelimage WHERE Content=:theme ORDER BY ImageID LIMIT :num1,18';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':theme', $_GET['theme']);
            $statement->bindValue(':num1', $_GET['page'] * 18);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif (($_GET['theme'] == "placeholder" || $_GET['theme'] == null) && $_GET['country'] != "placeholder") {
            if ($_GET['city'] == "placeholder" || $_GET['city'] == null) {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE CountryName=:countryname ORDER BY ImageID LIMIT :num1,18';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_GET['country']);
            } else {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE AsciiName LIKE :cityname ORDER BY ImageID LIMIT :num1,18';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_GET['city'] . "%");
            }
            $statement->bindValue(':num1', $_GET['page'] * 18);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif ($_GET['theme'] != "placeholder" && $_GET['theme'] != null && $_GET['country'] != "placeholder") {
            if ($_GET['city'] == "placeholder" || $_GET['city'] == null) {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE Content=:theme AND CountryName=:countryname ORDER BY ImageID LIMIT :num1,18';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':theme', $_GET['theme']);
                $statement->bindValue(':countryname', $_GET['country']);
            } else {
                $sql = 'SELECT ImageID,PATH FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE Content=:theme AND AsciiName LIKE :cityname ORDER BY ImageID LIMIT :num1,18';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':theme', $_GET['theme']);
                $statement->bindValue(':cityname', "%" . $_GET['city'] . "%");
            }
            $statement->bindValue(':num1', $_GET['page'] * 18);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } else {
            echo '<h4>请选择筛选条件</h4>';
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
    <a href="javascript:toTop()" id="toTop"><span>︿</span><span>Top</span></a>
</aside>

<div class="browse_box">
    <aside class="search_nav">
        <div class="search_bar">
            <form method="get" action="">
                <input placeholder="请输入关键字" type="text">
                <button type="submit" onclick="alert('?????')">搜索</button>
            </form>
        </div>
        <div class="search_list">
            <nav>
                <ul>
                    <li>热门内容快速浏览</li>
                    <li><a href="browse.php?theme=scenery&country=placeholder&city=placeholder&submit1=筛+选">自然风景</a>
                    </li>
                    <li><a href="browse.php?theme=city&country=placeholder&city=placeholder&submit1=筛+选">城市建筑</a></li>
                    <li><a href="browse.php?theme=people&country=placeholder&city=placeholder&submit1=筛+选">唯美人像</a></li>
                    <li><a href="browse.php?theme=animal&country=placeholder&city=placeholder&submit1=筛+选">自然动物</a></li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门国家快速浏览</li>
                    <li><a href="browse.php?theme=placeholder&country=China&city=placeholder&submit1=筛+选">中国</a></li>
                    <li><a href="browse.php?theme=placeholder&country=Italy&city=placeholder&submit1=筛+选">意大利</a></li>
                    <li><a href="browse.php?theme=placeholder&country=Japan&city=placeholder&submit1=筛+选">日本</a></li>
                    <li><a href="browse.php?theme=placeholder&country=United+States&city=placeholder&submit1=筛+选">美国</a>
                    </li>
                </ul>
            </nav>
            <nav>
                <ul>
                    <li>热门城市快速浏览</li>
                    <li><a href="browse.php?theme=placeholder&country=China&city=Shanghai&submit1=筛+选">上海</a></li>
                    <li><a href="browse.php?theme=placeholder&country=United+States&city=New+York&submit1=筛+选">纽约</a>
                    </li>
                    <li><a href="browse.php?theme=placeholder&country=France&city=Paris&submit1=筛+选">巴黎</a></li>
                    <li><a href="browse.php?theme=placeholder&country=United+Kingdom&city=London&submit1=筛+选">伦敦</a>
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
                <option value="scenery">风景</option>
                <option value="people">人物</option>
                <option value="nature">自然</option>
                <option value="building">建筑</option>
                <option value="food">美食</option>
                <option value="universe">宇宙</option>
                <option value="geometry">几何</option>
            </select>
            <select name="country" id="country" onchange="addOption()">
                <option value="placeholder" selected>按国家筛选</option>
                <!--                --><?php
                //                try {
                //                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                //                    $sql = 'SELECT ISO,CountryName FROM geocountries ORDER BY ISO';
                //                    $statement = $pdo->query($sql);
                //                    while ($row = $statement->fetch()) {
                //                        echo '<option value="' . $row['ISO'] . '">' . $row['CountryName'] . '</option>';
                //                    }
                //
                //                    $sql = 'SELECT CountryCodeISO,AsciiName,GeoNameID FROM geocities ORDER BY CountryCodeISO';
                //                    $statement = $pdo->query($sql);
                //                    while ($row = $statement->fetch()) {
                //                        $iso = $row['CountryCodeISO'];
                //                        array_push($city[$iso], $row);
                //                    }
                //                } catch (PDOException $e) {
                //                    echo '<script>alert("错误：无法连接到服务器")</script>';
                //                }
                //                ?>
                <!--                <script>-->
                <!--                    --><?php //for($j = 0;$j < $num1;$j++)
                //                    {
                //                    ?>
                // let country[<?php //echo $country[$j]?>//] = ["<? //echo $city[$country[$j]]['GeoNameID'];?>//",
                "<? //echo $city[$country[$j]]['AsciiName'];?>//"];
                // <? //}
                //                    ?>
                // function changeLocation(ISO) {
                // let select2 = document.getElementById("city");
                // let select1 = document.getElementById("country").value;
                // select2.length = 0; //清空一下市级菜单
                // if (select1 !== 'placeholder') {
                // select2.add(new Option("请选择城市", "请选择城市"), null);
                // for (var i in country[select1]) {
                // select2.add(new Option(i['AsciiName'], i['GeoNameID']), null);
                // }
                // } else {
                // select2.length = 0;
                // select2.add(new Option("按城市筛选", "按城市筛选"), null);
                // }
                // }
                //
                <!--//                </script>-->
            </select>
            <select name="city" id="city"></select>
            <input name="submit1" type="submit" value="筛 选">
        </form>
        <ul>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                echo (string)(getURL()."&theme=".$_POST('theme')."&country=".$_POST('country')."&city=".$_POST('city')."&submit1=".$_POST('submit1')."&page=1");
//                header(getURL()."&theme=".$_POST('theme')."&country=".$_POST('country')."&city=".$_POST('city')."&submit1=".$_POST('submit1')."&page=1");
            }
            if (isset($_GET['submit1'])) {
                if (!isset($_GET['page'])) {
                    search_first();
                } else {
                    search_again();
                }
            }
            ?>
        </ul>
    </main>
</div>


<div id="pagination" class="pagination">
    <ul>
        <?php
        if (isset($_GET['sum'], $_GET['page']) && $_GET['sum'] / 18 < 5 && isset($_GET['sum'], $_GET['page']) && $_GET['sum'] / 18 > 1) {
            $total = (($_GET['sum'] / 18) + 1) % 1;
            if ($_GET['page'] > 1) {
                echo '<a href="' . changePage(0) . '">首页</a>
                <a href="' . changePage($_GET['page'] - 1) . '"><</a>';
            }
            for ($i = 0; $i < $total; $i++) {
                echo '<a href="' . changePage($i) . '" class="' . isActive($i) . '">1</a>';
            }
            if ($_GET['page'] < $total) {
                echo '<a href="' . changePage($_GET['page'] + 1) . '" class="' . isActive(1) . '>></a>
                <a href="' . changePage($total) . ' ">尾页<a/>';
            }
        } elseif (isset($_GET['sum'], $_GET['page']) && $_GET['sum'] / 18 > 5) {
            $total = (($_GET['sum'] / 18) + 1) % 1;
            if ($_GET['page'] > 1) {
                echo '<a href="' . changePage(0) . '">首页</a>
                <a href="' . changePage($_GET['page'] - 1) . '"><</a>';
            }
            for ($i = $_GET['page'] - 2; $i <= $i = $_GET['page'] + 2; $i++) {
                echo '<a href="' . changePage($i) . '" class="' . isActive($i) . '">1</a>';
            }
            if ($_GET['page'] < $total) {
                echo '<a href="' . changePage($_GET['page'] + 1) . '" class="' . isActive(1) . '>></a>
                <a href="' . changePage($total) . ' ">尾页<a/>';
            }
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