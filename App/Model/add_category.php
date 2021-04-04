<?php
require_once("../../Controller/MDBConnection.php") ;
require_once('../../Controller/security_suit.php'); // Careful Here
require_once($_SERVER['DOCUMENT_ROOT'] . '/model/Category.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/controller/CategoryDAO.php');

if(isset($_POST['new_category'])){
	$temp  = sanitizePost($_POST);
	extract($temp, EXTR_PREFIX_ALL, 'P');
	$newCat =  Category::emptyCategory();
	$newCat->setName($P_new_category);
	$daoObject = new CategoryDAO();
	if($daoObject->create($newCat)){
		echo "successful_addition";
	}
}
?>