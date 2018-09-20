<?php 
	//include($_SERVER["DOCUMENT_ROOT"] . '/nuggets.php');
	class MDBConnection{
		public function __construct(){
			die("Init function is not allowed")	;
		}
		public static function getConnection(){
			// THIS CREDENTIALS SHOULD NOT BE INSIDE THE PUBLIC
			// Directory
			$cred_file = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/bp_cred.webhost.json');
			$cred_map = json_decode($cred_file, true);
			//$cred_map = $nuggets;
			try{
				$db = new PDO(
					'mysql:host=localhost; dbname=id2369571_book_project',
					$cred_map['user'],
					$cred_map['password']
				);	
				if(!$db){
					$error = $db->errorInfo()[2];
					error_log(date(DATE_RSS) ."Could not connect to the database \n$error\n");
				}

				$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				// FOR this to work i must grant apache2 permission to write to this folder or 
				// write to its own folder logs, which i dont know how to do
				// error_log(date(DATE_RSS) . "Connection Success\n"); // ONLY USE THIS FOR DEBUGGING
				#error_log( date(DATE_RSS) . "Connection Success", 3 ,"/var/log/apache2/bp_success.log");
				return $db;
			}catch(Throwable $e){
				error_log("Error $e\n");
				#error_log("Error $e" , 3 ,"../../bp_error.log");
				//echo "Error $e";
			}
			return false;
		}	

		public static function closeConnection(){
			$db = null;
		}
	}

 ?>
