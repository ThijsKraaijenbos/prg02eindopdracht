<?php
$errors = [];

if (empty($email)) {
    $errors['email'] = "Email cannot be empty";
}
if (!str_contains($email, '@') && !empty($email)) {
    $errors['email'] = "Invalid email. @ required";
}
if (empty($password)) {
    $errors['password'] = "Password cannot be empty";
}
if (strlen($password) < 8) {
    $errors['password'] = "Password needs to contain at least 8 characters";
}


