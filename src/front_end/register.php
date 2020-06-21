<?php
session_start();
require_once(dirname(dirname(__FILE__)).'/rear_end/config.php');
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>账户注册-ImgShow</title>
    <link rel="icon" href="../image/logo.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="../css/base.css" rel="stylesheet" type="text/css">
    <link href="../css/input_form.css" rel="stylesheet" type="text/css">
    <script src="../js/prefixfree.min.js" rel="script" type="text/javascript"></script>
</head>

<body>
<main>
    <div class="form_part register">
        <form action="register.php" method="post">
            <label>用户名
                <input class="input_box" name="username" type="text" placeholder="不少于8位不高于16位的数字、字母、下划线"
                       pattern="^[a-zA-Z0-9_-]{4,16}$"
                       required>
            </label>
            <label>邮箱<input class="input_box" name="email" type="email" placeholder="example: ImgShow@fudan.edu.cn"
                            pattern="^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$"
                            required="required">
            </label>
            <label>密码<input class="input_box" name="password1" type="password" placeholder="不少于8位不高于16位的数字、字母"
                            pattern="^[0-9A-Za-z]{8,16}$" id="password1" required="required" onkeyup="validate()">
            </label>
            <label>确认密码<input class="input_box" name="password2" type="password" placeholder="请保持两次输入密码的相同"
                              pattern="^[0-9A-Za-z]{8,16}$"
                              id="password2" required="required" onkeyup="validate()"><span id="check-result"></span>
            </label>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username_pattern = "^[a-zA-Z0-9_-]{4,16}$";
                $email_pattern = "^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$";
                $password_pattern = "^[0-9A-Za-z]{8,16}$";
                try {
                    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                    //very simple (and insecure) check of valid credentials.
                    $sql = "SELECT * FROM Traveluser WHERE UserName=:user";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(':user', $_POST['username']);
                    $statement->execute();

                    if ($statement->rowCount() == 0) {
                        $statement = null;
                        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
                        $sql = "INSERT INTO traveluser (Email,UserName,Pass,State,DateJoined,DateLastModified) VALUES (:email,:username,:password,:state,:datejoined,:datelastmodified)";
                        $statement = $pdo->prepare($sql);
                        $statement->bindValue(':email', $_POST['email']);
                        $statement->bindValue(':username', $_POST['username']);
                        $statement->bindValue(':password', $_POST['password1']);
                        $statement->bindValue(':state', '1');
                        $statement->bindValue(':datejoined', date("Y-m-d H:i:s"));
                        $statement->bindValue(':datelastmodified', date("Y-m-d H:i:s"));
                        $statement->execute();

                        if ($statement) {
                            $pdo=null;
                            header("location: login.php");
                        } else {
                            echo "<p style='color: red'>错误：注册失败</p>";
                        }
                        $pdo = null;
                    } else {
                        echo "<p style='color: red'>该用户名已经被使用，请换个名字</p>";
                    }
                    $pdo = null;
                } catch (PDOException $e) {
                    echo "<p style='color: red'>无法连接到服务器，请检查您的网络</p>";
                }
            }
            ?>
            <input type="submit" value="注册" id="submit">
            <p>已有ImgShow账号? <a href="login.php"> 立即登录!</a></p>
    </div>
</main>
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
                    <p>简体中文</p></div>
            </li>
        </ul>
    </div>
</footer>
<ul class="square">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>
<script>
    function validate() {
        var pw1 = document.getElementById("password1").value;
        var pw2 = document.getElementById("password2").value;
        if (pw1 !== "") {
            if (pw1 === pw2) {
                document.getElementById("check-result").innerHTML = "";
                document.getElementById("submit").disabled = false;
                document.getElementById("submit").style.cursor = "pointer";
                return true;
            } else {
                document.getElementById("check-result").innerHTML = "两次密码不相同";
                document.getElementById("submit").disabled = true;
                document.getElementById("submit").style.cursor = "not-allowed";
                return false;
            }
        } else if (pw1 !== "" && pw2 === "") {
            document.getElementById("check-result").innerHTML = "";
            return false;
        } else {
            document.getElementById("check-result").innerHTML = "请输入密码";
            document.getElementById("submit").disabled = true;
            document.getElementById("submit").style.cursor = "not-allowed";
            return false;
        }
    }
</script>
</body>
</html>