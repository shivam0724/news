<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($_GET['category'])) echo $_GET['category'];
            if (isset($_GET['topic'])) echo $_GET['topic']; ?></title>
</head>

<body>
    <?php
    session_start();

    include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

    $query = $conn->prepare("SELECT * FROM news_articles WHERE 1");
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    // var_dump($row);

    ?>
    <img src="<?php echo $row['thumbnail_path'] ?>" alt="image">
</body>

</html>