<?php
require_once("../../Controller/MDBConnection.php") ;
require_once('../../Controller/security_suit.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/book.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/Author.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/AuthorDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookCategoryDAO.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/BookAuthorDAO.php'); // Careful Here


/**
 * [updateCategories description]
 * Adds links new categories to books and destroy links to 
 * all categories that are not in the new categories array.
 * @param  [array] $categories       [description]
 * @param  [Book] $newBook          [description]
 * @param  [BookCatDAO] $bookCatDAO       [description]
 * @param  [PDOConnection] $globalConnection [description]
 * @return [void]                   [description]
 */
function updateCategories($categories, $newBook, $bookCatDAO, $globalConnection, $bookDAO){
	$bookIsbn = trim($newBook->getIsbn());
	$shouldCommit = true;
	$oldCategories = $bookDAO->getBookCategoryID($bookIsbn);
	$newCategories = $newBook->getCategories();

	$addCategories = array_diff($newCategories, $oldCategories);
	$deleteCategories = array_diff($oldCategories, $newCategories);

	foreach($addCategories as $catId){
		if($bookCatDAO->create($bookIsbn, $catId)){
			error_log( "Success Linking Book-Cat in DB: {$bookIsbn} : $catId");
		}else{
			error_log( "ERROR Linking Book-Cat in DB: {$bookIsbn} : $catId");
			$shouldCommit = false;
		}
	}

	foreach($deleteCategories as $catId){
		if($bookCatDAO->delete($bookIsbn, $catId)){
			error_log( "Success Linking Book-Cat in DB: {$bookIsbn} : $catId");
		}else{
			error_log( "ERROR Linking Book-Cat in DB: {$bookIsbn} : $catId");
			$shouldCommit = false;
		}
	}

	// if(!$shouldCommit){
	// 	$globalConnection->rollBack();
	// 	return;
	// };
	// // Missing debugging information here. Create a Custom function for debugging pdo errors for most projects.
	// $globalConnection->commit();
	return $shouldCommit;
}

function updateReview($review, $fileName){
	/// Make if return a boolean in case of success.
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/content/' . $fileName, $review);
}


/**
 * Like update categories but for authors.
 * @param  [type] $authors          [description]
 * @param  [type] $newBook          [description]
 * @param  [type] $bookAuthorDAO    [description]
 * @param  [type] $globalConnection [description]
 * @return [type]                   [description]
 */
function updateAuthors($authors, $newBook, $bookAuthorDAO, $globalConnection){
	$shouldCommit = true;
	$bookIsbn = trim($newBook->getIsbn());
	$newAuthors = $authors;
	$oldAuthors = []; 
	foreach($bookAuthorDAO->findAuthorsByISBN($bookIsbn) as $auth){
		$oldAuthors[] = $auth->getAuthName();
	}
	$addAuthors = array_diff($newAuthors, $oldAuthors); 
	$deleteAuthors = array_diff($oldAuthors, $newAuthors); 
	$addAuthorIDs = array_map(function($authorName){
		$temp_authdao = new AuthorDAO();
		return $temp_authdao->findAll($authorName)->getIdauthor(); ### May cause connection Errors if not synched with global connection.
	}, $addAuthors);
	foreach($addAuthorIDs as $authId){
		if($bookAuthorDAO->create($bookIsbn, $authId)){
			error_log( "Success Linking Book-Author in DB: {$bookIsbn} : $authId");
		}else{
			error_log( "ERROR Linking Book-Author in DB: {$bookIsbn} : $authId");
			$shouldCommit = false;
		}
	}

	$deleteAuthorNames = array_map(function($authorName){
		$temp_authdao = new AuthorDAO();
		return $temp_authdao->findAll($authorName)->getIdauthor(); ### May cause connection Errors if not synched with global connection.
	}, $deleteAuthors);
	foreach($deleteAuthorNames as $authId){
		$db_response = $bookAuthorDAO->delete($bookIsbn, $authId);
		$error_code = $globalConnection->errorInfo()[0];
		if($error_code === '00000'){
			error_log( "Success UNLinking Book-Author in DB: {$bookIsbn} : $authId");
		}else{
			error_log( "ERROR UNLinking Book-Author in DB: {$bookIsbn} : $authId");
			$shouldCommit = false;
		}
	}

	return $shouldCommit;
}

function updateBook($newBook, $bookDAO, $globalConnection){
	$shouldCommit = false;	
	if($bookDAO->update($newBook)){
		$shouldCommit = true;
	}
	return $shouldCommit;
}

function printDictionary($assoc){
	foreach($assoc as $key => $value){
		if(gettype($value) != 'array'){
			echo "<br> $key => $value";
		}else{
			echo "<br>$key :<br>";	
			foreach($value as $k => $v){
				echo "<br>----> $v";
			}
		}
	}
}

function main($post_request){
	$_POST = $post_request;
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
	$newBook->setCategories($P_categories);
	// Transaction Starts
	$globalConnection->beginTransaction();

	if(updateBook($newBook, $bookDAO, $globalConnection)){
		$update_result = updateCategories($categories, $newBook, $bookCatDAO, $globalConnection, $bookDAO);
		if($update_result){
			if(updateAuthors($authors, $newBook, $bookAuthorDAO, $globalConnection)){ ### TODO: Failing here.
				updateReview($newBook->getReview(), $newBook->getIsbn());
				$globalConnection->commit();
				echo "successful_update";
			}else{
				echo("Error in updateAuthors");
				printDictionary($_POST);	
				$globalConnection->rollBack();
				return;
			}
		}else{
			echo("Error in updateCategories: result: $update_result" );
			printDictionary($_POST);	
			$globalConnection->rollBack();
			return;
		}
	}else{
		echo("Error in updateBook");
		printDictionary($_POST);	
		print_r($globalConnection->errorInfo()); 
		$globalConnection->rollBack();
		return;
	}
}


if($_POST){

	main($_POST);
}

?>