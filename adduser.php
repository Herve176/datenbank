<?php

/*** begin our session ***/
session_start();

/*** set a form token ***/
$form_token = md5(rand(time(), true));

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;
?>

<html>
<head>
<title>Add User Form</title>
</head>

<body>
<h2>Add user</h2>
<form action="adduser_submit.php" method="post">
	<fieldset>

	<p>
	<label for="account_name">Username</label>
	<input type="text" id="account_name" name="account_name" value=""
			maxlength="50" />
	</p>

    <p>
	<label for="first_name">first_name</label>
	<input type="text" id="first_name" name="first_name" value=""
			maxlength="20" />
	</p>

    <p>
	<label for="last_name">last_name</label>
	<input type="text" id="last_name" name="last_name" value=""
			maxlength="20" />
	</p>
   
    <p>
	<label for="email">email</label>
	<input type="text" id="email" name="email" value=""
    placeholder="hdjomo1@gmail.com"
			maxlength="70" />
	</p>
    
    <P>working_hours</P>
    <select name="working_hours" id="workinghours">
        <option value="50">50hours</option>
        <option value="30">30hours</option>
        <option value="20">20hours</option>
        <option value="10">10hours</option>
    </select>

    <p>
	<label for="Portrait_image">portrait_image</label>
	<input type="file" id="portrait_image"/><br/>
	</p>
    
    

	<p>
	<label for="password">Password</label>
	<input type="password" id="password" name="password" value=""
				 maxlength="100" />
	</p>
	<p>
	<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
	<input type="submit" value="&rarr; Register" />
	</p>
	</fieldset>
</form>
</body>
</html>