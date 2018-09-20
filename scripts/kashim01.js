
$(document).ready(function(){
	addSubmitListener();
	addUpdateSubmitListener();
	addDeleteButtonListener();
	checkCookie();
	addImageListener();
	addSaveAddListener();
	addUpdateTabListener();
	addRadioListener();
	addUpdateSearchKeyListener();
});

// var imageInputValue = "";
var isbns = [];
var titles = [];
var series = []; 

/** 
 * Listener to override the default submit button's function
 * and instead use a background post request to avoid reloading the 
 * page.
 * JSDoc3 style documentation.
 */
function addSubmitListener(){
	// Nothe that if some error happens here the default submit button's 
	// functionality is used.
	var submitInput = document.querySelector('#add-form input[type="submit"]');	
	submitInput.addEventListener('click', function (event){
		var formCats = document.querySelectorAll('#add-form input[name="categories[]"]:checked');
		var catList = [];
		formCats.forEach( function(element, index) {
			catList.push(element.value);
		});
		// console.log("Categories List");
		// console.log(catList);
		var formData = {
			isbn:  document.querySelector('#isbn').value,
			title: document.querySelector('#title').value,
			series:document.querySelector('#series').value,
			url:   document.querySelector('#url').value,
			image: document.querySelector('#image').value,
			authors: document.querySelector('#authors').value,
			categories: catList,
			review: document.querySelector('#review').value
		};
		jqXHRObject = $.post('/controller/add_book.php', formData, function (data, status){
			console.log("Debugging Hacked Submit Method: ",data);
			if(data.trim() != "successful_addition"){
				alert("Some Error Has Happened, could NOT add the book. [TODO: add error info]");
			}else{
				alert("Book: "+formData.title+" added successsfully.");
			}
		});
		event.preventDefault();

	});
}


function addUpdateSubmitListener(){
	// Nothe that if some error happens here the default submit button's 
	// functionality is used.
	var submitInput = document.querySelector('#update-form input[type="submit"]');	
	submitInput.addEventListener('click', function (event){
		var formCats = document.querySelectorAll('#update-form input[name="categories[]"]:checked'); // ERROR HERE, May need to modify it
		// for it to target .active class according to bootstrap documentation.
		var catList = [];
		formCats.forEach( function(element, index) {
			catList.push(element.value);
		});
		var formData = {
			isbn:  document.querySelector('#uisbn').value,
			title: document.querySelector('#utitle').value,
			series:document.querySelector('#useries').value,
			url:   document.querySelector('#upurl').value,
			image: document.querySelector('#upimage').value,
			authors: document.querySelector('#upauthors').value,
			categories: catList, // ERROR HERE, this one is not being send for some reason
			//review: document.querySelector('#update-form input[name="review"]').value
			review: document.querySelector('#upreview').value
		};
		jqXHRObject = $.post('/controller/update_book.php', formData, function (data, status){
			console.log("Debugging Hacked Submit Method For Update: ",data);
			if(data.trim() != 'successful_update'){  // Make sure to trim EVERYTHING or all hell breaks loose!.
				alert("Some Error Has Happened, could NOT update the book. [TODO: add error info]");
				console.log("Debugging Update Submit Listener's Feedback ", typeof(data));
			}else{
				alert("Book: "+ formData.title +" updated successsfully.");
			}
		});
		event.preventDefault();

	});
}

/**
 *  Sends the necessary information to delete_book.php
 *  so we can uniquely indentify and delete a book from the database.
 */
function addDeleteButtonListener(){
	// Nothe that if some error happens here the default submit button's 
	// functionality is used.
	var deleteButton = document.querySelector('#delete-button');	
	deleteButton.addEventListener('click', function (event){
		// To delete, i only really need the book's table primary key.
		var bookTitle = document.querySelector('#uptitle');
		var formData = {
			isbn:  document.querySelector('#uisbn').value,
		};
		jqXHRObject = $.post('/controller/delete_book.php', formData, function (data, status){
			console.log("Debugging Hacked Submit Method For Delete: ",data);
			if(data != "OK"){ // TODO: CHANGE STRING FLAG RETURNED BY UPDATE_BOOK HERE!!!!!!!!!
				alert("Some Error Has Happened, could NOT delete the book. Data: " + data );
				console.log("Debugging Update Submit Listener's Feedback ", data);
			}else{
				alert("Book: "+ bookTitle +" deleted successsfully.");
			}
		});

	});

}

