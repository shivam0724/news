// stops form resubmission

if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// EVENT LISTENER OF NAV BAR

// document.querySelectorAll(".nav-list-items").forEach(select=>{
//     select.addEventListener("click", function(e){
//         e.preventDefault();

//         const category = e.target.innerHTML;
//         const href = e.target.href;
//         const new_href = "public/?category=" + category;
//         console.log(new_href);

//         select.href = href.replace("#",new_href);
//     })
// })
// function category_post_request(category){
//     $.ajax({
//         type: "POST",
//         url: "/public/",
//         data: {category: category},
//         success: function(response){
//             console.log(response);
//         }
//     })
// }

// NAVIGATION BAR

document.addEventListener('DOMContentLoaded', (e) => {
    e.target.querySelectorAll(".nav-list-items").forEach(select => {
        const category = select.innerHTML;
        const href = select.href;
        const new_href = "/public/?category=" + category;
        // console.log(href);
        // console.log(new_href);
        if (category != "Home")
            // select.href = href.replace("#",new_href);
            select.setAttribute("href", new_href);
    })
})

//TRENDING NAVBAR

document.addEventListener('DOMContentLoaded', (e) => {
    e.target.querySelectorAll(".rt-trending a").forEach(select => {
        const topic = select.innerHTML;
        const href = select.href;
        const new_href = "/public/trending/?trending_topic=" + topic;
        // console.log(new_href);
        if (topic != "Home")
            // select.href = href.replace("#",new_href);
            select.setAttribute("href", new_href);
    })
})

//EVENT LISTENERS OF NEWS

// document.querySelectorAll(".category-label").forEach(select =>{
//     select.addEventListener('click', function (e){
//         // console.log(e.target.innerHTML);
//     })
// })
// document.querySelectorAll(".news-02-heading").forEach(select=>{
//     select.addEventListener('click', function (e){
//         // console.log(e.target.innerHTML);
//     })
// })
// document.querySelectorAll(".news-01-heading").forEach(select=>{
//     select.addEventListener('click', function (e){
//         // console.log(e.target.innerHTML);
//     })
// })


// NEWS DESCRIPTION

document.querySelectorAll(".news-01").forEach(select => {
    select.addEventListener('click', function (e) {
        const news_id = select.children[0].value;
        const image_identifier = select.children[1].value;

        post_request(news_id, image_identifier);
    })
})

document.querySelectorAll(".news-02-section").forEach(select => {
    select.addEventListener('click', function (e) {
        const news_id = select.children[0].value;
        const image_identifier = select.children[1].value;
        // console.log(news_id, image_identifier);
        post_request(news_id, image_identifier);
    })
})

document.querySelectorAll(".lt-rt-news").forEach(select => {
    select.addEventListener('click', function (e) {
        const news_id = select.children[0].value;
        const image_identifier = select.children[1].value;
        // console.log(news_id, image_identifier);
        post_request(news_id, image_identifier);
    })
})

function post_request(news_id, image_identifier) {
    let form = document.createElement("form");
    form.className = "hidden-form";
    form.action = "/public/news/";
    form.method = "post";

    let input1 = document.createElement("input");
    input1.name = "news_id";
    input1.value = news_id;
    input1.type = "hidden";

    let input2 = document.createElement("input");
    input2.name = "image_identifier";
    input2.value = image_identifier;
    input2.type = "hidden";

    form.appendChild(input1);
    form.appendChild(input2);
    document.body.appendChild(form);
    form.submit();
}