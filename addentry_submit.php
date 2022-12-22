<?php

/*** begin our session ***/
session_start();

/* include footer.php for dynamic footer sections */
include_once("footer.php");
$my_footer = "";

echo "<br/><hr/><br/>" . ' $_POST["entry_text"] = ' . $_POST['entry_text'] . "<br/><hr/><br/>";
echo "<br/><hr/><br/>" . ' strlen($_POST["entry_text"]) = ' . strlen($_POST['entry_text']) . "<br/><hr/><br/>";


/*** check if the users is already logged in ***/
if (!isset($_SESSION['idAccount'])) {
    $message = 'You must be logged in to access this page';

    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
}
/*** check that both the title, text have been submitted ***/
elseif (!isset($_POST['entry_title'], $_POST['entry_text'])) {
    $message = 'Please enter a valid bug description, bug summary and bug resolution';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
}

/*** check the entry_title is the correct length ***/
elseif (strlen($_POST['entry_title']) > 45 || strlen($_POST['entry_title']) < 4) {
    $message = 'Incorrect length for bug resolution: too long or too short!';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
}
/*** check the entry_text is the correct length ***/
elseif (strlen($_POST['entry_text']) > 255 || strlen($_POST['entry_text']) < 4) {
    $message = 'Incorrect length for bug descrition: too long or too short';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
} elseif (strlen($_POST['entry_text']) > 255 || strlen($_POST['entry_text2']) < 4) {
    $message = 'Incorrect length for bug summary: too long or too short';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
} else {

    /*** if we are here the data is valid and we can insert it into database ***/
    $entry_title = filter_var($_POST['entry_title'], FILTER_SANITIZE_STRING);
    $entry_text = filter_var($_POST['entry_text'], FILTER_SANITIZE_STRING);
    $entry_text2 = filter_var($_POST['entry_text2'], FILTER_SANITIZE_STRING);
    $idAccount = filter_var($_SESSION['idAccount'], FILTER_VALIDATE_INT);

    /*** connect to database ***/
    include_once("db_connect.php");

    try {
        /*** set the error mode to exceptions ***/
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("insert into bug(date_reported,summary,description,resolution)
                               values ( current_date(), :entry_title, :entry_text,:entry_text2) ");

        /*** bind the parameters ***/
        $stmt->bindParam(':entry_title', $entry_title, PDO::PARAM_STR);
        $stmt->bindParam(':entry_text', $entry_text, PDO::PARAM_STR, 1024);
        $stmt->bindParam(':entry_text2', $entry_text, PDO::PARAM_STR, 1024);
        $stmt->bindParam(':idAccount', $idAccount, PDO::PARAM_INT);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** if all is done, say thanks ***/
        $message = 'New bug  entry added';

        /* add a context sensitive footer string for the pager footer section */
        $options = array(
            'addentry.php' => 'Go back to add entry page',
            'members.php' => 'Go back to make a new choice',
            'logout.php' => 'Log out'
        );
        $my_footer = footer($options);
    } catch (Exception $e) {
        echo "<br/>" . $e->getMessage() . "<br/>";
        // if we are here, something has gone wrong with the database
        $message = 'We are unable to process your request. Please try again later"';
        $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
    }
}
?>
<html>

    <head>
        <title> Add entry submit page</title>
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