<?php 
	require_once("Controller/security_suit.php");
 ?>
 <?php if(! $_POST): ?>
 <?php
	require_once 'header.html';
	require_once 'Controller/security_suit.php';
 ?>
 <form action="" method="POST" role="form">
 	<legend>Admin Control</legend>
 
 	<div class="form-group">
 		<label for="admin">Admin</label>
 		<div class="input-group">
 			<span class="glyphicon glyphicon-user input-group-addon " aria-hidden="true"></span>
	 		<input type="text" class="form-control" id="admin" name="admin" placeholder="Admin Username">
 		</div>	
 	</div>

 	<div class="form-group">
 		<label for="password">Password</label>
		<div class="input-group">
 			<span class="glyphicon glyphicon-magnet input-group-addon " aria-hidden="true"></span>
	 		<input type="password" class="form-control" id="password" name="password" placeholder="Admin Password">
		</div>
 	</div>
 
 	<button type="submit" class="btn btn-primary">Sign in</button>
 </form>
<?php require_once 'footer.html'; ?>
 <?php else: 
		$_POST = sanitizePost($_POST);
		### TODO: Change this, store password as hash ONLY and include CSRF token for protection of sessions.
		$json_string = file_get_contents('../booksite_admin.json');
		$admin_credentials = json_decode($json_string, $assoc=TRUE);
		if ( trim($_POST['admin']) == $admin_credentials['admin'] && 
				password_verify($_POST['password'] , $admin_credentials['password'])){
			session_start();
			$_SESSION['admin'] = $_POST['admin'];
			#echo "Success!!!";
			header('HTTP/1.1 302 Redirect');
			header("Location: kashim01.php");
		}else{
			# Redirect.
			header('HTTP/1.1 302 Redirect');
			header("Location: index.php");
		}
 ?>
 <?php endif ?>


