<?php 
require_once("../../Controller/MDBConnection.php") ;
require_once('../../Controller/security_suit.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/book.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/Author.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/AuthorDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookCategoryDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookAuthorDAO.php'); // Careful Here
// Remember to add apropiate security constraints! 
//error_log("Debugging Post Request: " .  $_POST['categories']);
// var_dump($_POST);
$_POST = sanitizePostWithArray($_POST);
extract($_POST, EXTR_PREFIX_ALL, 'P');
$globalConnection =  MDBConnection::getConnection();
$bookDAO = new BookDAO(); // Database access object
$authDAO = new AuthorDAO();
$bookCatDAO = new BookCategoryDAO(); 
$bookAuthorDAO= new BookAuthorDAO(); 

$bookDAO->con = $globalConnection;
$authDAO->con = $globalConnection;
$bookCatDAO->setDbCon($globalConnection);
$bookAuthorDAO->setDbCon($globalConnection);

$newBook = Book::loadEmptyBook(); 
$categories = $P_categories;
$authors = explode(";",$P_authors); // Now $P_authors is a  string of authors separated by ; <== Whacht HERE

$newBook->setIsbn($P_isbn);
$newBook->setTitle($P_title);
$newBook->setImage($P_image);
$newBook->setSeries($P_series);
$newBook->setUrl($P_url);
$newBook->setReview($P_review);


$successStatus = true;

function registerCategories($categories, $newBook, $bookCatDAO, $globalConnection){
	foreach($categories as $categoryID){
		if($bookCatDAO->create(trim($newBook->getIsbn()), (int)$categoryID)){
			error_log( 'Success Adding Book-Cat to DB');
		}else{
			error_log('ERROR Adding Book-Cat to DB');
			$successStatus = false;
			echo "<p>Error while adding isbn:{$newBook->getIsbn()} Category: {$categoryID}</p>";
			echo '<br>Book-CAT Addition Error <br>';
			var_dump($globalConnection->errorInfo());
			foreach( $globalConnection->errorInfo() as $error){
				echo "<br>$error";
			}
			$globalConnection->rollBack();
			return;
		}
	}
			$globalConnection->commit();
			echo "successful_addition";
}

function writeReview($review, $fileName){
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/content/' . $fileName, $review);
}

// Transaction Starts
$globalConnection->beginTransaction();
// Insert book inside the DB
if($bookDAO->create($newBook)){
	error_log('Success Adding Book to DB');
	// NEED to think some logic here in case the author is already registered
	// and we need to ensure that the book has at least one author
	// and no error was generated registering other authors
	foreach($authors as $authorName){
		// Test if the author is not already register in the database
		if(!empty($authorName) and !($authDAO->findAll($authorName))){
			//Setting author OBJ
			$newAuthor = Author::loadEmptyAuthor();
			$newAuthor->setAuthName($authorName);

			if($authDAO->create($newAuthor)){
				error_log('Success Adding Author to DB');
				$createdAuthor = $authDAO->findAll($authorName);
				$bookAuthorDAO->create($newBook->getIsbn(), $createdAuthor->getIdauthor());

			}else{
				$successStatus = false;
				error_log('ERROR Adding Author to DB');
				echo '<br>Author Addition Error <br>';
				foreach( $globalConnection->errorInfo() as $error){
					echo "<br>$error";
				}
				$globalConnection->rollBack();
			}
		}
	}
	if($successStatus){
		registerCategories($categories, $newBook, $bookCatDAO, $globalConnection);
		// Transaction ends within the registerCategories Function 
		if($newBook->getReview()){
			writeReview($newBook->getReview(), $newBook->getIsbn());
		}
	}
}else{
	error_log('ERROR Adding Book to DB');
	$successStatus = false;
	echo '<br>Book Addition Error <br>';
	foreach( $globalConnection->errorInfo() as $error){
		echo "<br>$error";
	}
	$globalConnection->rollBack();
}


 ?>