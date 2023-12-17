<?php
$errors = [];

if ($title == "") {
    $errors['title'] = "Title cannot be empty";
}
if ($genre == "") {
    $errors['genre'] = "Genres cannot be empty";
}
if ($rating == "") {
    $errors['rating'] = "Rating cannot be empty, please insert a value between 1 and 10";
}
if (!is_numeric($rating) || $rating > 10 || $rating < 1) {
    $errors['rating'] = "Invalid rating, please insert a value between 1 and 10";
}
if (strcasecmp($multiplayer, "yes") !== 0 && strcasecmp($multiplayer, "no") !== 0) {
    $errors['multiplayer'] = "Multiplayer support must be either Yes or No";
}

if ($studio == "") {
    $errors['multiplayer'] = "Studio cannot be empty";
}