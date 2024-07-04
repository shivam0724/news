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

document.addEventListener('DOMContentLoaded', (e)=>{
    e.target.querySelectorAll(".nav-list-items").forEach(select=>{
        const category = select.innerHTML;
        const href = select.href;
        const new_href = "public/?category=" + category;
        console.log(new_href);
        if(category != "Home")
            select.href = href.replace("#",new_href);
    })
})

//TRENDING NAVBAR

document.addEventListener('DOMContentLoaded', (e)=>{
    e.target.querySelectorAll(".rt-trending a").forEach(select=>{
        const topic = select.innerHTML;
        const href = select.href;
        const new_href = "public/?topic=" + topic;
        console.log(new_href);
        if(topic != "Home")
            select.href = href.replace("#",new_href);
    })
})

//EVENT LISTENERS OF NEWS

document.querySelectorAll(".category-label").forEach(select =>{
    select.addEventListener('click', function (e){
        // console.log(e.target.innerHTML);
    })
})
document.querySelectorAll(".news-02-heading").forEach(select=>{
    select.addEventListener('click', function (e){
        // console.log(e.target.innerHTML);
    })
})
document.querySelectorAll(".news-01-heading").forEach(select=>{
    select.addEventListener('click', function (e){
        // console.log(e.target.innerHTML);
    })
})