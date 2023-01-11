<?php

/*  begin the session  */
session_start();

/* include footer.php for dynamic footer sections */
include("footer.php");
$my_footer = "";

$is_illegal_access = false;

/*** check the existence of the session variable idAccount ***/
if (!isset($_SESSION['idAccount'])) {
    $is_illegal_access = true;

    $message = 'You must be logged in to access this page';

    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
} elseif (!isset($_GET['entry_title'])) {
    $message = 'Error on id of Bug';
    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
} else {

    /*  connect to database via PDO => $dbh is returned back */
    include_once("db_connect.php");

    try {

        $stmt = $dbh->prepare(" SELECT date_reported, summary, statut_name, email as assigned_to FROM bug b INNER JOIN bugstatus bs ON b.idBugstatus = bs.idBugstatus LEFT JOIN account ac ON b.assigned_to = ac.idAccount WHERE idBug = :id");
        $stmt->bindParam(':id', $_GET['entry_title'], PDO::PARAM_STR);

        $stmt->execute();

        /*  check for a result  */
        $result = $stmt->fetchAll();
        $bug = $result[0];
        //echo ($bug['assigned_to']);
    } catch (Exception $e) {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later';

        /* add a context sensitive footer string for the pager footer section */
        /* add a context sensitive footer string for the pager footer section */
        $options = array(
            'members.php' => 'Go back to make a new choice',
            'logout.php' => 'Log out'
        );
        $my_footer = footer($options);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bug Infos</title>
    </head>

    <body>
        <br>
        <h1>Bug infos :</h1>
        <form action="/action_page.php">
            <fieldset style="width: 50%;">
                <legend>Abount this bug:</legend>
                <label for="summary">Summary:</label><br>
                <input type="text" id="summary" name="summary" value=""><br><br>

                <label for="Bugstatus">Bug Status:</label><br>
                <input type="text" id="lname" name="lname" value=""><br><br>

                <label for="date_reported">Date Reported:</label><br>
                <input type="text" id="date_reported" name="date_reported" value=""><br><br>

                <label for="hours">Hours:</label><br>
                <input type="text" id="hours" name="hours" value=""><br><br>

                <label for="assigned_to">Assigned To:</label><br>
                <input type="text" id="assigned_to" name="assigned_to" value=""><br><br>

                <input type="submit" value="&rarr; Update Assigned">
            </fieldset>
        </form>
    </body>

</html>