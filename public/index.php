<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

if(!isset($_GET['category'])) header("Location: /?invalid_category=1");
// var_dump($row);

function html_title()
{
    if (isset($_GET['category'])) echo $_GET['category'];
    if (isset($_GET['topic'])) echo $_GET['topic'];
}


function public_news($conn)
{   
    include $_SERVER['DOCUMENT_ROOT'].'/config/app.php';
    $query = $conn->prepare("SELECT * FROM news_articles WHERE news_category =? ORDER BY posted_on DESC");
    $query->bind_param("s", $_GET['category']);
    $query->execute();
    $result = $query->get_result();

    $total_news = $result->num_rows;
    $news_per_page = 4;
    global $total_pages;
    $total_pages = ceil($total_news / $news_per_page);

    if (isset($_GET['page_no'])) $page_no = $_GET['page_no'];
    else $page_no = 1;

    $current_news_set = ($page_no - 1) * $news_per_page;

    // $query = $conn->prepare("SELECT * FROM news_articles WHERE news_category =? ORDER BY posted_on DESC LIMIT $current_news_set, $news_per_page");
    $query = $conn->prepare("SELECT * FROM news_articles WHERE news_category =? ORDER BY posted_on DESC LIMIT $news_per_page OFFSET $current_news_set");
    $query->bind_param("s", $_GET['category']);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div class="public-news-content flex">';
        echo '<div class="lt-public-news flex">';
        echo '<input type="hidden" name="news_id" value="'.$row['news_id'].'">';
        echo '<input type="hidden" name="news_id" value="'.$row['image_identifier'].'">';
        echo '<img src=" ' . $row['thumbnail_path'] . '" alt="image">';
        echo '</div>';
        echo '<div class="rt-public-news flex-direction">';
        echo '<h3>' . $row['news_title'] . ' </h3>';
        echo '<h5> ' . substr($row['news_description'], 0, 100) . '...</h5>';
        echo '<h5 class="news-ago">' . $row['posted_on'] ." &middot; " .time_ago($row['posted_on'])  . '</h5>';
        echo '</div>';
        echo '</div>';
    }
    if($result->num_rows == 0){
        echo '<h3 class="no-results">No results found</h3>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php html_title() ?></title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- NAVBAR -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/nav.php'; ?>
    <div class="public-news flex-direction">
        <div class="public-welcome">
            <h3>Latest News from <?php html_title() ?> Category</h3>
        </div>
        <?php public_news($conn) ?>
    </div>
    <div class="pagination flex">
        <!-- <h5 style="color: white;">Page No. </h5> -->
        <?php
        if($total_pages > 1){
            echo '<h5 style="color: white;">Page No. </h5>';
        }
        if (!isset($_GET['page_no'])) {
            $page = 1;
        } else {
            $page = $_GET['page_no'];
        }
        for ($page_num = 1; $page_num <= $total_pages; $page_num++) {
            if($page_num == $page) echo '<a class="active" href="index.php?category='.$_GET['category'] . '&page_no=' . $page_num . '">' . $page_num . '</a> ';
            else echo '<a href="index.php?category='.$_GET['category'] . '&page_no=' . $page_num . '">' . $page_num . '</a> ';
            if($page_num < $total_pages) echo ", ";
        }
        ?>
    </div>
    <!-- footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/footer.php'; ?>
</body>

<script src="/script.js"></script>
<script src="public.js"></script>
<script src="/assets/js/jquery-3.7.1.min.js"></script>

</html>