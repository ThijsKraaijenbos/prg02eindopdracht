<?php
/** @var array[] $db */
require_once 'includes/connection.php';

session_start();
$_SESSION['access_denied_warning'] = false;

$query = "SELECT games.*, studios.studio
          FROM games
          INNER JOIN studios ON games.studio_id = studios.id";
$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

$games = [];
while($row = mysqli_fetch_assoc($result))
{
    $games[] = $row;
}

//function om te kijken of de rating gelijk is aan 10.0 zodat het een 10 kan worden.
function displayRating($rating) {
    if (!str_contains("10.0", $rating)) {
        return $rating;
    } else {
        return "10";
    }
}

if (isset($_POST['debugbutton'])) {
    $testbuttonquery = "INSERT INTO `games` (`title`, `genre`, `rating`, `multiplayer`, `studio_id`) VALUES ('test', 'test', '5.5', 'no', '5')";
    $testbuttonresult = mysqli_query($db, $testbuttonquery);
    header("location: index.php");
}

if (isset($_POST['logout'])) {
    unset($_SESSION['login']);
    header("location: index.php");
    exit();
}

mysqli_close($db);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Games List - Index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
<div class="container">
    <h1 class="title mt-4 has-text-centered">Games I have played</h1>
    <hr>
    <section class="columns">
        <div class="column">
            <a href="create.php" class="button">Add game</a>
        </div>
        <?php if (!empty($_SESSION['login'])) { ?>
            <form method="post" action="index.php" class="column is-narrow">
                <button class="button is-link" type="submit" name="debugbutton">Add template game</button>
                <button class="button is-link" type="submit" name="logout">Logout</button>
            </form>
        <?php } ?>
        <?php if (empty($_SESSION['login'])) { ?>
            <form method="post" action="login.php" class="column is-narrow">
                <button class="button is-link" type="submit" name="login">Login</button>
            </form>
        <?php } ?>
    </section>
    <div class="columns">
        <div class="column">

            <table class="table is-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Genres</th>
                    <th>Personal rating</th>
                    <th>Studio</th>
                    <th>Multiplayer Support</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($games as $index => $game) { ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= ucwords(htmlentities($game['title'])) ?></td>
                        <td><?= ucwords(htmlentities($game['genre'])) ?></td>
                        <td><?= displayRating(htmlentities($game['rating'])) ?> / 10</td>
                        <td><?= ucwords(htmlentities($game['studio'])) ?></td>
                        <td><?= ucfirst(strtolower(htmlentities($game['multiplayer']))) ?></td>
                        <td><a href="details.php?id=<?= $game['ID']?>">Details</a></td>
                        <td><a href="edit.php?id=<?= $game['ID']?>">Edit</a></td>
                        <td><a href="delete.php?id=<?= $game['ID']?>">Delete</a></td>
                    </tr>

                <?php } ?>
                </tbody>
            </table>

        </div>
<!--        <div class="column is-one-fifth">-->
<!---->
<!--        </div>-->
</div>
</body>
</html>