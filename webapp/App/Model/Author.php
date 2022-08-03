<?php 
class Author{
	private $idauthor;	
	private $auth_name;	

	protected function __construct(){
	}

	public static function loadNewAuthor(int $idauthor, string $auth_name){
		$newInstance = new self();
		$newInstance->fillNewAuthor($idauthor, $auth_name);
	}
	protected function fillNewAuthor(int $idauthor, string $auth_name){
		$this->idauthor = $idauthor;
		$this->auth_name = $auth_name;
	}
	public static function loadEmptyAuthor(){
		return new self();	
	}

    /**
     * @return mixed
     */
    public function getIdauthor()
    {
        return $this->idauthor;
    }

    /**
     * @param mixed $idauthor
     *
     * @return self
     */
    public function setIdauthor($idauthor)
    {
        $this->idauthor = $idauthor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthName()
    {
        return $this->auth_name;
    }

    /**
     * @param mixed $auth_name
     *
     * @return self
     */
    public function setAuthName($auth_name)
    {
        $this->auth_name = $auth_name;

        return $this;
    }
}

 ?>