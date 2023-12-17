<?php
/** @var array[] $db */
require_once 'includes/connection.php';

session_start();

// Check if the 'login' session variable is set and true
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    if (isset($_POST['submit'])) {

        $title = mysqli_escape_string($db, $_POST['title']);
        $genre = mysqli_escape_string($db,$_POST['genre']);
        $rating = mysqli_escape_string($db,$_POST['rating']);
        $studio = mysqli_escape_string($db, $_POST['studio']);
        $multiplayer = mysqli_escape_string($db, $_POST['multiplayer']);

        require_once 'includes/albumerrors.php';

        if (!empty($_POST['title']) && !empty($_POST['genre']) && !empty($_POST['rating']) && !empty($_POST['multiplayer']) && empty($errors)) {
            //Get the id of the studio that is linked to the name of the inserted studio
            $getStudioQuery = "SELECT id FROM `studios` WHERE studio = '$studio'";
            $getStudioResult = mysqli_query($db, $getStudioQuery);
            $fetchID = mysqli_fetch_assoc($getStudioResult);

            //if studio Doesnt exist
            if (empty($fetchID)) {
                // create new studio
                $createStudio = "INSERT INTO `studios`(`studio`) VALUES ('$studio')";
                $createStudioResult = mysqli_query($db, $createStudio);

                $getStudioQuery = "SELECT id FROM `studios` WHERE studio = '$studio'";
                $getStudioResult = mysqli_query($db, $getStudioQuery);
                $fetchID = mysqli_fetch_assoc($getStudioResult);
            }
            //insert into database
            if (!empty($fetchID)) {
                $studioID = $fetchID['id'];
                $query = "INSERT INTO `games` (`title`, `genre`, `rating`,`multiplayer`,`studio_id`) VALUES ('$title', '$genre', '$rating','$multiplayer','$studioID')";
                $result = mysqli_query($db, $query);
            }
            if ($result) {
                header("Location: index.php");
                exit();
            }
        }

    }

    mysqli_close($db);
} else {
    // Redirect to the login page & show warning
    header("Location: login.php");
    $_SESSION['create_redirect'] = true;
    $_SESSION['access_denied_warning'] = true;
    exit();
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Games List - Create</title>
</head>
<body>
<div class="container px-4">
    <h1 class="title mt-4">Create album</h1>

    <?php if (!empty($errors)): ?>
        <ul class="notification is-danger">
            <?php foreach ($errors as $error): ?>
                <li><?= $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <section class="columns">
        <form class="column is-6" action="" method="post">
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="title">Title</label>
                </div>
                <div class="field-body">
                    <input class="input" id="title" type="text" name="title" placeholder="Title" value="<?= htmlentities($title ?? '') ?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="genre">Genre(s)</label>
                </div>
                <div class="field-body">
                    <input class="input" id="genre" type="text" name="genre" placeholder="Genre" value="<?= $genre ?? ''?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="rating">Rating</label>
                </div>
                <div class="field-body">
                    <input class="input" id="rating" type="number" name="rating" placeholder="Value between 1.0 - 10.0" step="0.1" value="<?= $rating ?? ''?>"/>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="studio">Studio</label>
                </div>
                <div class="field-body">
                    <input class="input" id="studio" type="text" name="studio" placeholder="Studio" value="<?= $studio ?? ''?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="multiplayer">Multiplayer</label>
                </div>
                <div class="field-body">
                    <input class="input" id="multiplayer" type="text" name="multiplayer" placeholder="Yes or No" value="<?= $multiplayer ?? ''?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                    <button class="button is-link is-fullwidth" type="submit" name="submit">Save</button>
                </div>
            </div>
        </form>
    </section>
    <a class="button mt-4" href="index.php">&laquo; Go back to the list</a>
</div>
</body>
</html>
