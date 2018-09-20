<?php 
	function pdoErrorString($pdoCon):string{
		$arrayInfo = $pdoCon->errorInfo();
		$errorString = "THIS PDO ERROR";
		foreach($arrayInfo as $info){
			$errorString += "<br> {$info}";
		}
		return $errorString;
	}

 ?>