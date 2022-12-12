<?php

/*  begin the session  */
session_start();

if(!isset($_SESSION['idAccount']))
{
    $message = 'You must be logged in to access this page';
}
else
{
	/* set start page variable for Bloggy's start page  */
    $start_page ="home.html";
	
	/* include footer.php for dynamic footer sections */
    include ("footer.php");
    $my_footer = "";

    /*  connect to database via PDO => $dbh is returned back */
    include_once("db_connect.php");

    try
    {
        /*  prepare the insert  */
        $stmt = $dbh->prepare("SELECT account_name FROM account
                               WHERE idAccount = :idAccount");

        /*  bind the parameters  */
        $stmt->bindParam(':idAccount', $_SESSION['idAccount'],
                           PDO::PARAM_INT);

        /*  execute the prepared statement  */
        $stmt->execute();

        /*  check for a result  */
        $username = $stmt->fetchColumn();

        /*  if we have no something is wrong  */
        if($username == false)
        {
            $message = 'Access Error';
        }

        else
        {
            /* unset the idAccount session variable */
            unset( $_SESSION['idAccount'] );
            $message = 'Dear user '.$username;
            $message .= "<br/>You are now logged out!<br/>";
                       
             /* add a context sensitive footer string for the page footer section */
            //$my_footer = footer(array('login.html' => 'Go to Login Form'));
            // $my_footer = footer(array('index_start_05.html' => 'Go to Start Page'));
            $my_footer = footer(array($start_page => 'Go to Start Page'));


        }
    }
    catch (Exception $e)
    {
        /*  if we are here, something is wrong in the database  */
        $message = 'We are unable to process your request. Please try again later';

        /* add a context sensitive footer string for the page footer section */
        $my_footer = footer(array('members.php' => 'Go Back Members Only Area'));
    }
}

?>

<html>
<head>
<title>Log Out Page</title>
</head>
<body>
<h1>Log Out </h1>
<h2><?php echo $message?></h2>

<br clear="all"/>
<hr/>
<?php

   echo $my_footer;

?>
<br/><hr/>


</body>
</html>

