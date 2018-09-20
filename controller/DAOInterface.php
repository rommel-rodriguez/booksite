<?php 
	interface CRUDInterface{
		public function create($obj): bool;
		public function read():array;
		public function update($obj): bool;
		public function delete($itemId): int;
		public function findAll($obj);

	}
 ?>