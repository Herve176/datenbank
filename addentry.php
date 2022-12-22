<?php

/*  begin the session  */
session_start();

/* include footer.php for dynamic footer sections */
include("footer.php");
$my_footer = "";

/*** check the existence of the session variable idAccount ***/
if (!isset($_SESSION['idAccount'])) {
    $message = 'You must be logged in to access this page';

    /* add a context sensitive footer string for the pager footer section */
    $options = array('login.html' => 'Go to Login Form ...');
    $my_footer = footer($options);
} else {
    /*  connect to database via PDO => $dbh is returned back */
    include_once("db_connect.php");

    try {
        /*  prepare the query for the username  */
        $stmt = $dbh->prepare("SELECT account_name FROM account 
                                 WHERE idAccount = :idAccount");

        /*  bind the parameters  */
        $stmt->bindParam(':idAccount', $_SESSION['idAccount'], PDO::PARAM_INT);

        /*  execute the prepared statement  */
        $stmt->execute();

        /*  check for a result  */
        $username = $stmt->fetchColumn();

        /*  if we have no result then something went wrong  */
        if ($username == false) {
            $message = 'Access Error';

            /* add a context sensitive footer string for the pager footer section */
            $options = array('login.html' => 'Go to Login Form');
            $my_footer = footer($options);
        } else {
            $message = 'Welcome ' . $username . ' !';
            $message .= "<br/><br/>" . 'Please type in the bug summary: ';

            /* add a context sensitive footer string for the pager footer section */
            $options = array('logout.php' => 'Log out');
            $my_footer = footer($options);
        }
    } catch (Exception $e) {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later"';

        /* add a context sensitive footer string for the pager footer section */
        $options = array('login.html' => 'Go to Login Form');
        $my_footer = footer($options);
    }
}

?>

<html>

    <head>
        <title>Add Bug summary </title>
    </head>

    <body>

        <h1><?php echo $message; ?></h1>

        <h2>Bug description </h2>
        <form action="addentry_submit.php" method="post">
            <fieldset>
                <p>
                    <label for="entry_title">bug resolution</label>
                    <input type="text" id="entry_title" name="entry_title" value="" size="45" maxlength="45" />
                </p>
                <p>
                    <label for="entry_text">bug description</label>
                    <textarea id="entry_text" name="entry_text" cols="41" rows="5"> </textarea>
                </p>

                <p>
                    <label for="entry_text">bug summary</label>
                    <textarea id="entry_text" name="entry_text2" cols="41" rows="5"> </textarea>
                </p>


                <p>
                    <input type="submit" value="&rarr; add entry" />
                </p>
            </fieldset>
        </form>

        <br clear="all" />
        <hr />
        <?php
    echo $my_footer;

    ?>
        <br />
        <hr />

    </body>

</html>