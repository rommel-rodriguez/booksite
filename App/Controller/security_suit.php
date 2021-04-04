
<?php 

	function sanitizeString($var): string{
		$var = stripslashes($var);
		$var = strip_tags($var);
		$var = htmlentities($var, ENT_QUOTES, 'UTF-8');
		return $var;
	}
	function sanitizeArray($myArray):array{
		for($i = 0; $i < count($myArray); $i++){
			$myArray[$i] = sanitizeString($myArray[$i]);
		}
		return $myArray;
	}

	function sanitizePost($postValues): array{
		/**
		 * This function should user recursion to sanitize all
		 * values regardless of depth in the post array
		 * and return and mutate $_POST? or return another array?
		 */
		//Throw NotIplementedError();
		foreach($postValues as $key => $value){
			$postValues[$key] = sanitizeString($value);
		}
		return $postValues;
	}

	function sanitizePostWithArray($postValues):array{
		// Value can ve an array so ...
		foreach($postValues as $key => $value){
			if(is_string($value)){
				$postValues[$key] = sanitizeString($value);
			}else{
				$postValues[$key] = sanitizeArray($value);
			}
		}
		return $postValues;

	}

 ?>