<?php
$errors = [];

if ($firstName == "") {
    $errors['firstName'] = "First Name cannot be empty";
}
if ($lastName == "") {
    $errors['lastName'] = "Last Name cannot be empty";
}
if ($email == "") {
    $errors['email'] = "Email cannot be empty";
}
if (!str_contains($email, '@')) {
    $errors['email'] = "Invalid email. @ required";
}
if (strlen($password) < 8) {
    $errors['password'] = "Password needs to contain at least 8 characters";
}