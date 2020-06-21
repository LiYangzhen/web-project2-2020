<?php
function isActive($num)
{
    if ($num == $_SESSION['page']) {
        return "active";
    } else {
        return "";
    }
}

function creatPageNumber($ob)
{
    $total = floor(($_SESSION['sum'] / $_SESSION['showNum']) + 1);
    if ($total > 1 && $total < 6) {
        if ($_SESSION['page'] > 0) {
            echo '<a href="' . changePage(0, $ob) . '">首页</a>
                <a href="' . changePage($_SESSION['page'] - 1, $ob) . '"><</a>';
        }
        for ($i = 0; $i < $total; $i++) {
            echo '<a href="' . changePage($i, $ob) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
        }
        if ($_SESSION['page'] < $total - 1) {
            echo '<a href="' . changePage($_SESSION['page'] + 1, $ob) . '"> > </a>';
            echo '<a href="' . changePage($total - 1, $ob) . ' ">尾页</a>';
        }
    } elseif ($total > 5) {
        if ($_SESSION['page'] > 1) {
            echo '<a href="' . changePage(0, $ob) . '">首页</a>
                <a href="' . changePage($_SESSION['page'] - 1, $ob) . '"> < </a>';
        }
        if ($_SESSION['page'] < 2) {
            for ($i = 0; $i < 5; $i++) {
                echo '<a href="' . changePage($i, $ob) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
            }
        } else {
            for ($i = $_SESSION['page'] - 2; $i <= $i = $_SESSION['page'] + 2; $i++) {
                echo '<a href="' . changePage($i, $ob) . '" class="' . isActive($i) . '">' . ($i + 1) . '</a>';
            }
        }
        if ($_SESSION['page'] < $total - 1) {
            echo '<a href="' . changePage($_SESSION['page'] + 1, $ob) . '"> > </a>';
            echo '<a href="' . changePage($total - 1, $ob) . ' ">尾页</a>';
        }
    }
}

function changePage($num, $ob)
{
    if ($ob == "browse") {
        if (isset($_GET['submit1']) || isset($_POST['submit1'])) {
            $url = "browse.php?page=" . $num;
            $url = $url . "&country=" . $_SESSION['country'];
            $url = $url . "&city=" . $_SESSION['city'];
            $url = $url . "&theme=" . $_SESSION['theme'];
            $url = $url . "&submit1=筛+选";
            return $url;
        } elseif (isset($_GET['submit2']) || isset($_POST['submit2'])) {
            $url = "browse.php?page=" . $num;
            $url = $url . "&title=" . $_SESSION['title'];
            $url = $url . "&submit2=搜索";
            return $url;
        }
    } elseif ($ob == "my_favourite") {
        $url = "my_favourite.php?page=" . $num;
        return $url;
    } elseif ($ob == "my_photo") {
        $url = "my_photos.php?page=" . $num;
        return $url;
    }elseif ($ob=="search"){
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
}
