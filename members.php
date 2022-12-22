<?php

/*  begin the session  */
session_start();

/* include footer.php for dynamic footer sections */
include("footer.php");
$my_footer = "";

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
        $username = $stmt->fetchColumn(); // erste column nehmen

        /*  if we have no result then something is wrong  */
        if ($username == false) {
            //echo "-->" . $_SESSION['idAccount'];
            $message = 'Access Error';

            /* add a context sensitive footer string for the pager footer section */
            $options = array('login.html' => 'Go to Login Form');
            $my_footer = footer($options);
        } else {
            $message = 'Welcome ' . $username . '!';
            $message .= "<br/><br/>" . 'Make your choice: ';
            /* include test content just for presentation purposes */

            /* add a context sensitive footer string for the pager footer section */
            $options = array(
                'addentry.php' => 'add a new bug',
                'show_all_entry_titles.php' => 'Show all bugs and assign them!',
                'logout.php' => 'Log out'
            );
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
        <title>Members Only Page</title>
    </head>

    <body>
        <h1>
            <font color="#0000FF">Members Only Area</font>
        </h1>
        <h3><?php echo $message; ?></h3>

        <br clear="all" />
        <hr />
        <?php

    echo $my_footer;

    ?>
        <br />
        <hr />


    </body>

</html>