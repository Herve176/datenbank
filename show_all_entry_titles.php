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
} else {
    /*  connect to database via PDO => $dbh is returned back */
    include_once("db_connect.php");

    try {
        /*  prepare the query for the account_name  */
        $stmt = $dbh->prepare("SELECT account_name FROM account 
                                 WHERE idAccount = :idAccount");

        /*  bind the parameters  */
        $stmt->bindParam(':idAccount', $_SESSION['idAccount'], PDO::PARAM_INT);

        /*  execute the prepared statement  */
        $stmt->execute();

        /*  check for a result  */
        $account_name = $stmt->fetchColumn();

        /*  if we have no result then something went wrong  */
        if ($account_name == false) {
            $message = 'Access Error';

            /* add a context sensitive footer string for the pager footer section */
            $options = array('login.html' => 'Go to Login Form');
            $my_footer = footer($options);
        } else {
            /*  build the query string for showing all entry titles grouped by count(*)  
                using the specialized bug         */
            $sql = "SELECT * FROM bug";

            /* perform the query and retrieve a PDOStatement instance */
            $stmt = $dbh->query($sql);

            /*fetch all records of the result set into an array structure */
            (array) $result = $stmt->fetchALL(PDO::FETCH_OBJ);

            /* include class definition of class TableBuilder */
            include_once("TableBuilder.class.php");

            /* create an instance of TableBuilder with widthTable='450px', widthColumn='150px'*/
            // $tb = new TableBuilder('450px', '150px');
            $tb = new TableBuilder('850px', '150px');

            /*set background color for table header row */
            $bg_color = 'gold';  //  '#FFFFBB'    #C0C0C0

            /*set column names for table header row */
            //$headers = array('entry_title', 'number_of_entries', 'last_entry_date');
            $headers = array('Summary', 'Description', 'Resolution', 'hours');

            /* build the table header row */
            $tb->buildTableHeader($headers, $bg_color);

            /* set alternating background colors for table rows    #E8E8E8 #C0FF00 #FFFFBB*/
            $bg_colors = array('#FFFFFF', '#C0FF00');

            /* control flag for alternating background color of table rows */
            $odd = TRUE;
            /* iterate over the resulting entry titles instances using a foreach loop */
            foreach ($result as $obj) {

                /* use URL rewriting to pass the selected record to the called page  */
                /* entry_title = via GET submitted variable with the value = $obj->entry_title */
                // $link_address = "page_selected_title_records_02.php?entry_title=" . $obj->entry_title;
                // $link = "<a href='" . $link_address . "'>" . $obj->entry_title . "</a>";

                $link_address = "page_selected_title_records.php?entry_title=" . $obj->idBug;
                $link = "<a href='" . $link_address . "'>" . $obj->summary . "</a>";

                /* fill the $fields array containing also the above constructed hyperlink $link */
                /* call the method capitalizeName() on the animal instance to show */
                /* that the result row is actually fetched into an animal's class instance  */
                $fields = array($link, $obj->description, $obj->resolution, $obj->hours);

                /* format the table rows with alternating colors for better readability */
                ($odd == TRUE) ? $tb->addRow($fields, $bg_colors[0]) :  $tb->addRow($fields, $bg_colors[1]);
                ($odd == TRUE) ? $odd = FALSE :  $odd = TRUE;
                // print $obj->entry_title .' : '. $obj->number_of_entries . ' : ' .$obj->last_entry_date . "<br />";
            }

            /* retrieve the whole table and print it */
            //echo $tb->getTable();


            /* show horizontal line */
            // echo "<br/><hr/><br/>";


            $message = '<br/>Show all Bug';

            /* add a context sensitive footer string for the pager footer section */
            $options = array(
                'members.php' => 'Go back to make a new choice',
                'logout.php' => 'Log out'
            );
            $my_footer = footer($options);
        }
    } catch (Exception $e) {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later"';

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

<html>

    <head>
        <title>Show all Entry Titles</title>
    </head>

    <body>

        <h1><?php echo $message; ?></h1>

        <?php
    try {
        /* show horizontal line */
        echo "<br/><hr/><br/>";

        /* only if illegal_access is false =>  display table */
        if ($is_illegal_access == false) {
            /* retrieve the whole table and print it */
            echo $tb->getTable();
        }
    } catch (Exception $e) {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later"';
        echo  $message;
        /* add a context sensitive footer string for the pager footer section */
        $options = array('login.html' => 'Go to Login Form');
        $my_footer = footer($options);
    }

    ?>

        <br clear="all" />
        <hr />
        <?php
    echo $my_footer;

    ?>
        <br />
        <hr />

    </body>

</html>