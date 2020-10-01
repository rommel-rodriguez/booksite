<?php 
	$title = "Fantasy";
	include 'header.html';
	require_once 'Controller/BookDAO.php';
	require_once 'Model/Book.php';
	$bookDAO = new BookDAO();
	$categoryBooks = [];

	# I NEED ALL OF EACH BOOK'S CATEGORIES!!!!!

		// some Validity Code Here
		// purposely not sanitized POST
	$categoryBooks = $bookDAO->findBooksInCategory('fantasy');

	$bookList = [];
	foreach($categoryBooks as $book){
		$bookItem = Book::loadEmptyBook();
		$bookItem->setIsbn($book['book_isbn']);
		$bookItem->setTitle($book['book_title']);
		$bookItem->setImage($book['book_img']);
		$bookItem->setSeries($book['book_series']);
		$bookItem->setUrl($book['book_url']);
		$bookList[] = $bookItem;
	}

	foreach ( $bookList as $book){
		$bookReview = "";
		try {
			$bookReview = file_get_contents("content/{$book->getIsbn()}"); // The @ Suppresses the warning in the error log in case of 
			// FILE NOT FOUND WARNING
		} catch (Exception $e) {
		    //echo 'Caught exception: ',  $e->getMessage(), "\n";
		    error_log("Caught: $e");
		}
		if(!empty($bookReview)){
			$book->setReview($bookReview);
		}else{
			$book->setReview("There isn't a review for this Book yet.");
		}
		$book->setCategories($bookDAO->getBookCategories($book->getIsbn()));
	}

 ?>	
	<div class="row">
		<?php foreach ($bookList as $book):  ?>
			<div class="container-fluid">
				<section class="row book-item">
					<header class="col-md-12 text-center book-title"> <h3><?php echo $book->getTitle(); ?></h3></header>
					<?php if(!empty($book->getSeries())): ?>
						<section class="book-series text-center"><h4>Series: <?php echo $book->getSeries(); ?></h4></section>
					<?php endif; ?>

					<a href=<?php echo $book->getUrl() ?>  class="link-image float-left" target="_blank">
						<img src=<?php echo $book->getImage(); ?>  style="max-height: 400px;" alt="Image of book" title="buy in Amazon" class="book-image">
					</a>
					<p class="book-review"><?php echo  $book->getReview(); ?></p>
					<section class="categories col-md-12">
						<?php foreach ($book->getCategories() as  $category): ?>
							<?php $category_uri = $category ?>
							<?php if(preg_match('/\w\s+\w/',"$category")): $category_uri = preg_replace( '/(\w)\s+(\w)/', '${1}_${2}' , $category); endif; ?>
							<a href=<?php echo "'" . $category_uri . ".php'" ; ?> class="btn btn-success"><?php echo $category ?></a>
						<?php endforeach; ?>
					</section>	
				</section>
			</div>
		<?php endforeach ; ?>
	</div>

<?php include 'footer.html'; ?>	
