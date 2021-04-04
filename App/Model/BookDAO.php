<?php
namespace Model;
use \Model\Book;
require_once  "../../Controller/MDBConnection.php";
require_once  "../../Controller/CRUDInterface.php";
# require_once  $_SERVER['DOCUMENT_ROOT'] . "/Model/Book.php"; # Replace   part with nothing.

class BookDAO implements CRUDInterface{
	public function __construct(){
		/**
		 * Need to define Where do i have to 
		 * close this connection for it not just to
		 * keep hanging there
		 * @var [type]
		 */
		$this->con = MDBConnection::getConnection();
	}

	public function create($bookObj):bool{
		$exitStatus = false;
		$insertSQL =<<<_SQL_
		insert into book (book_isbn, book_title, book_series, book_img, book_url)
			values(:isbn, :title, :series, :image, :url);
_SQL_;

		$preparedStatement = $this->con->prepare($insertSQL);
		$params = [
			':isbn' => $bookObj->getIsbn(),
			':title' => $bookObj->getTitle(),
			':image' => $bookObj->getImage(),
			':series' => $bookObj->getSeries(),
			':url' => $bookObj->getUrl()
		];
		$statement_output = $preparedStatement->execute($params);
		error_log("Debugging: st outuput: $statement_output");
		return ($statement_output != false) ? true : false;
		// if($preparedStatement->execute($params)){
		// 	$exitStatus = true;
		// }
		// return $exitStatus;
	}


	public function read():array{
		/**
		 * This function returns an array "rows" 
		 in which each row is a map column: value of 
		 each row of the table user
		 */
		$pdoStatement =  $this->con->query('select * from book');
		 return $pdoStatement->fetchAll(); 

	}
	/**
	 * Method: update
	 * Input: Book Object
	 * Returns: true if the update was succesfull, false otherwise
	 * @param  "Associative Array" $dict  
	 * Uses the value of $dict["book_isbn"] To find the right record
	 * to update
	 * 
	 */
	public function update($book):bool{
		$exitStatus = false;
		$updateSQL =<<<_SQL_
		update book set book_title = :title, book_series = :series, book_img = :image, book_url = :url)
			where book_isbn = :isbn;	
_SQL_;

