<?php
namespace Malaz\Booksite\Dummy;
Class Test{

	public function __construct(){
	}
    public function just_hello(){
        $my_var = __DIR__;
        echo "<h1>Hello from {$my_var}</h1>";
    }    
}

?>