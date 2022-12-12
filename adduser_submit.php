<?php
/*** begin our session ***/
session_start();

/*** first check that both the account_name, password and form token have been sent ***/
if(!isset( $_POST['account_name'], $_POST['password'],$_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['working_hours'],$_POST['portrait_image'], $_POST['form_token']))
{
    $message = 'Please enter a valid email, password,working_hours...all the informations thanks!';
}
/*** check the form token is valid ***/
elseif( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Invalid form submission';
}
/*** check the account_name is the correct length ***/
elseif (strlen( $_POST['account_name']) > 50 || strlen($_POST['account_name']) < 4)
{
    $message = 'Incorrect Length for account_name';
}
/*** check the password is the correct length ***/
elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
{
    $message = 'Incorrect Length for Password';
}
/*** check the account_name has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['account_name']) != true)
{
    /*** if there is no match ***/
    $message = "account_name must be alpha numeric";
}
/*** check the password has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['password']) != true)
{
        /*** if there is no match ***/
        $message = "Password must be alpha numeric";
}
else
{
    /* include footer.php for dynamic footer sections */
    include_once("footer.php");
    $my_footer;

    /*** if we are here the data is valid and we can insert it into database ***/
    $account_name = filter_var($_POST['account_name'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
    $last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
    $working_hours = filter_var($_POST['working_hours'], FILTER_SANITIZE_STRING);
    $portrait_image = filter_var($_POST['portrait_image'], FILTER_SANITIZE_STRING);
    /*** now we can encrypt the password via Secure Hash Algorithm SHA1***/
    $password = sha1( $password );

    /*** connect to database ***/
    include_once("db_connect.php");

     try {
        /*** set the error mode to excptions ***/
        // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the insert ***/
        $stmt = $dbh->prepare("INSERT INTO account (account_name, password,email,first_name,last_name,working_hours,portrait_image )
                                VALUES (:account_name, :password,:email,:first_name,:last_name,:working_hours,:portrait_image )");

        /*** bind the parameters ***/
        $stmt->bindParam(':account_name',$account_name, PDO::PARAM_STR);
        $stmt->bindParam(':password',$password, PDO::PARAM_STR, 40);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->bindParam(':first_name',$first_name,PDO::PARAM_STR);
        $stmt->bindParam(':last_name',$last_name,PDO::PARAM_STR);
        $stmt->bindParam(':working_hours',$working_hours,PDO::PARAM_STR);
        $stmt->bindParam(':portrait_image',$portrait_image,PDO::PARAM_STR);
        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** unset the form token session variable ***/
        unset( $_SESSION['form_token'] );

        /*** if all is done, say thanks ***/
        $message = 'New account created thank!';
        $my_footer = footer(array('login.html' => 'Go to login form'));
    }


    catch(Exception $e)
    {
        /*** check if the account_name already exists ***/
        if( $e->getCode() == 23000)
        {
            /* echo "<br/>".$e->getMessage()."<br/>"; */
            $message = 'account_name already exists';

            /* add a context sensitive footer string for the page footer section */
            $my_footer = footer(array('login.html' => 'Go to login form'));
        }
        else   {

            /* echo "<br/>".$e->getMessage()."<br/>"; */
            // if we are here, something has gone wrong with the database
            $message = 'We are unable to process your request. Please try again later"';

            /* add a context sensitive footer string for the page footer section */
            $my_footer = footer(array('adduser.php' => 'Go back to add user page'));
        }
    }
}
?>

<html>
<head>
<title>Login</title>
</head>
<body>
<p><?php echo $message; ?>

<br clear="all"/>
<hr/>
<?php

   echo $my_footer;
?>
<br/><hr/>


</body>
</html>