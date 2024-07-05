<?php

@session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

function main($conn)
{
    $total_news = count(fetch_all_news($conn));
    $news_per_page = 12;
    $total_pages = ceil($total_news / $news_per_page);
    global $total_pages;

    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $news_set = ($page - 1) * $news_per_page;

    $query = $conn->prepare("SELECT * FROM news_articles ORDER BY posted_on LIMIT $news_set, $news_per_page");
    $query->execute();
    $result = $query->get_result();

    // echo '<div class="news-container">';
    $news_count = 0;
    while ($row = $result->fetch_assoc()) {
        if ($news_count % 4 == 0) {
            if ($news_count > 0) {
                echo '</div>'; // Close previous news-grid
            }
            echo '<div class="news-grid">'; // Start new news-grid
        }

        $news_count++;
        $time_diff = time() - strtotime($row['posted_on']);
        $hours_ago = floor($time_diff / 3600);
        $time_ago = $hours_ago . ' hours ago';
        $category = htmlspecialchars($row['news_category']);
        $title = htmlspecialchars($row['news_title']);
        $thumbnail = htmlspecialchars($row['thumbnail_path']);

        if ($news_count % 4 == 1) {
            echo '<div class="news-01 flex-direction">';
            echo '<img src="' . $thumbnail . '" alt="news-image">';
            echo '<h4 class="category-label">' . $category . '</h4>';
            echo '<h5 class="news-01-heading">' . $title . '</h5>';
            echo '<h5 class="news-ago">' . $time_ago . '</h5>';
            echo '</div>';
        } else {
            if ($news_count % 4 == 2) {
                echo '<div class="news-02 flex-direction">';
            }
            echo '<div class="news-02-section flex-direction">';
            echo '<h4 class="category-label">' . $category . '</h4>';
            echo '<h5 class="news-02-heading">' . $title . '</h5>';
            echo '<h5 class="news-ago">' . $time_ago . '</h5>';
            echo '</div>';
            if ($news_count % 4 == 0) {
                echo '</div>'; // Close news-02
            }
        }
    }
    if ($news_count % 4 != 0) {
        echo '</div>'; // Close last news-grid
    }
    echo '</div>';
}

function fetch_all_news($conn)
{
    $query = $conn->prepare("SELECT * FROM news_articles WHERE 1");
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

?>
<!-- <link rel="stylesheet" href="/style.css">
<div class="news-container">
    <div class="news-grid">
        <div class="news-01 flex-direction">
            <img src="/assets/img/t20.webp" alt="news-image">
            <h4 class="category-label">Cricket</h4>
            <h5 class="news-01-heading">INDIA won ICC T20 Men's World Cup after 13 years in west
                indies under rohit sharma's
                captaincy.</h5>
            <h5 class="news-ago">2 hours ago.</h5>
        </div>
        <div class="news-02 flex-direction">
            <div class="news-02-section flex-direction">
                <h4 class="category-label">Cricket</h4>
                <h5 class="news-02-heading">INDIA won ICC T20 Men's World Cup after 13 years in west
                    indies under rohit's
                    captaincy.</h5>
                <h5 class="news-ago">2 hours ago.</h5>
            </div>
            <div class="news-02-section flex-direction">
                <h4 class="category-label">Cricket</h4>
                <h5 class="news-02-heading">INDIA won ICC T20 Men's World Cup after 13 years in west
                    indies under rohit's
                    captaincy.</h5>
                <h5 class="news-ago">2 hours ago.</h5>
            </div>
            <div class="news-02-section flex-direction">
                <h4 class="category-label">Cricket</h4>
                <h5 class="news-02-heading">INDIA won ICC T20 Men's World Cup after 13 years in west
                    indies under rohit's
                    captaincy.</h5>
                <h5 class="news-ago">2 hours ago.</h5>
            </div>
        </div>
    </div>
</div> -->