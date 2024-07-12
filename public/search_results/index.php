<?php
@session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

if(isset($_POST['search-field'])) $_SESSION['post'] = $_POST;
if (!isset($_POST['search-field']) && !isset($_SESSION['post'])) {
    header("Location: /?invalid_search=1");
}

function search_results($conn)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';
    if (isset($_GET['page_no'])) $page_no = $_GET['page_no'];
    else $page_no = 1;
    // var_dump($_SESSION);
    $search = "%" . $_SESSION['post']['search-field'] . "%";
    $query = $conn->prepare("SELECT * from news_articles WHERE news_title LIKE ? OR news_highlights LIKE ? OR tags LIKE ? ORDER BY posted_on DESC");
    $query->bind_param("sss", $search, $search, $search);
    $query->execute();
    $result = $query->get_result();

    $total_news = $result->num_rows;
    global $total_pages;
    $total_pages = $total_news;
    $news_per_page = 4;
    $total_pages = ceil($total_news / $news_per_page);
    $current_news_set = ($page_no - 1) * $news_per_page;

    $query = $conn->prepare("SELECT * from news_articles WHERE news_title LIKE ? OR news_highlights LIKE ? OR tags LIKE ? ORDER BY posted_on DESC LIMIT $current_news_set, $news_per_page");
    $query->bind_param("sss", $search, $search, $search);
    $query->execute();
    $result = $query->get_result();

    while ($row = $result->fetch_assoc()) {
        // echo json_encode($row);

        echo '<div class="public-news-content flex">';
        echo '<div class="lt-public-news flex">';
        echo '<input type="hidden" name="news_id" value="' . $row['news_id'] . '">';
        echo '<input type="hidden" name="news_id" value="' . $row['image_identifier'] . '">';
        echo '<img src=" ' . $row['thumbnail_path'] . '" alt="image">';
        echo '</div>';
        echo '<div class="rt-public-news flex-direction">';
        echo '<h3>' . $row['news_title'] . ' </h3>';
        echo '<h5> ' . substr($row['news_highlights'], 0, 100) . '...</h5>';
        echo '<h5 class="news-ago">' . $row['posted_on'] ." &middot; " .time_ago($row['posted_on']) . '</h5>';
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
    <title>Search Results</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/public/style.css">
</head>

<body>
    <!-- NAVBAR -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/nav.php'; ?>
    <div class="public-news flex-direction">
    <?php search_results($conn) ?>
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
            if($page_num == $page) echo '<a class="active" href="'. $_SERVER['PHP_SELF'] .'?page_no=' . $page_num . '">' . $page_num . '</a> ';
            else echo '<a href="'. $_SERVER['PHP_SELF'] .'?page_no=' . $page_num . '">' . $page_num . '</a> ';
            if($page_num < $total_pages) echo ", ";
        }
        ?>
    </div>
    <!-- footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/footer.php'; ?>
</body>

<script src="/script.js"></script>
<script src="/public/public.js"></script>
<script src="/assets/js/jquery-3.7.1.min.js"></script>

</html>