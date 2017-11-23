/*
COMP466 Assignment 2: Part 2 - course.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show content
function show_content(element) {
	hide_content();
	document.getElementById(element).style.display = "block";
}

// Hide content
function hide_content() {

	var counter = 0;

	while (document.getElementById("unit_content_" + counter) !== null) {
		document.getElementById("unit_content_" + counter).style.display = "none";

		counter++;
	}

	if (document.getElementById("home_content") !== null) {
		document.getElementById("home_content").style.display = "none";
	}
}

// Initial setup function
function start() {

	show_content("home_content");

	var counter = 0;

	if (document.getElementById("home") !== null) {
		document.getElementById("home").addEventListener(
			"click", function() {show_content("home_content");}, false);
	}

	while (document.getElementById("unit_" + counter) !== null) {
		document.getElementById("unit_" + counter).addEventListener(
		"click", function() {show_content(this.getAttribute("content"));}, false);

		counter++;
	}

}

// Start after page loads
window.addEventListener("load", start, false);