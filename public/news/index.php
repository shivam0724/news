<?php
@session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

if (isset($_POST['news_id']) && isset($_POST['image_identifier'])) {
    $query = $conn->prepare("SELECT * FROM news_articles WHERE news_id=? AND image_identifier=?");
    $query->bind_param("ss", $_POST['news_id'], $_POST['image_identifier']);
    $query->execute();
    $result = $query->get_result();
    $news = $result->fetch_assoc();
    // echo json_encode($result->fetch_assoc());
}
else{
    header("Location: /?invalid_news=1");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Details</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="news.css">
</head>

<body>
    <!-- NAVBAR -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/nav.php'; ?>

    <div class="news-section flex-direction">
        <h3>News In <?php echo $news['news_category'] ?> Category</h3>
        <h3><?php echo $news['news_title'] ?></h3>
        <h5 class="news-ago">News Posted On : <?php echo $news['posted_on'] ?></h5>
        <div class="news-section-content">
            <div class="news">
                <div class="lt-news flex">
                    <img src="<?php echo $news['thumbnail_path'] ?>" alt="image">
                    <h5><strong>News Description : </strong><?php echo $news['news_description'] ?></h5>
                </div>
                <div class="rt-news flex">
                    <h2>For You</h2>
                    <div class="news-container">
                        <div class="news-container-content flex-direction">
                            <?php
                                include $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';
                                for_you($conn);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/partials/footer.php'; ?>
</body>

<script src="/script.js"></script>
<script src="/assets/js/jquery-3.7.1.min.js"></script>

</html>