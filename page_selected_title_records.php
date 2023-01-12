<?php

/*  begin the session  */
session_start();

/* include footer.php for dynamic footer sections */
include("footer.php");
$my_footer = "";

/* Use For encryption */
$cipher = "aes128";
$pass = "mon_id_123";
$iv = sha1($pass);

$is_illegal_access = false;

/*** check the existence of the session variable idAccount ***/
if (!isset($_SESSION['idAccount'])) {
    $is_illegal_access = true;

    $message = 'You must be logged in to access this page';

    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
} elseif (!isset($_GET['idBug'])) {
    $message = 'Error on id of Bug';
    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
} else {

    //idBug_crypted = filter_var($_GET['idBug'], FILTER_VALIDATE_INT);
    $idBug_crypted = str_replace(" ", "+", $_GET['idBug']);
    $_SESSION['idBug_crypt'] = $idBug_crypted;

    //echo $idBug_crypted;
    $idBug = openssl_decrypt($idBug_crypted, $cipher, $pass, 0, $iv);
    //echo $idBug;

    /*  connect to database via PDO => $dbh is returned back */
    include_once("db_connect.php");

    try {

        /* Get infos about the BUG */
        $stmt = $dbh->prepare("SELECT date_reported, reported_by, hours, summary, b.idBugstatus, statut_name, email as assigned_to, first_name, last_name FROM bug b INNER JOIN bugstatus bs ON b.idBugstatus = bs.idBugstatus LEFT JOIN account ac ON b.assigned_to = ac.idAccount WHERE idBug = :id");
        $stmt->bindParam(':id', $idBug, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $bug = $result[0];

        /* Get the Full Name of which the Bug is Assigned  */
        $assignedTo = $bug['first_name'] . " " . $bug['last_name'];

        /* Get all users */
        $stmt = $dbh->query("SELECT idAccount, first_name, last_name, email FROM account ");
        $users = $stmt->fetchAll();

        /* Select the user who report the Bug */
        foreach ($users as $user) {
            if ($user['idAccount'] == $bug['reported_by']) {
                $reported_by = $user['first_name'] . " " . $user['last_name'];
            }
        }
    } catch (Exception $e) {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later';

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


        <?php if ($is_illegal_access == true) : /* if the user is not connected */

        $message = '<h4>You are not Logged. Please Login First </h4>';
        echo $message;

        /* add a context sensitive footer string for the pager footer section */
        $options = array('login.html' => 'Go to Login Form');
        $my_footer = footer($options);
        echo $my_footer; ?>


        <?php else : ?>

        <h1 style="text-align: center;">Bug infos :</h1>


        <div style="display: flex; justify-content: center;">
            <fieldset style="width: 28%;">

                <legend>Abount this bug:</legend>

                <br>
                <label for="summary">Summary :</label>
                <span style="padding:0 0 0 60px;"><?= $bug['summary'] ?></span><br><br>

                <label for="Bugstatus">Bug Status :</label>
                <span style="padding:0 0 0 50px;"><?= $bug['statut_name'] ?></span><br><br>

                <label for="date_reported">Date Reported :</label>
                <span style="padding:0 0 0 20px;"><?= $bug['date_reported'] ?></span><br><br>

                <label for="hours">Hours :</label>
                <span style="padding:0 0 0 90px;"><?= $bug['hours'] ?></span><br><br>

                <label for="reported_by">Reported By :</label>
                <span style="padding:0 0 0 40px;"><?= $reported_by ?></span><br><br>

                <label for="assigned_to">Assigned To : </label>
                <?php if (!empty($bug['assigned_to'])) : ?>
                <span style="padding:0 0 0 40px;"><?= $assignedTo ?></span><br>
                <?php else : ?>
                <span style="padding:0 0 0 40px;">Nobody</span><br>
                <?php endif ?>

            </fieldset>
        </div><br>


        <?php
        // SSI le bug n'est pas CLOSED ou REJETE on ne peut le re-assigne
        if ($bug['idBugstatus'] != 7 && $bug['idBugstatus'] != 2) : ?>

        <form action="assigned_submit.php" method="post" style="display: flex; justify-content: center;">

            <input type="hidden" name="idBug" value="<?= $idBug ?>">

            <label for="reassigned_to">Re-assigned To : </label>
            <select name="reassigned_to" id="reassigned_to">
                <?php foreach ($users as $user) : ?>
                <option value="<?= $user['idAccount'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?>
                </option>
                <?php endforeach ?>
            </select>
            <span style="padding:0 0 0 5px;"></span>
            <input type="submit" value="&rarr; Update Assigned">

        </form>
        <?php endif /* end if of formular */ ?>
        <?php endif /* end if the user is not connected */ ?>
    </body>

</html>