<?php
	session_start();
	if( !isset($_SESSION['admin'])  ){
			echo $_SESSION['admin'] ;
			header('HTTP/1.1 302 Redirect');
			header("Location: dark_fantasy.php");
			exit(); # Needed because the Location Header can be ignored by user, thus loading this page entirely.
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Admin</title>
	<link rel="stylesheet" href="styles/bootstrap.min.css">
	<link rel="stylesheet" href="styles/custom-layout02.css"> <!-- This one does not work for some reason-->
	<style>
		/*The Following is neccessary to give the hints a "box" else the letters are just floating around*/
		.twitter-typeahead, .tt-hint, .tt-input, .tt-menu {
		 width: 100%;
		 line-height: 30px;
		  border: 2px solid #ccc;
		  -webkit-border-radius: 8px;
		     -moz-border-radius: 8px;
		          border-radius: 8px;
		  outline: none;
		  }
		  .typeahead {
			  background-color: #fff;
			}
		.tt-query {
		  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		}

		.tt-hint {
		  color: #999
		}
		.tt-menu {
		  width: 422px;
		  margin: 12px 0;
		  padding: 8px 0;
		  background-color: #fff;
		  border: 1px solid #ccc;
		  border: 1px solid rgba(0, 0, 0, 0.2);
		  -webkit-border-radius: 8px;
		     -moz-border-radius: 8px;
		          border-radius: 8px;
		  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
		     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
		          box-shadow: 0 5px 10px rgba(0,0,0,.2);
		}

		.tt-suggestion {
		  padding: 3px 20px;
		  font-size: 18px;
		  line-height: 24px;
		}

		.tt-suggestion:hover {
		  cursor: pointer;
		  color: #fff;
		  background-color: #0097cf;
		}	
	</style>
	<?php 
		// Add logic here to redirect to error html if the user does no have the right credentials

		require_once("controller/CategoryDAO.php");
		$catDAO = new CategoryDAO();
		$categories = $catDAO->read();
		//var_dump($categories);
	 ?>
</head>
<body>
	<main class="container" role="main">
		<ul class="nav nav-tabs list-inline">
			<li class="active" id="add-book-link"><a href="#add" role="tab" data-toggle="tab">Add Book</a></li>
			<li id="update-book-link"><a  href="#update" role="tab" data-toggle="tab">Update/Delete Book</a></li>
			<li id="add-category-link"><a href="#add-category" role="tab" data-toggle="tab">Add Category</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active fade in" id="add">
				<form action="controller/add_book.php" role="form" id="add-form" method="POST">
					<div class="form-group col-md-3" >
						<label for="isbn" class="control-label text-center"> ISBN:</label>
						<div ><input required type="text" class="form-control" id="isbn" name="isbn" maxlength="14" pattern="[0-9A-Z]{9,14}?" placeholder="ISBN 10 or 13 numbers"></div>
						<p id="error-isbn" class="form-error"></p>
					</div>
					<div class="form-group col-md-9">
						<label for="title" class="control-label text-center">Title:</label>
						<div ><input  required type="text" class="form-control" id="title" name="title"></div>
						<p id="error-title" class="form-error"></p>
					</div>
					<div class="form-group col-md-6">
						<label for="series" class="control-label  text-center">Series:</label>
						<div ><input type="text" class="form-control" id="series" name="series"></div>
						<p id="error-series" class="form-error"></p>
					</div>
					<div class="form-group col-md-6">
						<label for="text" class="control-label  text-center">URL:</label>
						<div ><input  required type="url" class="form-control" id="url" name="url" placeholder="URL to Amazon"></div>
						<p id="error-url" class="form-error"></p>
					</div>
					<div class="form-group col-md-12">
						<label for="image" class="control-label  text-center">Image:</label>
						<div ><input  required type="text" class="form-control" id="image" name="image" placeholder="URL or Path to image"></div>
						<p id="error-image" class="form-error"></p>
					</div>
					<div class="form-group col-md-12 text-center" id="book-image-wrapper">
						<!-- Add some javascript here to preview the books image-->
						<img src="" alt="book's image" id="book-image" >
					</div>

					<div class="form-group col-md-6" id="authors-input">
						<label for="" class="control-label  text-center">Authors:</label>
						<div ><input  id="authors" required type="text" class="form-control"  name="authors" placeholder="Insert Authors separated by ;"></div>
						<p id="error-authors" class="form-error"></p>
					</div>


					<div class="form-group col-md-12">
							<label for="">Categories:</label>
							<div class="btn-group col-md-12" data-toggle="buttons">
								<?php foreach($categories as $category): ?>
									<label for="" class="btn btn-primary" style="margin-left:1rem;">
										<input type="checkbox" name="categories[]" value=<?php echo "'" .$category->getId()."'"; ?>>
										<?php echo $category->getName(); ?>
									</label>
								<?php endforeach; ?>
							</div>	
					</div>

					<div class="form-group col-md-6 col-md-offset-3">
						<label for="" class="control-label text-center">Review:</label>
						<div ><textarea  class="form-control" id="review" name="review" rows="10"></textarea></div>
						<p id="error-review" class="form-error"></p>
					</div>
					<div class="form-group col-md-4 col-md-offset-4 text-center">
						<button type="button" id="save-add" class="btn btn-default form-control">Save</button>
					</div>

					<div class="form-group">
						<div class="col-md-3 col-md-offset-3"><input type="submit" class="form-control btn-success"  value="Register"></div>
						<p id="error-submit" class="form-error"></p>
					</div>
					<div class="form-group">
						<div class="col-md-3"><input type="reset" class="form-control btn btn-danger"  value="Reset"></div>
					</div>

				</form>
			</div>

			<div class="tab-pane" id="update">
				<div class="col-md-9 col-md-offset-3" >
						<p> Select Search Type: </p>
						<label for="upisbn" class='radio-inline'>
							<input type="radio"  " id="upisbn" name="ustype" value='isbn'>
							ISBN	
						</label>
						<label for="uptitle"  class='radio-inline'>
							<input type="radio"  id="uptitle" name="ustype" value='title' checked>
							Title
						 </label>
						<label for="upseries"  class='radio-inline'>
							<input type="radio"  id="upseries" name="ustype" value='series'>
							Series
						 </label>
					<p id="error-upisbn" class="form-error"></p>
				</div>
				<form action="controller/update_book.php" role="form" id="update-form" method="POST">
					<div class="form-group col-md-12" >
						<label for="up-search" class="control-label text-center"> Search:</label>
						<div>
							<input required type="text" class="form-control " id="upsearch" name="search" placeholder="Input The Book's Title">
						</div>
						<p id="error-upisbn" class="form-error"></p>
					</div>
					<div class="form-group col-md-3" >
						<label for="isbn" class="control-label text-center"> ISBN:</label>
						<div ><input required type="text" class="form-control" id="uisbn" name="isbn" maxlength="14" pattern="[0-9A-Z]{10,14}" placeholder="ISBN 10 or 13 numbers"></div>
						<p id="error-isbn" class="form-error"></p>
					</div>
					<div class="form-group col-md-9">
						<label for="title" class="control-label text-center">Title:</label>
						<div ><input  required type="text" class="form-control" id="utitle" name="title"></div>
						<p id="error-title" class="form-error"></p>
					</div>
					<div class="form-group col-md-6">
						<label for="series" class="control-label  text-center">Series:</label>
						<div ><input type="text" class="form-control" id="useries" name="series"></div>
						<p id="error-series" class="form-error"></p>
					</div>


					<div class="form-group col-md-6">
						<label for="text" class="control-label  text-center">URL:</label>
						<div ><input  disabled required type="url" class="form-control" id="upurl" name="url" placeholder="URL to Amazon"></div>
					</div>
					<div class="form-group col-md-12">
						<label for="image" class="control-label  text-center">Image:</label>
						<div ><input  disabled required type="text" class="form-control" id="upimage" name="image" placeholder="URL or Path to image"></div>
					</div>
					<div class="form-group col-md-12 text-center" id="ubook-image-wrapper">
						<!-- Add some javascript here to preview the books image-->
						<img src="" alt="book's image" id="upbook-image" >
					</div>

					<div class="form-group col-md-6" id="upauthors-input">
						<label for="" class="control-label  text-center">Authors:</label>
						<div ><input  id="upauthors" disabled required type="text" class="form-control"  name="authors"></div>
						<p id="uperror-authors" class="form-error"></p>
					</div>


					<div class="form-group col-md-12">
							<label for="">Categories:</label>
							<div class="btn-group col-md-12" data-toggle="buttons">
								<?php foreach($categories as $category): ?>
									<label for="" class="btn btn-primary" style="margin-left:1rem;">
										<input type="checkbox" name="categories[]" class="update-box" value=<?php echo "'" .$category->getId()."'"; ?>>
										<?php echo $category->getName(); ?>
									</label>
								<?php endforeach; ?>
							</div>	
					</div>

					<div class="form-group col-md-6 col-md-offset-3">
						<label for="" class="control-label text-center">Review:</label>
						<div ><textarea  disabled class="form-control" id="upreview" name="review" rows="10"></textarea></div>
					</div>

					<div class="form-group">
						<div class="col-md-3 col-md-offset-3"><input disabled type="submit" class="form-control btn-success"  value="Commit"></div>
						<p id="error-commit" class="form-error"></p>
					</div>
					<div class="form-group">
						<div class="col-md-3"><input disabled type="reset" class="form-control btn btn-warning"  value="Reset/ClearAll"  ></div>
					</div>
					<div class="form-group">
						<div class="col-md-3"><input id="delete-button" disabled type="button" class="form-control btn btn-danger"  value="Delete"  ></div>
					</div>

				</form>
				
			</div>

			<div class="tab-pane" id="add-category">
				
			</div>
			
		</div>
		
	</main>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
	<script src="scripts/cookieslib.js"></script>
	<script src="scripts/kashim01.js"></script>
	<script src="scripts/typeahead.bundle.js"></script>
	
</body>
</html>