		$preparedStatement = $this->con->prepare($updateSQL);
		$params = [
			':isbn' => $book->getIsbn(),
			':title' => $book->getTitle(),
			':image' => $book->getImage(),
			':series' => $book->getSeries(),
			':url' => $book->getUrl()
		];
		// When no rows are affected, execute returns 0 evaluating to false and cutting the flow in update_book.php
		// Change behavior so that this does not happen.
		$return_value = $preparedStatement->execute($params);
		if( $this->con->errorInfo()[0] === '00000'){
			$exitStatus = true;
		}else{
			//echo pdoErrorString($this->con);
			echo "\nINSIDE BOOKDAO->UDPATE function!!!!!!\n"; // ERROR HERE.
			print_r($this->con->errorInfo()); // ERROR HERE.
		}
		return $exitStatus;
	}

	/**
	 * Deletes rows of the table user
	 * Returns: The number of rows deteled
	 * or zero if no row where delete 
	 * -1 if there was some error
	 * @var []
	 * NEED TO FIGUREOUT WHAT THE RIGHT 
	 PARAMENTER IS!
	 */
	public function delete($isbn):int{
		 // Find out if a book's deletion cascade-update the other tables.
		//throw NotIplementedError();	
		$delete_query = 'DELETE FROM book WHERE book_isbn = :my_isbn';
		$preparedStatement = $this->con->prepare($delete_query);
		$params = [':my_isbn'=>$isbn];
		$num_rows = $preparedStatement->execute($params);
		if($num_rows != 1){
			error_log("Custom Shadow Log: Book Removal Not Succesful");
			return 0;
		}
		return 1;
	}

	/**
	 * 	findAll
	 * 	This funtion looks for a given isbn
	 * 	number inside the database an returns a Book
	 * 	object on Success finding it.
	 * 	Returns False Otherwise.
	 * 	@param int $isbn
	 * 	@return a Book Object or null  
	 */
	public function findAll($isbn){
		$simple_query =<<<_SQL_
		SELECT * FROM book WHERE book_isbn = :my_isbn;		
_SQL_;
		$bookDict = [];
		$preparedStatement = $this->con->prepare($simple_query);
		$params = [':my_isbn' => $isbn];
		if($preparedStatement->execute($params)){
			$bookDict = $preparedStatement->fetchAll();
		}else{
			error_log("Custom SHADOW LOG: Row Retrieval Not succesfull");
			return null;
		}
		//TODO: Pass the Fetched row to the Book::FillNewBook
		$foundBook = Book::loadNewBook($bookDict['book_isbn'], $bookDict['book_title'],
			$bookDict['book_img'], $bookDict['series'], $bookDict['book_url']);
		return $foundBook;
	}

	public function findByTitle($book_title){
		$simple_query =<<<_SQL_
		SELECT * FROM book WHERE book_title = :my_title;		
_SQL_;
		$bookDict = [];
		$preparedStatement = $this->con->prepare($simple_query);
		$params = [':my_title' => $book_title];
		if($preparedStatement->execute($params)){
			$bookDict = $preparedStatement->fetchAll()[0];
		}
		if(!$bookDict){
			$error_message = $this->con->errorInfo()[2];
			error_log("Custom SHADOW LOG: Row Retrieval Not succesfull Returning Null");
			error_log($error_message);
			return null;
		}
		//TODO: Pass the Fetched row to the Book::FillNewBook
		$foundBook = Book::loadNewBook($bookDict['book_isbn'], $bookDict['book_title'],
			$bookDict['book_img'], $bookDict['book_series'], $bookDict['book_url']);
		return $foundBook;

	}

	/**
	 *  findBySeries	
	 * 	This funtion looks for a given book 
	 *  that belongs to the book_series argument	
	 * 	returns object on Success finding it.
	 * 	Returns False Otherwise.
	 * 	@param string book_series 
	 * 	@return a Book Object or null  
	 */
	public function findBySeries($book_series){
		$simple_query =<<<_SQL_
		SELECT * FROM book WHERE book_series = :my_series;		
_SQL_;
		$bookDict = [];
		$preparedStatement = $this->con->prepare($simple_query);
		$params = [':my_series' => $book_series];
		if($preparedStatement->execute($params)){
			$bookDict = $preparedStatement->fetchAll();
		}else{
			error_log("Custom SHADOW LOG: Row Retrieval Not succesfull");
			return null;
		}
		//TODO: Pass the Fetched row to the Book::FillNewBook
		$foundBook = Book::loadNewBook($bookDict['book_isbn'], $bookDict['book_title'],
			$bookDict['book_img'], $bookDict['series'], $bookDict['book_url']);
		return $foundBook;

	}

	public function findBooksInCategory($category):array{
		$search_query =<<<_SQL_
		select book.book_isbn, book_title, book_series, book_url, book_img
		 from ((book inner join book_category on book.book_isbn = book_category.book_isbn)
			inner join category on book_category.idcategory = category.idcategory) where cat_name = :cat ;	
_SQL_;
		$bookCategoryRows = [];
		try{
			$preparedStatement = $this->con->prepare($search_query);
			$params = [':cat' => $category];
			//$preparedStatement->execute($params); // Only returns True or False
			if($preparedStatement->execute($params)){
				$bookCategoryRows = $preparedStatement->fetchAll();
			}else{
				error_log("Custom SHADOW LOG: Row Retrieval Not succesfull");
			}
		}catch(PDOException $pdoe){
			error_log("PDO Error: $pdoe");
		}

		return $bookCategoryRows;;
	}

	/**
	 * [findBooksInCategories This One should be able to search multiple categories at once]
	 * @param  [array] $categories "List of categories we are looking for in books" 
	 * @return [array]          List of books that have ALL the required categories 
	 */
	public function findBooksInCategories($categories):array{
		throw NotIplementedError();	
	}

	public function getBookCategoryDict($isbn):array{
		$bookCategories = [];
		$sqlQuery =<<<_SQL_
		select distinct cat_name, category.idcategory as idcategory
			from ((book inner join book_category on book.book_isbn = book_category.book_isbn) 
		    inner join category on book_category.idcategory = category.idcategory )
		    where book.book_isbn = :this_isbn;		
_SQL_;

		$preparedStatement = $this->con->prepare($sqlQuery);
		$params = [ ':this_isbn' => $isbn];
		if($preparedStatement->execute($params)){
			$tempCategories = $preparedStatement->fetchAll();
		}else{
			error_log("Custom SHADOW LOG: Row Retrieval Not succesfull");
		}
		foreach($tempCategories as $row ){
			$bookCategories[$row['cat_name']] = $row['idcategory'];
		}
		return $bookCategories;
	}
	public function getBookCategories($isbn):array{
		$bookCategories = [];
		foreach($this->getBookCategoryDict($isbn) as $name => $id){
			$bookCategories[] = $name;
		}
		return $bookCategories;
	}
	public function getBookCategoryID($isbn):array{
		$bookCategories = [];
		foreach($this->getBookCategoryDict($isbn) as $name => $id){
			$bookCategories[] = $id;
		}
		return $bookCategories;
	}

}
 ?>