<?php
   /**
    *  file: db_connect.php
    *
    *  call: include_once("db_connect.php");
    *
    *  @returns $dbh PDO-database handle object
    *
    */


    /* data set name and connection data */

    /* mysql hostname */
    $mysql_hostname = 'localhost';

    /* mysql username */
    // $mysql_username = 'db_blog_user';
    $mysql_username = 'root';


    /* mysql password */
    // $mysql_password = 'db_blog_password';
    $mysql_password = '';


    /* database name */
    $mysql_dbname = 'btsh';

     try {
        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname",
                        $mysql_username, $mysql_password);

       // $message = "connected to database ".$mysql_dbname;
       // echo "<br/>".$message."<br/>";

        /* set the error mode to exceptions */
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     }
     catch(Exception $e) {
           echo "Connection Error: ". $e->getMessage();
     }
?>