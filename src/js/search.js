let search = document.getElementById("search");
let search_result = document.querySelector(".search_result");
let pagination = document.getElementById("pagination");
let imageGroup = document.querySelector(".imgGroup");

search.onclick = function () {
    event.preventDefault();
    imageGroup.style.display = "unset";
    pagination.style.display = "unset";
    let li = document.createElement('li');
    li.innerHTML = '<a href="#">' +
        '<img src="../travel-images/small/5855174537.jpg" alt="图片" width="200" height="250">' +
        '</a>' +
        '<div>' +
        '<h3>Title</h3>' +
        '<p>四大会计利润还给我看</p>' +
        '</div>';
    li.className = "thumbnail";
    search_result.appendChild(li);
    console.log(1);
};