/** 
 * Checks for values of the input field of the add book form
 * then sets their values to their cookie's counter parts. 
 * @return {void} 
 */
function checkCookie(){
	//var exclude = /(.*categories.*)|(.*authors.*)/;
	var exclude = /.*categories.*/;
	if(document.cookie){
		var addInputs = document.querySelectorAll('#add input');	
		addInputs.forEach(function (element, index){
			console.log(element.getAttribute('name'));
			if((!exclude.exec( element.getAttribute('name')))  &&  element.getAttribute('type') != 'reset'
				&& element.getAttribute('type') != 'submit' ){
				element.value = getCookie(element.getAttribute('name'));	
			}
		});
	}
}



// Image Listener Section
function addImageListener(){
	var imageInput = document.querySelector('#image');
	imageInput.addEventListener("blur", loadBookImage);
}

function loadBookImage(event){
	var target = event.target ;
	var imageURL = target.value ; // Can NOT get the value of an input with getAttribute function
	//console.log('Image Url = ' + imageURL);
	var imageObj = document.querySelector('#book-image');
	imageObj.setAttribute('src', imageURL);
}

function addSaveAddListener(){
	var saveButton = document.querySelector('#save-add');	
	var submitButton = document.querySelector('#add input[type=submit]');	
	console.log('Debugging Save Add Listener');
	saveButton.addEventListener('click', saveInputsToCookie);
	submitButton.addEventListener('click', saveInputsToCookie);

}
function saveInputsToCookie(event){
		// TODO: Thinking on saving the  review aswell.
		console.log('MORE Debugging Save Add Listener');
		var addInputs = document.querySelectorAll('#add input');
		addInputs.forEach(function(element, index){
			setCookie(element.getAttribute('name'), element.value, 7);
		});
		console.log(document.cookie);
}

// SEARCH HELP SECTION
function addUpdateTabListener(){
	var itemObj = document.querySelector('#update-book-link');	
	itemObj.addEventListener('click', ajaxAcquireSearch);
}

function ajaxAcquireSearch(event){
	// var addBookLink = document.querySelector('#add-book-link')	
	// var updateBookLink = document.querySelector('#update-book-link')	
	// addBookLink.removeAttribute('class')
	// updateBookLink.setAttribute('class', 'active')
	if(isbns.length == 0){
		console.log('About To Perform AJAX POST Request');
		jqXHRObject = $.post('/controller/autoComplete.php', fillSearchArrays);
		jqXHRObject.done(throneTwitterTypeahead);
	}
}


function fillSearchArrays(data, status){
	var searchArrays = JSON.parse(data);
	isbns = searchArrays[0];
	titles = searchArrays[1];
	series = searchArrays[2];
	// searchArrays[1].forEach(function (element, index){
	// 	titles.push(decodeURIComponent(element));
	// });
	// searchArrays[2].forEach(function (element, index){
	// 	series.push(decodeURIComponent(element));
	// });
}
function throneTwitterTypeahead(){
	var searchType = document.querySelector('input[name=ustype]:checked').value;
    // Constructing the suggestion engine
    searchArray = []; // Add some logic here to select the right one
    switch(searchType){
    	case 'isbn': searchArray = isbns;
    	break;
    	case 'title': searchArray = titles;
    	break;
    	case 'series': searchArray = series;
    	break;
    	default: alert('Not A Valid Search Type');
    	break;
    }

    searchArray = searchArray.map(function(element, index){
 		return $('<textarea />').html(element).text(); // Return to normal htmlized names.
    });
    $('#upsearch').typeahead('destroy');

    typeheadSearch = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: searchArray 
    });


    // Initializing the typeahead
    $('#upsearch').typeahead({
        hint: true,
        highlight: true, /* Enable substring highlighting */
        minLength: 1 /* Specify minimum characters required for showing suggestions */
    },
    {
        name: 'typeSearch',
        source: typeheadSearch
    });
}

function addUpdateSearchKeyListener(){
	//var inputList = document.querySelectorAll('.typeahead');
	var inputList = [];
	var searchInput = document.querySelector('#upsearch');
	searchInput.addEventListener("keyup", fillUpdateFields);
}

