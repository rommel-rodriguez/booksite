<?php 
require_once('../../Controller/BookDAO.php');

/// DO I NEED TO ADD SOME SECURITY HERE SO THIS PROCCESS IS NOT ABUSED?
$bookDAO = new BookDAO();
$isbnList = [];
$titleList = [];
$seriesList = [];
$wrapperList = [];
$jsonString = "";
foreach($bookDAO->read() as $book){
	$isbnList[] = $book['book_isbn'];
	$titleList[] = $book['book_title'];
	if(!empty($book['book_series'])){
		$seriesList[] = $book['book_series'];
	}
}
$wrapperList[] = $isbnList;
$wrapperList[] = $titleList;
$wrapperList[] = $seriesList;

$jsonString = json_encode($wrapperList);

echo $jsonString;
// When using AJAX for some reason if($_POST) becomes useless

 ?>