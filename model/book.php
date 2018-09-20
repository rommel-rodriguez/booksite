<?php 

class Book{
	private $isbn;
	private $title;
	private $image;
	private $series;
	private $url;
	private $categories; // This one should be an Array
	private $review;

	protected function __construct(){
	}

	public static function loadNewBook(string $isbn, string $title, string $image,
	 string $series, string $url,  $categories = null){
		$instance = new self();
		$instance->fillNewBook($isbn, $title, $image, $series, $url, $categories);
		return $instance;

	}
	protected function fillNewBook(string $isbn, string $title, string $image,
	 string $series, string $url,  $categories){

		$this->isbn= $isbn;
		$this->title = $title;
		$this->image = $image;
		$this->series = $series;
		$this->url = $url;
		$this->categories = $categories;
	}

	public static function loadEmptyBook(){
		return new self();		
	}
	
    /**
     * @return mixed
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * @param mixed $isbn
     *
     * @return self
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param mixed $series
     *
     * @return self
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     *
     * @return self
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     *
     * @return self
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }
}
 ?>