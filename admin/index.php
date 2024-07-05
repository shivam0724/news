<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';

$_SESSION['admin']['admin_username'] = "shivam";

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

            $query = $conn->prepare("INSERT INTO news_articles (posted_by, news_title, news_category, tags, news_description, thumbnail_path, image_identifier) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("sssssss", $_SESSION['admin']['admin_username'], $_POST['title'], $_POST['category'], $_POST['tags'], $_POST['description'], $thumbnail_path, $time);
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="" method="post" class="news-submission flex-direction" enctype="multipart/form-data">
        <h2>News Article Submission Form</h2>
        <div class="news-submission-form">
            <label for="title">News Title</label>
            <input type="text" name="title" id="title" required>
            <label for="category">Select News Category</label>
            <select name="category" id="category" required>
                <option value="Sports">Sports</option>
                <option value="Business">Business</option>
            </select>
            <label for="tags">Type tags</label>
            <input type="text" name="tags" id="tags">
            <label for="description">Enter News Description</label>
            <textarea name="description" id="description" placeholder="enter upto 500 words" required></textarea>
            <label for="thumbnail">Upload thumbnail image</label>
            <input type="file" name="thumbnail" id="thubmnail" accept="image/*" title="Only Images are accepeted" required>
        </div>
        <button type="submit">Post</button>
    </form>
</body>

</html>