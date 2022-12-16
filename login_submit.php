<?php

/*** begin our session ***/
session_start();

/* include footer.php for dynamic footer sections */
include_once("footer.php");
$my_footer = "";

/*** check if the account is already logged in ***/
if (isset($_SESSION['idAccount'])) {
    $message = 'account is already logged in';
}
/*** check that both the email, password_hash have been submitted ***/
if (!isset($_POST['email'], $_POST['password'])) {
    $message = 'Please enter a valid email and password';
}
/*** check the email is the correct length ***/
elseif (strlen($_POST['email']) > 20 || strlen($_POST['email']) < 4) {
    $message = 'Incorrect Length for email';
}
/*** check the password_hash is the correct length ***/
elseif (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 4) {
    $message = 'Incorrect Length for password_hash';
}
/*** check the email has only alpha numeric characters ***/
// elseif (ctype_alnum($_POST['email']) != true) {
//     /*** if there is no match ***/
//     $message = "email must be alpha numeric";
// }
/*** check the password_hash has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['password']) != true) {
    /*** if there is no match ***/
    $message = "password_hash must be alpha numeric";
} else {


    /*** if we are here the data is valid and we can insert it into database ***/
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $passwrd = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    /*** now we can encrypt the password_hash ***/
    $password_hash = sha1($passwrd);

    /*** connect to database ***/
    include_once("db_connect.php");

    try {
        /*** set the error mode to excptions ***/
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("SELECT idAccount, email, password_hash FROM account
                                 WHERE email = :email AND
                                       password_hash = :pass");

        /*** bind the parameters ***/
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $passwrd, PDO::PARAM_STR, 40);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $idAccount = $stmt->fetchColumn();

        /*** if we have no result then fail boat ***/
        if ($idAccount == false) {
            $message = 'Login Failed';

            /* add a context sensitive footer string for the pager footer section */
            $my_footer = footer(array('login.html' => 'Go to login form'));
        }
        /*** if we do have a result, all is well ***/
        else {
            /*** set the session idAccount variable ***/
            $_SESSION['idAccount'] = $idAccount;

            /*** tell the user we are logged in ***/
            $message = 'You are now logged in';

            /* add a context sensitive footer string for the pager footer section */
            $options = array('members.php' => 'Go to Members only Area', 'logout.php' => 'Log out');
            $my_footer = footer($options);
        }
    } catch (Exception $e) {
        /*** check if the email already exists ***/
        if ($e->getCode() == 23000) {
            $message = 'email already exists';
            $my_footer = footer(array('login.html' => 'Go to login form'));
        } else {

            echo "<br/>" . $e->getMessage() . "<br/>";
            // if we are here, something has gone wrong with the database
            $message = 'We are unable to process your request. Please try again later"';
            $my_footer = footer(array('adduser.php' => 'Go back to add user page'));
        }
    }
}
?>

<html>

    <head>
        <title> Login</title>
    </head>

    <body>
        <p><?php echo $message; ?></p>
        <br />
        <hr />
        <?php
    /* footer section */
    echo $my_footer;

    ?>
    </body>

</html>