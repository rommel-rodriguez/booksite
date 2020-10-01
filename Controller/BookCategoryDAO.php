<?php 
require_once  "MDBConnection.php";
require_once  "CRUDInterface.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/Category.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/book.php";

class BookCategoryDAO{
	private $dbCon;
	public function __construct(){
		$this->dbCon = MDBConnection::getConnection();
	}

	public function create(string $book_isbn, int $cat_id){
		//$exitStatus = false;
		$insertSQL =<<<_SQL_
		insert into book_category(book_isbn, idcategory) 
			values(:isbn, :idcategory);
_SQL_;

		$preparedStatement = $this->dbCon->prepare($insertSQL);
		$params = [
			':isbn' => $book_isbn,
			':idcategory' => $cat_id 
		];
		//if($preparedStatement->execute($params)){
		//	$exitStatus = true;
		//}
		return $preparedStatement->execute($params);

	}

	public function delete(string $book_isbn, int $cat_id){
		//$exitStatus = false;
		$deleteSQL =<<<_SQL_
		delete from book_category
			where book_isbn = :isbn and idcategory = :idcategory;	
_SQL_;

		$preparedStatement = $this->dbCon->prepare($insertSQL);
		$params = [
			':isbn' => $book_isbn,
			':idcategory' => $cat_id 
		];
		//if($preparedStatement->execute($params)){
		//	$exitStatus = true;
		//}
		return $preparedStatement->execute($params);
	}
    /**
     * @return mixed
     */
    public function getDbCon()
    {
        return $this->dbCon;
    }

    /**
     * @param mixed $dbCon
     *
     * @return self
     */
    public function setDbCon($dbCon)
    {
        $this->dbCon = $dbCon;

        return $this;
    }
}
?>
