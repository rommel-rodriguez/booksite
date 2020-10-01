<?php 
	require_once  "MDBConnection.php";
	require_once  "../Model/User.php";
	require_once  "CRUDInterface.php";
	class UserDAO implements CRUDInterface{
		public function __construct(){
			/**
			 * Need to define Where do i have to 
			 * close this connection for it not just to
			 * keep hanging there
			 * @var [type]
			 */
			$this->con = MDBConnection::getConnection();
		}

		public function create($userObj):bool{
			//Change this for prepared statements 
			// Prepared statements even remove the necesity for mysql/mariadb real_escape_string 
			// function to sanitize database queries
			$userAddQuery =<<<_EOF_
		insert into 
		test_user(first_name, last_name, email, hashed_pass) 
		values(:firstName, :lastName, :email, :password);
_EOF_;
	
			// Connection is returning Void without raising exeptions
			// Correct this behavior
			$preparedStatement = $this->con->prepare($userAddQuery);
			$params  = [
				'firstName' => $userObj->getFirstName(),
				'lastName'	=> $userObj->getLastName(),
				'email'		=> $userObj->getEmail(),
				'password'  => $userObj->getPassword()
			];
			try{
				$preparedStatement->execute($params);
				return true;
			}catch(Throwable $e){
				echo "Error: $e";
				return false;
			}
		}


		public function read():array{
			/**
			 * This function returns an array "rows" 
			 in which each row is a map column: value of 
			 each row of the table User
			 */
			 return $this->con->query('select * from test_user');

		}
		public function update($userObj):bool{
			/**
			 * Input: User Object
			 * Returns: true if the update was succesfull, false otherwise
			 */
			$updateQuery =<<<_SQL_
			UPDATE 
			test_user SET
			first_name = :firstName,
			last_name = :lastName,
			hashed_pass = :hashedPassword	
			WHERE
			id = :id;
_SQL_;
			$preparedStatement = $this->con->prepare($updateQuery);
			$params  = [
				'firstName' => $userObj->getFirstName(),
				'lastName'	=> $userObj->getLastName(),
				'email'		=> $userObj->getEmail(),
				'password'  => $userObj->getPassword(),
				'id'  => $userObj->getId()
			];
			try{
				$preparedStatement->execute($params);
				return true;
			}catch(Throwable $e){
				return false;
			}


		}
		public function delete($userId):int{
			/**
			 * Deletes rows of the table User
			 * Returns: The number of rows deteled
			 * or zero if no row where delete 
			 * -1 if there was some error
			 * @var []
			 * NEED TO FIGUREOUT WHAT THE RIGHT 
			 PARAMENTER IS!
			 */
			$rowsDeleted = 0;
			$deleteQuery =<<<_SQL_
			DELETE	
			FROM
			test_user
			WHERE
			id = :id;
_SQL_;
			$preparedStatement = $this->con->prepare($deleteQuery);
			$params  = [
				'id'  => $userId
			];
			try{
				$rowsDeleted = $preparedStatement->execute($params);
			}catch(Throwable $e){
				$rowsDeleted = -1;
			}
			return $rowsDeleted;
		}

		public function findAll(){
			throw NotIplementedError();	
		}

		public function validateUser($userMail, $userPass){
			$validateQuery=<<<_SQL_
			SELECT	*
			FROM
			test_user
			WHERE
			email = :user_email
			hashed_pass = :user_pass
			;
_SQL_;
			$preparedStatement = $this->con->prepare($validateQuery);
			$params = ['user_mail' => $userMail, 'user_pass' => $userPass];
			# Hopefully it only returns a row ...
			$userRow = $preparedStatement->execute($params);

			if($userRow){
				$userObj = new User();
				$userObj = (object) $userRow;
			}else{
				return false;
			}
		}
	}
 ?>