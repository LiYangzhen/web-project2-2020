<?php
session_start();
require_once('config.php');

function browse_generate($result)
{
    $i = 0;
    while (($row = $result->fetch()) && $i < 18) {
        echo '<li class="thumbnail" title="' . $row['Title'] . '">
                <a href="details.php?imageid=' . $row['ImageID'] . '">
                    <div class="img-box">
                        <img src="../travel-images/small/' . $row['PATH'] . '" alt="图片" width="150" height="150">
                    </div>
                </a>
            </li>';
        $i++;
    }
}

function myFav_generate($result)
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
                     <a href="../rear_end/uncollect.php?imageid=' . $row['ImageID'] . '">取消收藏</a>
                </div>
            </li>';
        $i++;
    }
}

function myPhoto_generate($result)
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


function search_generate($result)
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

function details_generate()
{
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
                <li class="collection_number"><span>' . $favor . '</span><a href="../rear_end/collect.php?imageid=' . $_GET['imageid'] . '&collected=' . $collected . '" class="' . $collectedClass . ' collect"></a></li>
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
}

function search_first()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        if ($_SESSION['theme'] != "placeholder" && $_SESSION['theme'] != null && $_SESSION['country'] == "placeholder") {
            $sql = 'SELECT ImageID,PATH,Title FROM travelimage WHERE Content=:theme ORDER BY ImageID';
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':theme', $_SESSION['theme']);
            $statement->execute();
            if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
                browse_generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif (($_SESSION['theme'] == "placeholder" || $_SESSION['theme'] == null) && $_SESSION['country'] != "placeholder") {
            if ($_SESSION['city'] == "placeholder" || $_SESSION['city'] == null) {
                $sql = 'SELECT ImageID,PATH,Title FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE CountryName=:countryname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_SESSION['country']);
            } else {
                $sql = 'SELECT ImageID,PATH,Title FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE AsciiName LIKE :cityname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_SESSION['city'] . "%");
            }
            $statement->execute();
            if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
                browse_generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif ($_SESSION['theme'] != "placeholder" && $_SESSION['theme'] != null && $_SESSION['country'] != "placeholder") {
            if ($_SESSION['city'] == "placeholder" || $_SESSION['city'] == null) {
                $sql = 'SELECT ImageID,PATH,Title FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE Content=:theme AND CountryName=:countryname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_SESSION['country']);
            } else {
                $sql = 'SELECT ImageID,PATH,Title FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE Content=:theme AND AsciiName LIKE :cityname ORDER BY ImageID';
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_SESSION['city'] . "%");
            }
            $statement->bindValue(':theme', $_SESSION['theme']);
            $statement->execute();
            if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
                browse_generate($statement);
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
        if ($_SESSION['theme'] != "placeholder" && $_SESSION['theme'] != null && $_SESSION['country'] == "placeholder") {
            $num = $_SESSION['page'] * 18;
            $sql = "SELECT ImageID,PATH,Title FROM travelimage WHERE Content=:theme ORDER BY ImageID LIMIT $num,18";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':theme', $_SESSION['theme']);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                browse_generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif (($_SESSION['theme'] == "placeholder" || $_SESSION['theme'] == null) && $_SESSION['country'] != "placeholder") {
            $num = $_SESSION['page'] * 18;
            if ($_SESSION['city'] == "placeholder" || $_SESSION['city'] == null) {
                $sql = "SELECT ImageID,PATH,Title FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE CountryName=:countryname ORDER BY ImageID LIMIT $num,18";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_SESSION['country']);
            } else {
                $sql = "SELECT ImageID,PATH,Title FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE AsciiName LIKE :cityname ORDER BY ImageID LIMIT $num,18";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_SESSION['city'] . "%");
            }
            $statement->execute();
            if ($statement->rowCount() > 0) {
                browse_generate($statement);
            } else {
                echo '<h4>筛选无结果</h4>';
            }
        } elseif ($_SESSION['theme'] != "placeholder" && $_SESSION['theme'] != null && $_SESSION['country'] != "placeholder") {
            $num = $_SESSION['page'] * 18;
            if ($_SESSION['city'] == "placeholder" || $_SESSION['city'] == null) {
                $sql = "SELECT ImageID,PATH,Title FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE Content=:theme AND CountryName=:countryname ORDER BY ImageID LIMIT $num,18";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':countryname', $_SESSION['country']);
            } else {
                $sql = "SELECT ImageID,PATH,Title FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE Content=:theme AND AsciiName LIKE :cityname ORDER BY ImageID LIMIT $num,18";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':cityname', "%" . $_SESSION['city'] . "%");
            }
            $statement->bindValue(':theme', $_SESSION['theme']);
            $statement->execute();
            if ($statement->rowCount() > 0) {
                browse_generate($statement);
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
            search_generate($statement);
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
            search_generate($statement);
        } else {
            echo '<h4>搜索无结果</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        $pdo = null;
        echo '<script>alert("错误：无法连接到服务器")</script>';
    }
}

function generateFav()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $num = $_SESSION['page'] * 18;
        $max = $_SESSION['showNum'];
        $sql = "SELECT travelimagefavor.ImageID,PATH,Title,Description FROM travelimagefavor JOIN travelimage ON travelimage.ImageID=travelimagefavor.ImageID WHERE travelimagefavor.UID=:id LIMIT $num,$max";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION['id']);
        $statement->execute();
        if (($_SESSION['sum'] = $statement->rowCount()) > 0) {
            myFav_generate($statement);
        } else {
            echo '<h4>您还没有收藏的图片</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo '<h4>服务器连接错误</h4>';
    }
}

function countFavSum()
{
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $sql = 'SELECT count(*) FROM travelimagefavor WHERE travelimagefavor.UID=:id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION['id']);
        $statement->execute();
        $row = $statement->fetch();
        $_SESSION['sum'] = $row[0];
        $_SESSION['page'] = 0;
        generateFav();
        $pdo = null;
    } catch (PDOException $e) {
        echo '<h4>服务器连接错误</h4>';
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
            myPhoto_generate($statement);
        } else {
            echo '<h4>您还未上传过图片</h4>';
        }
        $pdo = null;
    } catch (PDOException $e) {
        echo '<h4>服务器连接错误</h4>';
    }
}

function countMySum()
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