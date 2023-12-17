<?php
/** @var array[] $db */
require_once 'includes/connection.php';

$id = $_GET['id'];
$query = "SELECT games.*, studios.studio
          FROM games
          INNER JOIN studios ON games.studio_id = studios.id
          WHERE games.ID = $id";

$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

$game = mysqli_fetch_assoc($result);
if (!$game) {
    header("location: index.php");
}
function displayRating($rating) {
    if (!str_contains("10.0", $rating)) {
        return $rating;
    } else {
        return "10";
    }
}

mysqli_close($db);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Games List - Details</title>
</head>
<body>
<div class="container px-4">
    <div class="columns">
        <div class="column is-narrow">
            <h1 class="title mt-4"><?= ucwords(htmlentities($game['title'])) ?></h1>
            <section class="content">
                <ul>
                    <li>Genre: <?= ucwords(htmlentities($game['genre'])) ?></li>
                    <li>Personal Rating: <?= displayRating(htmlentities($game['rating']))?> / 10</li>
                    <li>Studio: <?= ucfirst(htmlentities($game['studio'])) ?></li>
                    <li>Multiplayer Support: <?= ucfirst(htmlentities($game['multiplayer'])) ?></li>
                </ul>
            </section>
            <div>
                <a class="button" href="index.php">Go back to the list</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>