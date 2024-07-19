<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No JS</title>
    <link rel="stylesheet" href="/style.css">

    <style>
        body{
            height: 100vh;
        }
        .no-js-body{
            user-select: none;
            color: white;
            width: 100%;
            height: 40%;
        }
        .no-js-body a{
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="no-js-body flex">
        <h3>Please Enable JavaScript and <a href="/index.php">click here to redirect to homepage.</a></h3>
    </div>
    <script>
        window.location.href = '/';
    </script>
</body>
</html>