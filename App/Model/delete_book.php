<?php
require_once("../../Controller/MDBConnection.php") ;
require_once('../../Controller/security_suit.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/book.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/Author.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/AuthorDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookCategoryDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookAuthorDAO.php'); // Careful Here

function main(){
	$_POST = sanitizePostWithArray($_POST);
	extract($_POST, EXTR_PREFIX_ALL, 'P');
	$globalConnection =  MDBConnection::getConnection();
	$bookDAO = new BookDAO(); // Database access object
	$bookDAO->con = $globalConnection;
	$globalConnection->beginTransaction();
	#### Do something then commit changes.
	#### Assumption all tables in which book_isbn is part of the primary key will be deleted by
	#the on delete cascade constraint.
	if(! $bookDAO->delete($P_isbn)){
		$globalConnection->rollBack();
	}else{
		echo "OK";
	}
	$globalConnection->commit();
}

if($_POST){
	main();
}
?>