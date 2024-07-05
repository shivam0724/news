document.querySelectorAll(".public-news-content").forEach(select =>{
    select.addEventListener('click', (e)=>{
        const news_id = select.children[0].children[0].value;
        // console.log(news_id);
        const image_identifier = select.children[0].children[1].value;
        // console.log(image_identifier);
        post_request(news_id, image_identifier);
    })
})

function post_request(news_id, image_identifier){
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