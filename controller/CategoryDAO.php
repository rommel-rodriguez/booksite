<?php 
require_once  "MDBConnection.php";
require_once  "DAOInterface.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/Category.php";

class CategoryDAO implements CRUDInterface{

		public function __construct(){
			/**
			 * Need to define Where do i have to 
			 * close this connection for it not just to
			 * keep hanging there
			 * @var [type]
			 */
			$this->con = MDBConnection::getConnection();
		}

		public function create($catObj):bool{
			$exitStatus = false;
			$insertSQL =<<<_SQL_
			insert into category(cat_name) 
				values(:catName);
_SQL_;

			$preparedStatement = $this->con->prepare($insertSQL);
			$params = [
				':catName' => $catObj->getName()
			];
			if($preparedStatement->execute($params)){
				$exitStatus = true;
			}
			return $exitStatus;
		}


		public function read():array{
			/**
			 * This function returns an array "rows" 
			 in which each row is a map column: value of 
			 each row of the table user
			 */
			 $categoriesArray = [];
			 foreach($this->con->query('select * from category') as $row){
			 	$catObj = null;
			 	$catObj =  Category::emptyCategory();
			 	$catObj->setId($row["idcategory"]);
			 	$catObj->setName($row["cat_name"]);
			 	$categoriesArray[] = $catObj;
			 }
			 return $categoriesArray;


		}
		/**
		 * Method: update
		 * Input: Associative Array 
		 * Returns: true if the update was succesfull, false otherwise
		 * @param  "Associative Array" $dict  
		 * Uses the value of $dict["book_isbn"] To find the right record
		 * to update
		 * 
		 */
		public function update($dict):bool{

			throw NotIplementedError();	

		}

		public function delete($deleteCriteria):int{
			/**
			 * Deletes rows of the table user
			 * Returns: The number of rows deteled
			 * or zero if no row where delete 
			 * -1 if there was some error
			 * @var []
			 * NEED TO FIGUREOUT WHAT THE RIGHT 
			 PARAMENTER IS!
			 */
			throw NotIplementedError();	
		}

		/**
		 * 	findAll
		 * 	This funtion looks for a given isbn
		 * 	number inside the database an returns a Book
		 * 	object on Success finding it.
		 * 	Returns False Otherwise.
		 * 	@param int $isbn
		 */
		public function findAll($isbn){
			throw NotIplementedError();	
		}

}

 ?>