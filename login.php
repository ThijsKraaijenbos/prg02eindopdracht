<?php

/** @var array[] $db */

require_once "includes/connection.php";

session_start();

//Check if there was a warning from another page
if (isset($_SESSION['access_denied_warning']) && $_SESSION['access_denied_warning'] === true) {
    $accessWarning = "You need to log-in before you can access this page";
} else {
    $accessWarning = "";
}

$_SESSION['login'] = false;
// Is user logged in?
if (!$_SESSION['login']) {
    if (isset($_POST['submit'])) {

        // Get form data
        $email = mysqli_escape_string($db, $_POST['email']);
        $password = $_POST['password'];

        // Server-side validation
        require_once 'includes/loginerrors.php';

        // If data valid
        if (empty($errors)) {

            // SELECT the user from the database, based on the email address.
            $emailquery = "SELECT * FROM `users` WHERE email = '$email'";
            $emailresult = mysqli_query($db, $emailquery);

            // check if the user exists
            if ($emailresult) {
                // Get user data from result
                $userdata = mysqli_fetch_assoc($emailresult);
                // Check if the provided password matches the stored password in the database
                if ($userdata && password_verify($password, $userdata['password'])) {
                    // Store the user in the session
                    $_SESSION['login'] = true;
                    //Turn off warning
                    $_SESSION['access_denied_warning'] = false;
                    // Redirect to secure page depending on the page you came from
                    if (isset($_SESSION['create_redirect']) && $_SESSION['create_redirect']) {
                        header("location: create.php");
                        $_SESSION['create_redirect'] = false;
                        exit();
                    } else {
                        header("location: index.php");
                        exit();
                    }
                } // Credentials not valid
                else {
                    //error incorrect log in (incorrect password)
                    $errors['loginFailed'] = "Incorrect login. Email & Password don't match";
                }
            }// User doesn't exist
            if (empty($userdata)) {
                //error incorrect log in (incorrect user)
                $errors['loginFailed'] = "Incorrect login. Email & Password don't match";
            }
        }
    }
} else {
    header("location: index.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Login</title>
</head>
<body>
<section class="section">
    <?php if (isset($_SESSION['access_denied_warning']) && $_SESSION['access_denied_warning'] === true) { ?>
        <p><?= $accessWarning?></p>
    <?php } ?>
    <div class="container content">
        <h2 class="title">Login</h2>
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
                            <label class="label" for="email">Email</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" id="email" type="text" name="email" value="<?= htmlentities($email ?? '')  ?>" />
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label" for="password">Password</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" id="password" type="password" name="password"/>
                                    <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field is-horizontal">
                        <div class="field-label is-normal"></div>
                        <div class="field-body">
                            <button class="button is-link is-fullwidth" type="submit" name="submit">Log in With Email</button>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal"></div>
                        <div class="field-body">
                            <a class="button is-link is-fullwidth" href="register.php">Register instead</a>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal"></div>
                        <div class="field-body">
                            <a class="button is-link is-fullwidth" href="index.php">Back to home</a>
                        </div>
                    </div>
                </form>
            </section>
    </section>
</body>
</html>