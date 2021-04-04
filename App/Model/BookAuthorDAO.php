<?php 
require_once  "../../Controller/MDBConnection.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/Author.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/book.php";
class BookAuthorDAO{
	private $con;
	public function __construct(){
		$this->con = MDBConnection::getConnection();
	}	
	public function create($book_isbn, $idauthor){
		$insertSQL=<<<_SQL_
			insert into book_author (idauthor, book_isbn) values(:author, :isbn);
_SQL_;
		$params = [
			':isbn' => $book_isbn,
			':author' => $idauthor
		];
		$preparedStatement = $this->con->prepare($insertSQL);
		$db_response = $preparedStatement->execute($params);
		if( $this->con->errorInfo()[0] === '00000'){
			return true;
		}
		$error_code = $this->con->errorInfo()[0];
		error_log("LOG Book-Author: $book_isbn -Book: $idauthor registration NOT successsfull, ErrorCode: $error_code");
		return false;
	}

	public function delete($book_isbn, $idauthor){
		$deleteSQL =<<<_SQL_
		delete from book_author
			where book_isbn = :isbn and idauthor= :idauthor;	
_SQL_;

		$preparedStatement = $this->dbCon->prepare($insertSQL);
		$params = [
			':isbn' => $book_isbn,
			':idauthor' => $idauthor
		];
		return $preparedStatement->execute($params);
	}

	public function exists($book_isbn, $idauthor){
		$sqlStatement=<<<_SQL_
		select * from book_author where book_isbn = :isbn and
			idauthor = :author;
_SQL_;

		$params = [ ':isbn' => $book_isbn, ':author' => $idauthor ];
		$preparedStatement = $this->con->prepare($sqlStatement);
		if($preparedStatement->execute($params)){
			return true;		
		}else{
			return false;
		}
	}

    /**
     * Function findByAuthorsISBN
     * @param mixed $book_isbn
     * This function returns an array of
     * Array of Author Objects
     * @return Array
     */
	public function findAuthorsByISBN($book_isbn){
		$authors = [];
		$sqlStatement=<<<_SQL_
		select author.idauthor, auth_name
			from (book_author inner join author
			on book_author.idauthor = author.idauthor)
			where book_isbn = :isbn;
_SQL_;
		$params = [ ':isbn' => $book_isbn ];
		$preparedStatement = $this->con->prepare($sqlStatement);
		if($preparedStatement->execute($params)){
			$rows = $preparedStatement->fetchAll();
			if(!$rows){
				return null;
			}else{
				foreach($rows as $row){
					$author = Author::loadEmptyAuthor();
					$author->setIdauthor($row['idauthor']); // ERROR HERE, it does not recognize idauthor as a valid index
					$author->setAuthName($row['auth_name']);
					// ADD console log here for debugging, in update author is returning its id 
					// NOT his name
					$authors[] = $author;
				}
				return $authors;
			}

		}else{
			return null;
		}
	}

    /**
     * @return mixed
     */
    public function getDbCon()
    {
        return $this->con;
    }

    /**
     * @param mixed $con
     *
     * @return self
     */
    public function setDbCon($con)
    {
        $this->con = $con;

        return $this;
    }
}
 ?>
