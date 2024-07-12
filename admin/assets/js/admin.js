const categories = ['Sports', 'Business', 'Education', 'Movies', 'Politics', 'Technology', 'Economy', 'Other'];

// navigation bar handling

document.querySelectorAll(".navigation-items-list a").forEach(select => {
    select.addEventListener('click', function (e) {
        const url_hash = window.location.hash;

        active_classes(select);
        set_heading(select);
        hiding_required_content(select);
        clear_url(select.getAttribute("data-id"));

        set_pagination_url(select, url_hash);
    })
})
document.addEventListener('DOMContentLoaded', e => {
    const url_hash = window.location.hash;
    const select = document.querySelector('.navigation-items-list a[data-id="' + url_hash.replace("#", "") + '"')

    active_classes(select);
    set_heading(select);
    hiding_required_content(select);
    set_pagination_url(select, url_hash);
})
function clear_url(data_id) {
    // console.log(window.location.href, data_id);
    window.location.href = "/admin/#" + data_id;
}
function active_classes(select) {
    document.querySelectorAll('.active').forEach(a => {
        a.className = "";
    })
    select.className = "active";
    console.log();
}
function set_heading(select) {
    if (select.innerHTML != "Logout") document.getElementById('admin-page-heading').innerHTML = select.innerHTML;
}

//hiding and showing main-content
function hiding_required_content(select) {
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
        document.getElementById(data_id).children[0].style.display = "flex";
        document.getElementById(data_id).children[1].style.display = "block";
    }
    if (data_id == "news_update") {
        document.getElementById(data_id).children[0].style.display = "flex";
        document.getElementById(data_id).children[1].style.display = "block";
    }
    if (data_id == "news_delete") {
        document.getElementById(data_id).children[0].style.display = "flex";
        document.getElementById(data_id).children[1].style.display = "block";
    }
}

function set_pagination_url(select, url_hash) {

    if (url_hash == "#news" || url_hash == "#news_update" || url_hash == "#news_delete") {
        const main_content = document.getElementById(select.getAttribute('data-id'));

        main_content.children[1].children[1].querySelectorAll("a").forEach(a => {
            a.href += url_hash;
        })
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
    // const main_content = child.parentElement.parentElement.parentElement.parentElement.parentElement;
    const main_content = child.closest('.main-content');

    $.ajax({
        type: "GET",
        url: "/admin/config/admin_app.php",
        data: { news_id: news_id, get_news: true },
        success: function (response) {
            // console.log(response);
            show_news_details(response, child);
            // filter_button_listener(main_content, child);
            if (main_content.id == "news_delete") {
                news_delete_button(child)
                news_delete_request(main_content, news_id);
            }
            nav_url_click();
        }
    })
}

function show_news_details(response, child) {
    // console.log(child.parentElement.parentElement.parentElement.parentElement.parentElement);
    // document.querySelector('#news h4').style.display = "none";
    child.parentElement.parentElement.parentElement.parentElement.parentElement.children[0].style.display = "none";
    child.parentElement.parentElement.parentElement.parentElement.parentElement.children[1].style.display = "none";
    child.parentElement.parentElement.parentElement.parentElement.parentElement.lastElementChild.innerHTML = response;
    child.parentElement.parentElement.parentElement.parentElement.parentElement.lastElementChild.style.display = "grid";
}

// button for news deletion

function news_delete_button(child) {
    let news_delete_button = `<div class="news-delete-button flex">
                            <button data-id="news-deletion-button" class="button">Delete News&nbsp;?</button>
                        </div>`;
    const news_details1 = child.parentElement.parentElement.parentElement.parentElement.parentElement.children[2];
    news_details1.innerHTML += news_delete_button;
    // console.log(child.parentElement.parentElement.parentElement.parentElement.parentElement.children[2].children[1]);
}
function news_delete_request(main_content, news_id) {
    // console.log(main_content.children[2].children[2].firstElementChild);
    const dlt_btn = main_content.children[2].children[2].firstElementChild;
    dlt_btn.addEventListener('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: "/admin/config/admin_app.php",
            data: { news_id: news_id, delete_button: true },
            success: function (response) {
                console.log(response);
                alert("News Deleted Succesfully");
                // window.location.href = "/admin/#news-delete";
                // window.location.reload();
                document.querySelector('.navigation-items-list a[data-id="news_delete"]').click();
            }
        })
    })
}

//filter button 

// function filter_button_listener(main_content, child){
//     const filter_button = main_content.querySelector('button[data-id="filter_button"');
//     console.log(child, main_content, filter_button);
// }

document.querySelectorAll('select[name="filter_name"').forEach(select => {
    select.addEventListener('change', function (e) {
        e.preventDefault();
        console.log(select, e.target.value, select.nextElementSibling);
        const select_value = e.target.value;
        if (select_value == 'news_category') {
            select.nextElementSibling.outerHTML = `<select name="filter_value" id="filter_value">
                                            ${categories_option(categories)};
                                        </select>`;
        }
        // const main_content_id = e.target.closest(".main-content").id;
        // document.querySelector('a[data-id='+ main_content_id +']').click();
    })
})

function nav_url_click() {
    document.addEventListener('DOMContentLoaded', (e) => {
        const url_hash = window.location.hash.replace("#", "");

        // const main_content_id = e.target.closest(".main-content").id;
        document.querySelector('a[data-id=' + url_hash + ']').click();
    })
}

function categories_option(categories) {
    let list = "";
    for (const category of categories) {
        list += `<option value="${category}">${category}</option>`;
    }
    return list;
}