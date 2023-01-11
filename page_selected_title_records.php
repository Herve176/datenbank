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
}