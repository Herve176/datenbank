<?php

session_start();

$idBug = $_POST['idBug'];
$idAccount = $_POST['reassigned_to'];

/*  connect to database via PDO => $dbh is returned back */
include_once("db_connect.php");

try {
    $dbh->query("UPDATE bug SET assigned_to = $idAccount WHERE idBug = $idBug");
    $link = "http://localhost:8000/page_selected_title_records.php?idBug=" . $_SESSION['idBug_crypt'];
    header("Location: " . $link);
} catch (Exception $e) {
    /*  if we are here, something is wrong in the database  */
    $message = 'We are unable to process your request. Please try again later';
    echo $message . "\n Error  : " . $e;
}