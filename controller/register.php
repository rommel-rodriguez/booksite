<?php 
	require_once '../model/user.php';
	require_once 'security_suit.php';
	require_once 'UserDAO.php';
	/*$conne = MDBConnection::getConnection();

	$rows = $conne->query('select * from test_user');

	foreach($rows as $row){
		var_dump($row);
	}
	*/

	foreach($_POST as $name => $value)
		$_POST[$name] = sanitizeString($value);
	// deprecated->NEED TO ADD SALTING IN THE PASSWORD_HASH FUNCTION
	// NEEDEDD JUST FOUND OUT THE BCRYPT uses its own salt
	// Read more about this matter online 
/*	$user = new User(
			$_POST['firstName'],
			$_POST['lastName'],
			$_POST['email'],
			password_hash($_POST['password'],PASSWORD_BCRYPT),
			"nothing"
		);
*/	
	$user = User::loadNewUser(
			$_POST['firstName'],
			$_POST['lastName'],
			$_POST['email'],
			password_hash($_POST['password'],PASSWORD_BCRYPT),
			"nothing"
		)	;
	$dao = new UserDAO();
	if(!$dao->create($user)){
		header('HTTP/1.1 302 Redirect');
		header("Location: ../test.html");
		//echo "Some error has happen";
	}else{
		header('HTTP/1.1 302 Redirect');
		header("Location: ../test.html");
		// include "../header.html";
		// echo "Should return some page here";
		// include "../footer.html";
	}
	

 ?>