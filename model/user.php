<?php 
	class User{
		private $firstName;
		private $lastName;
		private $email;
		private $hashedPassword;
		private $id;
/*		public function __construct(string $firstName, string $lastName, string $email,
			string $hashedPassword, string $loginName ){
			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->email = $email;
			$this->hashedPassword = $hashedPassword;
			$this->loginName = $loginName;
			$this->id = null;
		}
*/		protected function __construct(){
		}

		public static function loadNewUser(string $firstName, string $lastName, string $email,
			string $hashedPassword, string $loginName){
			$instance = new self();
			$instance->fillNewUser($firstName, $lastName, $email, $hashedPassword, $loginName);
			return $instance;

		}
		protected function fillNewUser(string $firstName, string $lastName, string $email,
			string $hashedPassword, string $loginName){

			$this->firstName = $firstName;
			$this->lastName = $lastName;
			$this->email = $email;
			$this->hashedPassword = $hashedPassword;
			$this->loginName = $loginName;
			$this->id = null;


		}

		public function getFirstName(){
			return $this->firstName;
		}
		public function getLastName(){
			return $this->lastName;
		}
		public function getEmail(){
			return $this->email;
		}
		public function getPassword() : string{
			return $this->hashedPassword;
		}
		public function setEmail(string $newEmail){
			$this->email = $newEmail;
		}
		public function setId(int $newId){
			$this->id= $newId;
		}
		public function getId(){
			return $this->id;
		}
		public function getFullName() : string{
			return $this->firstName ." ". $this->lastName;
		}

	}
 ?>