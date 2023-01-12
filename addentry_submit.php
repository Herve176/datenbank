<?php

/*** begin our session ***/
session_start();

/* include footer.php for dynamic footer sections */
include_once("footer.php");
$my_footer = "";

//echo "<br/><hr/><br/>" . ' $_POST["entry_text"] = ' . $_POST['entry_text'] . "<br/><hr/><br/>";
//echo "<br/><hr/><br/>" . ' strlen($_POST["entry_text"]) = ' . strlen($_POST['entry_text']) . "<br/><hr/><br/>";


/*** check if the users is already logged in ***/
if (!isset($_SESSION['idAccount'])) {
    $message = 'You must be logged in to access this page';

    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
}
/*** check that both the title, text have been submitted ***/
elseif (!isset($_POST['summary'], $_POST['description'], $_POST['resolution'])) {
    $message = 'Please enter a valid bug description, bug summary and bug resolution';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
}

/*** check the entry_title is the correct length ***/
elseif (strlen($_POST['summary']) > 45 || strlen($_POST['summary']) < 4) {
    $message = 'Incorrect length for bug summary: too long or too short!';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
}
/*** check the description is the correct length ***/
elseif (strlen($_POST['description']) > 45 || strlen($_POST['description']) < 4) {
    $message = 'Incorrect length for bug descrition: too long or too short';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
}
/*** check the resolution is the correct length ***/
elseif (strlen($_POST['resolution']) > 255 || strlen($_POST['resolution']) < 4) {
    $message = 'Incorrect length for bug resolution: too long or too short';
    $my_footer = footer(array('addentry.php' => 'Go back to add entry page'));
} else {

    /*** if we are here the data is valid and we can insert it into database ***/
    $bug_summary = filter_var($_POST['summary'], FILTER_SANITIZE_STRING);
    $bug_description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $bug_resolution = filter_var($_POST['resolution'], FILTER_SANITIZE_STRING);
    $idAccount = filter_var($_SESSION['idAccount'], FILTER_VALIDATE_INT);

    /*** connect to database ***/
    include_once("db_connect.php");

    try {
        /*** set the error mode to exceptions ***/
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("insert into bug(idBugstatus, date_reported,summary,description,resolution, hours, reported_by)
                               values ( 1, current_date(), :summary, :description,:resolution, 0, :idAccount) ");

        /*** bind the parameters ***/
        $stmt->bindParam(':summary', $bug_summary, PDO::PARAM_STR);
        $stmt->bindParam(':description', $bug_description, PDO::PARAM_STR, 1024);
        $stmt->bindParam(':resolution', $bug_resolution, PDO::PARAM_STR, 1024);
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