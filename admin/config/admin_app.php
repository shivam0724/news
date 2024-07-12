<?php
@session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

if (isset($_POST['title']) && isset($_POST['category']) && $_POST['description']) {
    news_posting($conn);
}
function news_posting($conn)
{
    $time = time();
    $image_extension = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
    $thumbnail_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/thumbnails/news_" . $time . "." . $image_extension;
    // echo $thumbnail_path;
    $uploaded = false;

    if (getimagesize($_FILES['thumbnail']['tmp_name'])) {
        $uploaded = true;
    } else echo '<script>alert("File is not an image.")</script>';

    if ($uploaded = false) echo '<script>alert("Image uploading failed.")</script>';
    else {
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path)) {
            $thumbnail_path = "/uploads/thumbnails/news_" . $time . "." . $image_extension;

            $query = $conn->prepare("INSERT INTO news_articles (posted_by, news_title, news_category, tags, news_highlights, news_description, thumbnail_path, image_identifier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssssss", $_SESSION['admin']['admin_username'], $_POST['title'], $_POST['category'], $_POST['tags'], $_POST['description'], $_POST['description1'], $thumbnail_path, $time);
            if ($query->execute()) {
                echo '<script>alert("News Posted Successfully")</script>';
            } else {
                echo '<script>alert("News Posting Failed")</script>';
            }
        } else {
            echo '<script>alert("Sorry, there was an error uploading news thumbnail.")</script>';
        }
    }
}
// echo (count(fetch_news($conn)));
function fetch_news($conn)
{
    $query = $conn->prepare("SELECT * FROM news_articles");
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// echo (count(fetch_user_news($conn)));
function fetch_user_news($conn)
{
    $query = $conn->prepare("SELECT * FROM news_articles WHERE posted_by = ?");
    $query->bind_param("s", $_SESSION['admin']['admin_username']);
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

function fetch_news_category_wise($conn, $category)
{
    $query = $conn->prepare("SELECT * FROM news_articles WHERE news_category=?");
    $query->bind_param("s", $category);
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}


function show_category_wise_count($conn, $category)
{
    $row = fetch_news_category_wise($conn, $category);

    echo '<div class="news-info-box flex-direction">';
    echo '<img src="/admin/assets/img/svgs/all_news.svg" alt="all">';
    echo '<h4>' . count($row) . '</h4>';
    echo '<h4>Total Articles of ' . $category . ' Category</h4>';
    echo '</div>';
}

function fetch_admin_table($conn)
{
    $query = $conn->prepare("SELECT * FROM admin");
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}
function fetch_paginated_news($conn)
{
    global $total_pages, $page_no;
    $total_news = count(fetch_news($conn));
    $news_per_page = 5;
    $total_pages = ceil($total_news / $news_per_page);
    $GLOBALS['total_pages'] = $total_pages;

    if (isset($_GET['page_no'])) $page_no = $_GET['page_no'];
    else $page_no = 1;

    $current_news_set = ($page_no - 1) * $news_per_page;

    $query = $conn->prepare("SELECT * FROM news_articles ORDER BY posted_on DESC LIMIT $current_news_set, $news_per_page");
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}
function fetch_filtered_paginated_news($conn, $col, $col_value)
{
    $col_value = '%' . $col_value . '%';
    global $total_pages, $page_no;
    $total_news = count(fetch_filtered_news($conn, $col, $col_value));
    $news_per_page = 5;
    $total_pages = ceil($total_news / $news_per_page);
    $GLOBALS['total_pages'] = $total_pages;

    if (isset($_GET['page_no'])) $page_no = $_GET['page_no'];
    else $page_no = 1;

    $current_news_set = ($page_no - 1) * $news_per_page;

    $query = $conn->prepare("SELECT * FROM news_articles WHERE $col LIKE ? ORDER BY posted_on DESC LIMIT $current_news_set, $news_per_page");
    $query->bind_param("s", $col_value);
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}
function show_admin_table($conn)
{
    $rows = fetch_admin_table($conn);

    foreach ($rows as $key => $row) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['admin_name'] . '</td>';
        echo '<td>' . $row['admin_username'] . '</td>';
        echo '<td>' . $row['admin_password'] . '</td>';
        echo '<td>' . $row['admin_type'] . '</td>';
        echo '<td>' . $row['last_login_time'] . '</td>';
        echo '<td>' . $row['last_logout_time'] . '</td>';
        echo '<td>' . $row['creation_date'] . '</td>';
        echo '</tr>';
    }
}

function show_filtered_news($conn, $col, $col_value)
{
    $rows = fetch_filtered_paginated_news($conn, $col, $col_value);

    foreach ($rows as $index => $row) {
        echo '<tr>';
        echo '<td>' . $row['news_id'] . '</td>';
        echo '<td>' . $row['posted_by'] . '</td>';
        echo '<td>' . $row['news_title'] . '</td>';
        echo '<td>' . $row['news_category'] . '</td>';
        echo '<td>' . $row['news_views'] . '</td>';
        echo '<td>' . substr($row['news_highlights'], 0, 50) . '<br>' . '...read more</td>';
        echo '<td>' . substr($row['news_description'], 0, 50) . '<br>' . '...read more</td>';
        echo '<td>' . $row['tags'] . '</td>';
        echo '<td>' . $row['posted_on'] . '</td>';
        echo '</tr>';
    }
}
function show_news($conn)
{
    $rows = fetch_paginated_news($conn);

    foreach ($rows as $index => $row) {
        echo '<tr>';
        echo '<td>' . $row['news_id'] . '</td>';
        echo '<td>' . $row['posted_by'] . '</td>';
        echo '<td>' . $row['news_title'] . '</td>';
        echo '<td>' . $row['news_category'] . '</td>';
        echo '<td>' . $row['news_views'] . '</td>';
        echo '<td>' . substr($row['news_highlights'], 0, 50) . '<br>' . '...read more</td>';
        echo '<td>' . substr($row['news_description'], 0, 50) . '<br>' . '...read more</td>';
        echo '<td>' . $row['tags'] . '</td>';
        echo '<td>' . $row['posted_on'] . '</td>';
        echo '</tr>';
    }
}

// news details handled here

if (isset($_GET['news_id']) && isset($_GET['get_news']) && $_GET['get_news'] == true) {
    // echo json_encode(fetch_one($conn, $_GET['news_id']));
    $row = fetch_one($conn, $_GET['news_id']);

    // echo '<h4>News Details With News_ID : '. $row['news_id'] . ' Posted On : ' .  $row['posted_on'] . ' By : ' .  $row['posted_by'] .'</h4>';
    echo '<h4>News Details With News_ID : ' . $row['news_id'] . '</h4>';
    echo '<div class="news-details1">';
    echo '<h5>News Posted By : </h5>';
    echo '<h5>' . $row['posted_by'] . '</h5>';
    echo '<h5>News Title : </h5>';
    echo '<h5>' . $row['news_title'] . '</h5>';
    echo '<h5>News Category : </h5>';
    echo '<h5>' . $row['news_category'] . '</h5>';
    echo '<h5>Views : </h5>';
    echo '<h5>' . $row['news_views'] . '</h5>';
    echo '<h5>News Highlights : </h5>';
    echo '<h5>' . $row['news_highlights'] . '</h5>';
    echo '<h5>News Description : </h5>';
    echo '<h5>' . $row['news_description'] . '</h5>';
    echo '<h5>Tags : </h5>';
    echo '<h5>' . $row['tags'] . '</h5>';
    echo '<h5>News Posted On : </h5>';
    echo '<h5>' . $row['posted_on'] . '</h5>';
    echo '<h5>News Thumbnail : </h5>';
    echo '<img src="' . $row['thumbnail_path'] . '" alt="thumbnail">';
    echo '</div>';
}
function fetch_one($conn, $news_id)
{
    $query = $conn->prepare("SELECT * FROM news_articles WHERE news_id = ?");
    $query->bind_param("s", $news_id);
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_assoc();
}

// news filter

// if (isset($_GET['filter_name']) && isset($_GET['filter_value']) && isset($_GET['filter_button'])) {
//     $rows = fetch_filtered_news($conn, $_GET['filter_name'], $_GET['filter_value']);
//     // var_dump($rows);
// }

function fetch_filtered_news($conn, $col, $col_value)
{
    $col_value = '%' . $_GET['filter_value'] . '%';
    $query = $conn->prepare("SELECT * FROM news_articles WHERE $col LIKE ?");
    $query->bind_param("s", $col_value);
    $query->execute();
    $result = $query->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// showing table

function show_table($conn)
{
    if (isset($_GET['filter_name']) && isset($_GET['filter_value']) && isset($_GET['filter_button'])) {
        show_filtered_news($conn, $_GET['filter_name'], $_GET['filter_value']);
        // var_dump($rows);
    } else show_news($conn);
}

// pagination

function pagination()
{
    if (isset($_GET['page_no'])) $page_no = $_GET['page_no'];
    else $page_no = 1;

    $total_pages = $GLOBALS['total_pages'];
    if ($total_pages > 1) {
        echo '<h5 style="color: white;">Page No. </h5>';
    }
    for ($page_num = 1; $page_num <= $total_pages; $page_num++) {
        if ($total_pages > 1) {
            $get = "";
            if (isset($_GET['filter_name']) && isset($_GET['filter_value']) && isset($_GET['filter_button'])) {
                $get = 'filter_name=' . $_GET['filter_name'] . '&filter_value=' . $_GET['filter_value'] . '&filter_button=&';
            }
            if ($page_num == $page_no) echo '<a class="active" href="' . $_SERVER['PHP_SELF'] . '?' . $get . 'page_no=' . $page_num . '">' . $page_num . '</a> ';
            else echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . $get . 'page_no=' . $page_num . '">' . $page_num . '</a> ';
            if ($page_num < $total_pages) echo ", ";
        }
    }
}


// RAW HTML

function news_table_head()
{
    echo '<th>News_ID</th>
          <th>Posted By</th>
          <th>News Title</th>
          <th>News Category</th>
          <th>Views</th>
          <th>News Highlight</th>
          <th>News Description</th>
          <th>Tags</th>
          <th>Posted On</th>';
}

// NEWS DELETION

if(isset($_POST['news_id']) && isset($_POST['delete_button']) && $_POST['delete_button'] == true){
    $delete = $conn->prepare("DELETE FROM news_articles WHERE news_id=?");
    $delete->bind_param("s", $_POST['news_id']);
    $delete->execute();
}