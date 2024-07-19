<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/config/db_conn.php';
include ADMIN_APP;

// $_SESSION['admin']['admin_username'] = "shivam";
if (!isset($_SESSION['admin']['admin_username'])) {
    header("Location: /admin/config/adlogin.php");
}

$categories = array(
    "Sports" => "Sports",
    "Education" => "Education",
    "Business" => "Business",
    "Movies" => "Movies",
    "Politics" => "Politics",
    "Technology" => "Technology",
    "Economy" => "Economy",
    "Other" => "Other"
)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1440, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="style.css">
</head>

<noscript>
    <meta http-equiv="refresh" content="0; url=/config/redirects/nojs.php">
</noscript>

<body>
    <div class="nav">
        <div class="lt-container">
            <div class="vert-nav flex-start">
                <h3>Admin
                    <h3 style="padding: 5px;">Hello!&nbsp;<?php if (isset($_SESSION['admin']['admin_username'])) echo $_SESSION['admin']['admin_username'];
                                                            else echo "Shivam"; ?></h3>
                </h3>
                <div class="navigation-items">
                    <div class="navigation-items-list flex-start">
                        <a href="#dashboard" data-id="dashboard" class="active"><img src="/admin/assets/img/svgs/dashboard.svg" alt="">&nbsp;&nbsp;Dashboard</a>
                        <a href="#admin" data-id="admin"><img src="/admin/assets/img/svgs/admin.svg" alt="">&nbsp;&nbsp;Admins</a>
                        <a href="#news" data-id="news"><img src="/admin/assets/img/svgs/news.svg" alt="">&nbsp;&nbsp;News</a>
                        <a href="#news_posting" data-id="news_posting"><img src="/admin/assets/img/svgs/posting.svg" alt="">&nbsp;&nbsp;News Posting</a>
                        <a href="#news_update" data-id="news_update"><img src="/admin/assets/img/svgs/update.svg" alt="">&nbsp;&nbsp;News Updation</a>
                        <a href="#news_delete" data-id="news_delete"><img src="/admin/assets/img/svgs/delete.svg" alt="">&nbsp;&nbsp;News Deletion</a>
                        <a href="#contact" data-id="contact"><img src="/admin/assets/img/svgs/contact.svg" alt="">&nbsp;&nbsp;Contact</a>
                        <a href="/admin/config/logout.php"><img src="/admin/assets/img/svgs/logout.svg" alt="">&nbsp;&nbsp;Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rt-container flex-direction">
            <div class="hrtl-nav flex">
                <div class="hrtl-nav-content flex">
                    <div class="lt-htrl-nav flex">
                        <img src="/admin/assets/img/svgs/calander.svg" alt="calander">
                        <!-- <h4>JULY 06, 2024</h4> -->
                        <h4><?php echo date('l \of F jS, Y') ?></h4>
                    </div>
                    <div class="rt-htrl-nav flex">
                        <div class="notification-alerts-box">
                            <h4>No Notifications Yet.</h4>
                        </div>
                        <div class="user-account-box">
                            <a href="config/logout.php"><h4>Logout ?</h4></a>
                        </div>
                        <img src="/admin/assets/img/svgs/notification.svg" id="notification-alerts-icon" title="Notification Alerts" alt="alerts">
                        <img src="/admin/assets/img/svgs/user.svg" id="user-account-icon" alt="user">
                    </div>
                </div>
            </div>
            <div class="dashboard-content">
                <h3 id="admin-page-heading">Dashboard</h3>
                <div class="main-content hide" id="dashboard">
                    <h4>ALL NEWS</h4>
                    <div class="category-wise-news">
                        <div class="news-info-box flex-direction">
                            <img src="/admin/assets/img/svgs/all_news.svg" alt="all">
                            <h4><?php echo count(fetch_news($conn)) ?></h4>
                            <h4>Total Articles</h4>
                        </div>
                        <div class="news-info-box flex-direction">
                            <input type="hidden" name="posted_by" value='<?php echo fetch_user_news($conn)[0]['posted_by'] ?>'>
                            <img src="/admin/assets/img/svgs/all_news.svg" alt="all">
                            <h4><?php echo count(fetch_user_news($conn)) ?></h4>
                            <h4>Total Articles Posted By You</h4>
                        </div>
                    </div>
                    <h4>Category Wise News</h4>
                    <div class="category-wise-news">
                        <?php
                        foreach ($categories as $key => $value) {
                            show_category_wise_count($conn, $key);
                        }
                        ?>
                    </div>
                </div>
                <div class="main-content hide" id="admin">
                    <table>
                        <tr>
                            <th>Admin_ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Admin Privilege</th>
                            <th>Last Login Time</th>
                            <th>Last Logout Time</th>
                            <th>Admin Since</th>
                        </tr>
                        <?php try {
                            show_admin_table($conn);
                        } catch (Exception $catch) {
                            echo '<td>122344</td><td>Shivam</td><td>shivam</td><td>shivam</td><td>Administrator</td><td>2024-07-06 21:52:51</td><td>2024-07-06 21:50:03</td><td>2024-06-05 23:37:03</td>';
                        } ?>
                    </table>
                </div>
                <div class="main-content hide" id="news">
                    <div class="news-filter flex">
                        <h4>Filter</h4>
                        <form action="" method="get" class="flex">
                            <select name="filter_name" id="filter-name">
                                <?php
                                foreach (fetch_one($conn, '1011211100') as $key => $value) {
                                    echo '<option value="' . $key . '">' . ucwords(str_replace('_', ' ', $key)) . '</option>';
                                }
                                ?>
                            </select>
                            <input type="text" name="filter_value" id="filter-value" placeholder="enter value">
                            <button type="submit" data-id="filter_button" value="true" name="filter_button">Filter</button>
                        </form>
                    </div>
                    <div class="news-table">
                        <table>
                            <tr>
                                <?php news_table_head() ?>
                            </tr>
                            <tr>
                                <?php
                                show_table($conn) // show_news($conn)
                                ?>
                            </tr>
                        </table>
                        <div class="pagination flex">
                            <!-- <h5 style="color: white;">Page No. </h5> -->
                            <?php pagination() ?>
                        </div>
                    </div>
                    <div class="news-details"></div>
                </div>
                <div class="main-content hide" id="news_posting">
                    <h4>Fill the following fields to post a news article</h4>
                    <form action="" method="post" class="news-submission" enctype="multipart/form-data">
                        <h2>News Article Submission Form</h2>
                        <div class="news-submission-form flex-direction">
                            <label for="title">News Title</label>
                            <input type="text" name="title" id="title" placeholder="news title" required>
                            <label for="category">Select News Category</label>
                            <select name="category" id="category" required>
                                <option value="NULL" disabled selected>-Select-</option>
                                <!-- <option value="Education">Education</option> -->
                                <!-- <option value="Business">Business</option> -->
                                <?php
                                foreach ($categories as $key => $value) {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                }
                                ?>
                            </select>
                            <label for="tags">Type tags</label>
                            <input type="text" name="tags" placeholder="tags" id="tags">
                            <label for="description">Type News Highlights</label>
                            <textarea name="description" id="description" placeholder="enter upto 500 words" required></textarea>
                            <label for="description1">Type News Description</label>
                            <textarea name="description1" id="description1" placeholder="enter upto 500 words" required></textarea>
                            <label for="thumbnail">Upload thumbnail image</label>
                            <input type="file" name="thumbnail" id="thubmnail" accept="image/*" title="Only Images are accepeted" required>
                        </div>
                        <button type="submit">Post</button>
                    </form>
                </div>
                <div class="main-content " id="news_update">
                    <div class="news-filter flex">
                        <h4>Filter</h4>
                        <form action="" method="get" class="flex">
                            <select name="filter_name" id="filter-name">
                                <?php
                                foreach (fetch_one($conn, '1011211100') as $key => $value) {
                                    echo '<option value="' . $key . '">' . ucwords(str_replace('_', ' ', $key)) . '</option>';
                                }
                                ?>
                            </select>
                            <input type="text" name="filter_value" id="filter-value" placeholder="enter value">
                            <button type="submit" data-id="filter_button" name="filter_button">Filter</button>
                        </form>
                    </div>
                    <div class="news-table">
                        <table>
                            <tr>
                                <?php news_table_head() ?>
                            </tr>
                            <tr>
                                <?php
                                show_table($conn) // show_news($conn)
                                ?>
                            </tr>
                        </table>
                        <div class="pagination flex">
                            <!-- <h5 style="color: white;">Page No. </h5> -->
                            <?php pagination() ?>
                        </div>
                    </div>
                    <div class="news-details"></div>
                </div>
                <div class="main-content hide" id="news_delete">
                    <div class="news-filter flex">
                        <h4>Filter</h4>
                        <form action="" method="get" class="flex">
                            <select name="filter_name" id="filter-name">
                                <?php
                                foreach (fetch_one($conn, '1011211100') as $key => $value) {
                                    echo '<option value="' . $key . '">' . ucwords(str_replace('_', ' ', $key)) . '</option>';
                                }
                                ?>
                            </select>
                            <input type="text" name="filter_value" id="filter-value" placeholder="enter value">
                            <button type="submit" data-id="filter_button" name="filter_button">Filter</button>
                        </form>
                    </div>
                    <div class="news-table">
                        <table>
                            <tr>
                                <?php news_table_head() ?>
                            </tr>
                            <tr>
                                <?php
                                show_table($conn) // show_news($conn)
                                ?>
                            </tr>
                        </table>
                        <div class="pagination flex">
                            <!-- <h5 style="color: white;">Page No. </h5> -->
                            <?php pagination() ?>
                        </div>
                    </div>
                    <div class="news-details"></div>
                </div>
                <div class="main-content hide" id="contact">
                    <h4>No Contacts has been made with us yet.</h4>
                </div>
            </div>
        </div>

    </div>
</body>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<!-- <script src="/admin/assets/js/admin.js"></script> -->
 <script type="module" src="/admin/assets/js/admin.js"></script>
<script src="/assets/js/jquery-3.7.1.min.js"></script>

</html>