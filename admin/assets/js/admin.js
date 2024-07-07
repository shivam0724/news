document.querySelectorAll(".navigation-items-list a").forEach(select => {
    select.addEventListener('click', function (e) {
        active_classes(select, e);
        set_heading(select, e);
        hiding_required_content(select, e);
    })
})

function active_classes(select, e) {
    document.querySelectorAll('.active').forEach(a => {
        a.className = "";
    })
    e.target.className = "active";
    console.log();
}
function set_heading(select, e) {
    if (e.target.innerHTML != "Logout") document.getElementById('admin-page-heading').innerHTML = e.target.innerHTML;
}

//hiding and showing main-content
function hiding_required_content(select, e) {
    document.querySelectorAll(".main-content").forEach(content => {
        content.classList = "main-content hide";
        // console.log(content);
    })
    document.querySelectorAll('.news-details').forEach(det => {
        det.innerHTML = "";
        det.style.display = 'none';
    })
    const data_id = select.getAttribute("data-id");
    document.getElementById(data_id).classList = "main-content";

    if (data_id == "news") {
        document.getElementById(data_id).children[0].style.display = "block";
        document.getElementById(data_id).children[1].style.display = "block";
    }
}

//news details

document.querySelectorAll("tr").forEach(select => {
    select.childNodes.forEach(child => {
        child.addEventListener('click', function (e) {
            const news_id = child.parentElement.firstChild.innerHTML;
            get_news_details(news_id, child);
        })
    })
})

function get_news_details(news_id, child) {
    $.ajax({
        type: "GET",
        url: "/admin/config/admin_app.php",
        data: { news_id: news_id, get_news: true },
        success: function (response) {
            // console.log(response);
            show_news_details(response, child);
        }
    })
}

function show_news_details(response, child){
    console.log(child.parentElement.parentElement.parentElement.parentElement.parentElement.lastElementChild);
    document.querySelector('#news h4').style.display = "none";
    document.querySelector('#news .news-table').style.display = "none";
    child.parentElement.parentElement.parentElement.parentElement.parentElement.lastElementChild.innerHTML = response;
    child.parentElement.parentElement.parentElement.parentElement.parentElement.lastElementChild.style.display = "block";
}