<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';
include 'config/app.php';


{
    if(isset($_GET['invalid_category']) && $_GET['invalid_category'] == 1){
        echo '<script>alert("Invalid Category Selection.")</script>';
       //  unset($_GET['invalid_category']);
       header("Location: /");
    }
    if(isset($_GET['invalid_news']) && $_GET['invalid_news'] == 1){
        echo '<script>alert("Invalid News Selection.")</script>';
       //  unset($_GET['invalid_news']);
       header("Location: /");
    }
    if(isset($_GET['invalid_search']) && $_GET['invalid_search'] == 1){
        echo '<script>alert("Invalid Search Input.")</script>';
       //  unset($_GET['invalid_search']);
       header("Location: /");
    }
    if(isset($_GET['invalid_trending_topic']) && $_GET['invalid_trending_topic'] == 1){
        echo '<script>alert("Invalid Trending topic selection.")</script>';
       //  unset($_GET['invalid_search']);
       header("Location: /");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>News HomePage</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="nav-bar flex">
        <div class="nav-items flex">
            <div class="lt-nav flex">
                <a href="/"><img src="assets/img/svgs/logo.svg" alt="logo" /></a>
            </div>
            <div class="mdl-nav flex">
                <a href="/" class="nav-list-items">Home</a>
                <a href="#" class="nav-list-items">Business</a>
                <a href="#" class="nav-list-items">Education</a>
                <a href="#" class="nav-list-items">Sports</a>
                <a href="#" class="nav-list-items">Movies</a>
                <a href="#" class="nav-list-items">Politics</a>
                <a href="#" class="nav-list-items">Technology</a>
                <a href="#" class="nav-list-items">Economy</a>
            </div>
            <div class="rt-nav flex">
                <form action="public/search_results/" method="post" class="flex">
                    <input type="text" name="search-field" placeholder="search any topic" required>
                    <button type="submit">
                        <img class="search-icon" src="assets/img/svgs/search.svg" alt="search" />
                        <img class="search-icon-hover" src="assets/img/svgs/search-hover.svg" alt="search">
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="trending-nav flex">
        <div class="trending-nav-items flex" style="user-select: none;">
            <div class="lt-trending">
                <h3>Trending Topics <img src="assets/img/svgs/right_arrow.svg" alt="arrow"></h3>
            </div>
            <div class="rt-trending flex">
                <a href="#">T20 World Cup</a> |
                <a href="#">IPL 2024</a> |
                <a href="#">Nifty 50</a> |
                <a href="#">Budget</a> |
                <a href="#">Reliance Industries</a> |
                <a href="#">Telecom Rate Hike</a> |
                <a href="#">Parliament</a>
            </div>
        </div>
    </div>
    <div class="news-section flex">
        <div class="news-section-content">
            <div class="news">
                <div class="lt-news flex">
                    <h2>Top Stories</h2>
                    <?php main($conn) ?>
                </div>
                <div class="rt-news flex">
                    <h2>For You</h2>
                    <div class="news-container">
                        <div class="news-container-content flex-direction">
                            <?php for_you($conn) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination flex">
        <h5 style="color: white;">Page No. </h5>
        <?php
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        for ($page_num = 1; $page_num <= $total_pages; $page_num++) {
            if($page_num == $page) echo '<a class="active" href="index.php?page=' . $page_num . '">' . $page_num . '</a> ';
            else echo '<a href="index.php?page=' . $page_num . '">' . $page_num . '</a> ';
            if($page_num < $total_pages) echo ", ";
        }
        ?>
    </div>
    <footer>
        <div class="footer">
            <div class="lt-footer">
                © 2024 shivam, All Rights Reserved.
            </div>
            <div class="rt-footer">
                <img src="/assets/img/footer/twitter.svg" alt="twitter">
                <img src="/assets/img/footer/insta.svg" alt="instagram">
                <img src="/assets/img/footer/fb.svg" alt="facebook">
            </div>
        </div>
    </footer>
</body>

<script src="script.js"></script>
<script src="/assets/js/jquery-3.7.1.min.js"></script>

</html>