function fillUpdateFields(event){
	console.log('Inside fillUpdateFields, Event Key:');
	console.log(event.key);
	var searchType = document.querySelector('input[name=ustype]:checked').value;
	var inputObj = event.target;
	if(event.key == "Enter" && event.target.value){
		var searchData = {name : '', value : ''};
		switch (searchType) {
			case "isbn":
				console.log('=========> Search by ISBN <==========');
				searchData['name'] = "isbn";
				searchData.value = inputObj.value;
				jqXHRObject = $.post('/controller/update_lookup.php', searchData, fillUpdateInputs);
				// statements_1
				break;
			case "title":
				console.log('=========> Search by Title<==========');
				searchData['name'] = "title";
				searchData.value = inputObj.value;
				console.log('DEBUGGING: searchData: ');
				console.log(searchData);
				jqXHRObject = $.post('/controller/update_lookup.php', searchData,  fillUpdateInputs);
				// statements_1
				break;
			case "series":
				console.log('=========> Search by Series<==========');
				searchData['name'] = "series";
				searchData.value = inputObj.value;
				jqXHRObject = $.post('/controller/update_lookup.php', searchData,  fillUpdateInputs);
				// statements_1
				break;
			default:
				// statements_def
				alert("Unexpected Update Form's Input Name ");
				break;
		}
	}
}

function fillUpdateInputs(data, status){
	console.log('========= DEBUGGING: fillUpdateInputs =======')
	console.log('Current data: ' + data)
	console.log('Data lenght: ' + String(data.length));

	if(data.length)
		var book_data = JSON.parse(data);
	else
		console.log('Book Data could not be retrieved');

	if(book_data){
		var update_inputs = document.querySelectorAll('#update input');
		var description_input = document.querySelector('#update textarea');
		description_input.removeAttribute('disabled');
		if(book_data['description'])
			description_input.value = book_data['description'];
		for(var key in book_data){
			book_data[key] = $('<textarea />').html(book_data[key]).text(); // Return to normal htmlized names.
		}	
		update_inputs.forEach(function (element, index){
			element.removeAttribute('disabled');
			switch(element.getAttribute('name')){
				case 'isbn': element.value = book_data['isbn'];
					break;
				case 'title': element.value = book_data['title'];
					break;
				case 'series': element.value = book_data['series'];
					break;
				case 'url': element.value = book_data['url'];
					break;
				case 'image': element.value = book_data['image'];
					break;
				case 'authors': element.value = book_data['authors'];
					break;
				case 'categories[]': 
					// The following method "includes" may need a polyfill in for some browsers
					console.log("DEBUGGING: Inside case 'categories[]' elment value: "+element.value);
					console.log("DEBUGGING: Inside case 'categories[]' : checking server-returned cat array"
						+ book_data['categories']);
					if(book_data['categories'].includes(element.value)){
						console.log("\t\tDEBUGGING: Inside case 'categories[]--> inside if condition'");
						element.setAttribute('checked', "true");
						//element.checked = true;
						parentLabel = element.parentNode;
						parentLabel.classList.add('active');
						//element.checked = "checked";
					}
					break;
				default:  console.log('Update Input Field Not  Handled: ' + element.getAttribute('name') 
										+ element.getAttribute('id') + ' '
										+ element.getAttribute('type'));
					break;
			}
		});
		//console.log(book_data);
	}else{
		alert("Could Not Retrieve the Book's Information");
	}
}

// function addAutoCompleteListener(){
// 	// Adds a listener fot ALL search fields
// }



function addRadioListener(){
	var uradioInputs = document.querySelectorAll('input[name=ustype]');
	if(!uradioInputs){alert('Radio Inputs Not Loaded, Debugg!!');};
	uradioInputs.forEach(function(el, index){
		el.addEventListener('click', radioListener);	
	});
}

function radioListener(event){
	var upsearch = document.querySelector('#upsearch');
	switch(event.target.value){
		case 'isbn':  upsearch.value = '';
		upsearch.setAttribute('placeholder', 'Input The Book\'s ISBN Number') ;
		throneTwitterTypeahead();
		break;
		case 'title': upsearch.value = '';
		upsearch.setAttribute('placeholder', "Input The Book's Title");
		throneTwitterTypeahead();
		break;
		case 'series':  upsearch.value = '';
		upsearch.setAttribute('placeholder', "Input The Book's Series");
		throneTwitterTypeahead();
		break;
		default:;
		break;
	}
}

