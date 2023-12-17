<?php
/** @var mysqli $db */
//Require music data to use variable in this file
require_once "includes/connection.php";
session_start();

//Get id of selected game through given query
$id = $_GET['id'];
//get data for h1 tag that shows which game you're about to delete (Not needed for delete functionality)
$loadquery = "SELECT * FROM `games` where ID = $id";
$loadedgame = mysqli_fetch_assoc(mysqli_query($db, $loadquery));

if (!$loadedgame) {
    header("location: index.php");
    exit();
}

// check if submit button is set to delete the game
if (isset($_POST['submit'])) {
    $deletequery = "DELETE FROM `games` WHERE ID = $id";
    $result = mysqli_query($db, $deletequery);
            header("Location: index.php");
}

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("location: login.php");
    $_SESSION['create_redirect'] = false;
    $_SESSION['access_denied_warning'] = true;
    exit();
}

mysqli_close($db);

?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Games List - Delete</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<section class="container">
    <h1 class="title mt-4 has-text-centered">Are you sure you want to delete; <?= htmlentities($loadedgame['title'])?>?</h1>
    <div class = "columns is-variable is-centered">
        <form method="post" action="">
    <button class="button is-danger mt-4" type="submit" name="submit">Yes, I want to delete; <?= htmlentities($loadedgame['title'])?>.</button>
        </form>
    <a class="button mt-4" href="index.php">No, take me back to the homepage.</a>
    </div>
</section>
</body>
</html>
