/*
COMP466 Assignment 2: Main Page - tma.js
Name: Kevin Wong							
ID: 3339323								
*/

// Hide the content in the page
function hideContent() {

	// Hide the content div elements of the page
	document.getElementById("home_content").setAttribute("class", "hidden");

	document.getElementById("forgetmenot_content").setAttribute("class", "hidden");

	document.getElementById("learncity_content").setAttribute("class", "hidden");

}

// Hide the content in the page
function showContent(id) {

	// Hide the content div elements of the page
	hideContent();

	// Show the correct content
	document.getElementById(id).setAttribute("class", "assignment_parts");
}


// Initial setup function
function start() {
	
	// Clear current content
	hideContent();

	// Show the home content
	showContent("home_content");

	document.getElementById("home").addEventListener("click", 
		function() {showContent("home_content");}, false);

	document.getElementById("tab_1").addEventListener("click", 
		function() {showContent("forgetmenot_content");}, false);

	document.getElementById("tab_2").addEventListener("click", 
		function() {showContent("learncity_content");}, false);

}

// Start after page loads
window.addEventListener("load", start, false);