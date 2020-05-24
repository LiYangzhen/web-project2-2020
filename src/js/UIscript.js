const OR_style = "scroll-out-right 0.5s ease-in-out forwards";
const OL_style = "scroll-out-left 0.5s ease-in-out forwards";
const IR_style = "scroll-in-right 0.5s ease-in-out forwards";
const IL_style = "scroll-in-left 0.5s ease-in-out forwards";
let currentPosition = 0;
let timer;
let fileName = location.href.split('/')[location.href.split('/').length - 1];
const city = {
    中国: ['上海', '昆明', '北京', '烟台'],
    日本: ['东京', '大阪', '镰仓'],
    意大利: ['罗马', '米兰', '威尼斯', '佛罗伦萨'],
    美国: ['纽约', '洛杉矶', '华盛顿']
};
if (fileName.includes("index")){
    var CONTAINER = document.getElementsByClassName("mainImg")[0];
    var PICTURES = document.getElementsByClassName("scroll-img-box")[0].getElementsByTagName("img");
    var BTS = document.getElementsByClassName("radius")[0].getElementsByTagName("span");
    var LENGTH = PICTURES.length;
}
//////////////////////////////// 轮播图片

if (fileName.includes("index")) {
    PICTURES[0].style.opacity = "1";

    function scroll(num) {
        let target = currentPosition + num;
        while (target < 0) {
            target += LENGTH;
        }
        target %= LENGTH;
        if (target !== currentPosition) {
            if (num > 0) {
                PICTURES[currentPosition].style.animation = OL_style;
                PICTURES[target].style.animation = IL_style;
            } else {
                PICTURES[currentPosition].style.animation = OR_style;
                PICTURES[target].style.animation = IR_style;
            }
            BTS[currentPosition].className = "";
            BTS[target].className = "active";
            currentPosition = target;
        }
    }

    function buttonsClicked(num) {
        let differ = num - currentPosition;
        if (differ > 0) {
            scroll(differ);
        } else if (differ < 0) {
            scroll(differ);
        }
        currentPosition = num;
    }


    for (let i = 0; i < LENGTH; i++) {
        BTS[i].onmousedown = function () {
            buttonsClicked(i);
        }
    }
    timer = setInterval("scroll(1)", 4000);
    CONTAINER.onmouseover = function () {
        clearInterval(timer);
    }
    CONTAINER.onmouseout = function () {
        timer = setInterval("scroll(1)", 4000);
    }
}

////////////////////////// 滚轮

window.onscroll = function () {
    var a = document.documentElement.scrollTop || document.body.scrollTop;     //滚动条y轴上的距离
    let b = location.href.split('/');
    if (fileName.includes("index")) {
        if (a > 200) {
            document.querySelector(".banner").style.backgroundColor = "#343a40";
        } else {
            document.querySelector(".banner").style.backgroundColor = "transparent";
        }
    } else {
        document.querySelector(".banner").style.backgroundColor = "#343a40";
    }

    if (a > 200) {
        document.querySelector("#sidebar").style.visibility = "visible";

    } else {
        document.querySelector("#sidebar").style.visibility = "hidden";
    }
};

///////////////////////// 上传图片预览
if (fileName.includes("upload")) {
    document.getElementById("ImagesUpload").addEventListener("change", function () {
        let reader = new FileReader();
        reader.readAsDataURL(document.getElementById("ImagesUpload").files[0]);
        reader.onload = function () {
            let result = reader.result;
            let image = document.createElement("img");
            image.src = result;
            document.getElementById("img-preview").appendChild(image);
        }
    })
}

/////////////////////// 二级联动
function allCity() {
    var select1 = document.getElementById("country");
    for (var i in city) {
        select1.add(new Option(i, i), null);
    }
    addOption(); // 初始化选项
}

function addOption() {
    let select2 = document.getElementById("city");
    let select1 = document.getElementById("country").value;
    select2.length = 0; //清空一下市级菜单
    if (select1 != 'placeholder') {
        select2.add(new Option("请选择城市", "请选择城市"), null);
        for (var i in city[select1]) {
            select2.add(new Option(city[select1][i], city[select1][i]), null);
        }
    } else {
        select2.length = 0;
        select2.add(new Option("按城市筛选", "按城市筛选"), null);
    }
}

if (fileName.includes("browse")) {
    allCity();
}
///////////////////////