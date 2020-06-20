<?php
session_start();    //启动会话
session_unset();    //删除会话
session_destroy();  //结束会话
echo $_SERVER['HTTP_REFERER'];
if (strpos($_SERVER['HTTP_REFERER'],'my_favourite') !== false ||strpos($_SERVER['HTTP_REFERER'],'my_photo') !== false || strpos($_SERVER['HTTP_REFERER'],'upload') !== false) {
    header("Location: ../../index.php");
}else{
    header("Location: " . $_SERVER['HTTP_REFERER']);
}
