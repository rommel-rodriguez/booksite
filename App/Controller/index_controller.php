<?php 
namespace Controller;

require_once($_SERVER['DOCUMENT_ROOT'] . '/autoload.php'); ## My autoloader
require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php'); ## Vendor's autoloader
use \Model\Book;
# spl_autoload_register();

#$title = "Fantasy";
## TODO: import TWIG 
$title = $_GET['title'] ?? "Fantasy";
# require_once 'BookDAO.php';
$loader = new \Twig\Loader\FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/templates');
$twig = new \Twig\Environment($loader);
# require_once ($_SERVER['DOCUMENT_ROOT'] . '/Model/Book.php');
$bookDAO = new BookDAO();
$categoryBooks = [];
$categoryBooks = $bookDAO->findBooksInCategory("$title");

$bookList = [];
foreach($categoryBooks as $book){
	$bookItem = Book::loadEmptyBook();
	$bookItem->setIsbn($book['book_isbn']);
	$bookItem->setTitle($book['book_title']);
	$bookItem->setImage($book['book_img']);
	$bookItem->setSeries($book['book_series']);
	$bookItem->setUrl($book['book_url']);
	$bookList[] = $bookItem;
}

foreach ( $bookList as $book){
	$bookReview = "";
	try {
		$bookReview = file_get_contents("content/{$book->getIsbn()}"); // The @ Suppresses the warning in the error log in case of 
	} catch (\Exception $e) {
	    //echo 'Caught exception: ',  $e->getMessage(), "\n";
	    error_log("Caught: $e");
	}
	if(!empty($bookReview)){
		$book->setReview($bookReview);
	}else{
		$book->setReview("There isn't a review for this book yet.");
	}
	$book->setCategories($bookDAO->getBookCategories($book->getIsbn()));
}

$twigTemplate = 'index_template.html.twig';

echo $twig->render($twigTemplate, ['bookList' => $bookList, 'title' => $title]);


?>	