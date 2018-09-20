var lastScroll = 0;
var headerHeight = 0;
function init(){
	var myHeader = document.querySelector('#main-header');
	var myMain = document.querySelector('main')
	var myFooter =  document.querySelector('footer')
	var docBody =  document.querySelector('body')
	headerHeight = myHeader.clientHeight

	window.addEventListener("scroll", fadeWrapper)
	/*
		From Stackoverflow	how to get the height of a document
	 */
	var body = document.body,
	    html = document.documentElement;

	var height = Math.max( body.scrollHeight, body.offsetHeight, 
	                       html.clientHeight, html.scrollHeight, html.offsetHeight );	
	/*
		end Stackoverflow	
	 */

	var mainHeight = height - (myHeader.clientHeight + myFooter.clientHeight)
	/*document.write(docBody.clientHeight+ 'px')*/
	myMain.style.marginTop = myHeader.clientHeight + "px" 
	myMain.style.minHeight = mainHeight + 'px'
}


function fadeHeader(e){
	var myHeader = document.querySelector('#main-header');
	var docBody =  document.querySelector('body')
	var myMain = document.querySelector('main')
	if(e.pageY > lastScroll){
		myHeader.style.display = "none"
		console.log(headerHeight)
		//myHeader.style.height = "0px"
		myMain.style.marginTop = "0px"
		lastScroll = e.pageY
	}else{
		myHeader.style.display = "block"
		//myHeader.style.height = "auto"
		console.log(headerHeight)
		myMain.style.marginTop = headerHeight + "px"
		lastScroll = e.pageY
	}
}

function fade(el, type, time){
	jsObj = document.getElementById(el)
	var isIn = type === 'in',
	    opacity = isIn ? 0 : 1,
	    interval = 50,
	    duration = time,
	    gap = interval / duration;	

	if(isIn) {
		jsObj.style.display = 'block';
		jsObj.style.opacity = opacity;
	}

	function func() {
	    opacity = isIn ? opacity + gap : opacity - gap;
	    jsObj.style.opacity = opacity;

	    if(opacity <= 0) jsObj.style.display = 'none'
	    if(opacity <= 0 || opacity >= 1) window.clearInterval(fading);
	  }

	  var fading = window.setInterval(func, interval);	
}
function fadeWrapper(e){
	var myHeader = document.querySelector('#main-header');
	var docBody =  document.querySelector('body')
	var myMain = document.querySelector('main')

	if(e.pageY > lastScroll){
		fade('main-header', 'out', 500)
		console.log(headerHeight)
		//myHeader.style.height = "0px"
		myMain.style.marginTop = "0px"
		lastScroll = e.pageY
	}else{
		fade('main-header', 'in', 500)
		//myHeader.style.height = "auto"
		console.log(headerHeight)
		myMain.style.marginTop = headerHeight + "px"
		lastScroll = e.pageY
	}

	//fade('main-header', 'out', 500)
}
