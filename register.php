<?php
$firstName = '';
$lastName = '';
$email = '';
$password = '';
if(isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "includes/connection.php";

    // Get form data
    $firstName = mysqli_escape_string($db,$_POST['firstName']);
    $lastName = mysqli_escape_string($db,$_POST['lastName']);
    $email = mysqli_escape_string($db,$_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    require_once 'includes/registererrors.php';

    if (empty($errors)) {
        // create a secure password, with the PHP function password_hash()
        $password = password_hash("$password", PASSWORD_DEFAULT);

        // store the new user in the database.
        $query = "INSERT INTO `users`(`email`, `password`, `first_name`, `last_name`) VALUES ('$email','$password','$firstName','$lastName')";
        $result = mysqli_query($db, $query);
        // If query succeeded
        if ($result) {
            // Redirect to login page
            header("Location: login.php");
            // Exit the code
            exit();
        }
    }
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
    <title>Registreren</title>
</head>
<body>

<section class="section">
    <div class="container content">
        <h2 class="title">Register With Email</h2>
        <?php if (!empty($errors)) { ?>
            <section>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php } ?>

            <section class="columns">
                <form class="column is-6" action="" method="post">

                    <!-- First name -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label" for="firstName">First name</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" id="firstName" type="text" name="firstName" value="<?= $firstName ?? '' ?>" />
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last name -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label" for="lastName">Last name</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" id="lastName" type="text" name="lastName" value="<?= $lastName ?? '' ?>" />
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label" for="email">Email</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>" />
                                    <span class="icon is-small is-left"><i class="fas fa-envelope"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
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

                    <!-- Submit -->
                    <div class="field is-horizontal">
                        <div class="field-label is-normal"></div>
                        <div class="field-body">
                            <button class="button is-link is-fullwidth" type="submit" name="submit">Register</button>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal"></div>
                        <div class="field-body">
                            <a class="button is-link is-fullwidth" href="login.php">Log-in instead</a>
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

    </div>
</section>
</body>
</html>
