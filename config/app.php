<?php

@session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

function main($conn)
{
    $total_news = count(fetch_all_news($conn));
    $news_per_page = 8;
    global $total_pages;
    $total_pages = ceil($total_news / $news_per_page);

    if (!isset($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $current_page = ($page - 1) * $news_per_page;

    $query = $conn->prepare("SELECT * FROM news_articles ORDER BY news_views DESC LIMIT $current_page, $news_per_page");
    // $query = $conn->prepare("SELECT * FROM news_articles ORDER BY posted_on DESC LIMIT 4");
    $query->execute();
    $result = $query->get_result();

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $close = false;
        if ($count % 4 == 0) {
            if ($count > 0) {
                echo '</div>';
                echo '</div>';
            }
            echo '<div class="news-container">';
            echo '<div class="news-grid">';
        }

        $count++;

        if ($count % 4 == 1) {
            echo '<div class="news-01 flex-direction">';
            echo '<input type="hidden" name="news_id" value="' . $row['news_id'] . '">';
            echo '<input type="hidden" name="news_id" value="' . $row['image_identifier'] . '">';
            echo '<img src="' . $row['thumbnail_path'] . '" alt="news-image">';
            echo '<h4 class="category-label">' . $row['news_category'] . '</h4>';
            echo '<h5 class="news-01-heading">' . $row['news_title'] . '</h5>';
            echo '<h5 class="news-ago">' . $row['posted_on'] . " &middot; " . time_ago($row['posted_on']) . '</h5>';
            echo '</div>';
        } else {
            if ($count % 4 == 2) {
                echo '<div class="news-02 flex-direction">';
            }
            echo '<div class="news-02-section flex-direction">';
            echo '<input type="hidden" name="news_id" value="' . $row['news_id'] . '">';
            echo '<input type="hidden" name="news_id" value="' . $row['image_identifier'] . '">';
            echo '<h4 class="category-label">' . $row['news_category'] . '</h4>';
            echo '<h5 class="news-02-heading">' . $row['news_title'] . '</h5>';
            echo '<h5 class="news-ago">' . $row['posted_on'] . " &middot; " . time_ago($row['posted_on'])  . '</h5>';
            echo '</div>';
            if ($count % 4 == 0) {
                echo '</div>';
            }
        }
    }
    if ($count % 4 != 0) {
        if ($count % 4 != 1) {
            echo '</div>';
        }
        echo "</div>";
        echo "</div>";
    } else {
        echo "</div>";
        echo "</div>";
    }
}

function for_you($conn)
{
    $query = $conn->prepare("SELECT * FROM news_articles ORDER BY posted_on DESC LIMIT 3");
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div class="rt-news-grid">';
        echo '<div class="lt-rt-news flex-direction">';
        echo '<input type="hidden" name="news_id" value="' . $row['news_id'] . '">';
        echo '<input type="hidden" name="news_id" value="' . $row['image_identifier'] . '">';
        echo '<h4 class="category-label">' . $row['news_category'] . '</h4>';
        echo '<h5 class="news-02-heading">' . $row['news_title'] . '</h5>';
        echo '<h5 class="news-ago">' . $row['posted_on'] . " &middot; " . time_ago($row['posted_on'])  . '</h5>';
        echo '</div>';
        echo '<div class="rt-rt-news">';
        echo '<img src="' . $row['thumbnail_path'] . '" alt="image">';
        echo '</div>';
        echo '</div>';
    }
}
function fetch_all_news($conn)
{
    $query = $conn->prepare("SELECT * FROM news_articles WHERE 1");
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function time_ago($posted_on)
{
    $current_time = time();
    $time_difference = $current_time - strtotime($posted_on);
    $hours_ago = floor($time_difference / 3600);
    if ($hours_ago <= 0) return "Just Now";
    else if ($hours_ago == 1) return "1 hour ago";
    else if ($hours_ago > 1 && $hours_ago <= 24) return "{$hours_ago} hours ago";
    else if ($hours_ago > 24 && $hours_ago < 48) return floor($hours_ago / 24) . " day ago";
    else if ($hours_ago > 48 && $hours_ago <= 744) return floor($hours_ago / 24) . " days ago";
    else if ($hours_ago > 744 && $hours_ago < 1488) return floor($hours_ago / 744) . " month ago";
    else if ($hours_ago >= 1488 && $hours_ago <= 8760) return floor($hours_ago / 744) . " months ago";
    else if ($hours_ago >= 8760 && $hours_ago < 17520) return floor($hours_ago / 8760) . " year ago";
    else if ($hours_ago >= 17520) return floor($hours_ago / 8760) . " year ago";
}
