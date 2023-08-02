<?php 
	require_once 'header.html';
	$registerForm = <<<_EOF_
	<form action="controller/add_user.php" method = "post">
		<fieldset>
			<legend> Personal Data</legend>
			<p><label for="fname"><span> First Name:</span><input type="text" name="firstName" id="fname" value=""></label></p>
			<p><label for="lname"><span>Last Name:</span><input type="text" name="lastName" id="lname" value=""></label></p>
			<p><label for="mail"><span>Email:</span><input type="email" name="email" id="mail" value=""></label></p>
			<p><label for=""><span>Password:</span><input type="password" name="password" id="" value="" pattern='.{6,}'></label></p>
			<!-- <p><label for=""><span>Login/Forum Name:</span><input type="text" name="loginName" id="log-name" value=""></label></p>-->
		     <input type="submit" value="Submit">		
		</fieldset>
		
	</form>
_EOF_;
	echo $registerForm;

	require_once 'footer.html';
 ?>