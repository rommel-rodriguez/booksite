<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/update_book.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/security_suit.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/BookDAO.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/AuthorDAO.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/CategoryDAO.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/controller/BookAuthorDAO.php");


if($_POST){
	$_POST = sanitizePost($_POST);
	$bookObj = null;
	$authors = null;
	$categories = null;
	$description = '';
	$bookDAO =  new BookDAO();
	$name = $_POST['name'];
	$value = $_POST['value'];

	switch ($name) {
		case 'isbn':
			$bookObj = $bookDAO->findAll($value);
			break;
		case 'title':
			# error_log("Looking for Title: $value");
			$bookObj = $bookDAO->findByTitle($value);
			break;
		case 'series':
			$bookObj = $bookDAO->findBySeries($value);
			break;
		default:
			$bookObj = null;
			break;
	}
	if($bookObj){
		$description = "";
		$description_path = $_SERVER['DOCUMENT_ROOT'] . "/content/{$bookObj->getIsbn()}";
		if(file_exists($description_path)){
			$description = file_get_contents($description_path);
		}
		$bookAuthorDAO = new BookAuthorDAO();
		$authorNames = [];
		error_log("UPDATE LOOKUP FILE, Looking for: {$bookObj->getIsbn()}");
		foreach($bookAuthorDAO->findAuthorsByISBN($bookObj->getIsbn()) as $author){
			$authorNames[] = $author->getAuthName();
		}
		$authors_string = implode(';', $authorNames);
		$categories = $bookDAO->getBookCategoryID($bookObj->getIsbn());	
		// Placeholder HERE CREATE A DICT OUT OF THE bookObj Properties
		// package everything, convert to json then echo it and done!

		$book_data = [
			'isbn' => $bookObj->getIsbn(),
			'title' => $bookObj->getTitle(),
			'image' => $bookObj->getImage(),
			'series' => $bookObj->getSeries(),
			'url' => $bookObj->getUrl(),
			'categories' => $categories,
			'description' => $description,
			'authors' => $authors_string
		];

		$data_json = json_encode($book_data);
		echo $data_json;

	}else{
		//Sent some error flag here
	}
}

 ?>
