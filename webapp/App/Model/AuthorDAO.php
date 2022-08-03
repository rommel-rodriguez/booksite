<?php 
require_once  "../../Controller/MDBConnection.php";
require_once  "../../Controller/CRUDInterface.php";
require_once  $_SERVER['DOCUMENT_ROOT'] . "/model/Author.php";
class AuthorDAO implements CRUDInterface{
		public function __construct(){
			/**
			 * Need to define Where do i have to 
			 * close this connection for it not just to
			 * keep hanging there
			 * @var [type]
			 */
			$this->con = MDBConnection::getConnection();
		}

		public function create($authObj):bool{
			$exitStatus = false;
			$insertSQL =<<<_SQL_
			insert into author(auth_name) 
				values(:auth_name);
_SQL_;

			$preparedStatement = $this->con->prepare($insertSQL);
			$params = [
				':auth_name' => $authObj->getAuthName()
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
			 return $this->con->query('select * from book');

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
		public function findAll($name){
			$simple_query =<<<_SQL_
			SELECT * FROM author WHERE auth_name = :authName;		
_SQL_;
			$authRows = [];
			$preparedStatement = $this->con->prepare($simple_query);
			$params = [':authName' => $name];
			if($preparedStatement->execute($params)){
				$authRows = $preparedStatement->fetchAll();
				if(! $authRows){
					return NULL;
				}
			}else{
				error_log("Custom SHADOW LOG: Row Retrieval Not succesfull");
			}
			//TODO: Pass the Fetched row to the Book::FillNewBook
			$foundAuthor = Author::loadEmptyAuthor();
			$foundAuthor->setIdauthor($authRows[0]['idauthor']);
			$foundAuthor->setAuthName($authRows[0]['auth_name']);
			return $foundAuthor;
		}
}

 ?>