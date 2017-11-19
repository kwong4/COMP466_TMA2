/*
COMP466 Assignment 2: Part 1 - forgetmenot.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show Login screen
function show(element) {
	document.getElementById(element).style.display = "block";
	hide_errors();
}

// Hide Login screen
function hide(element) {
	document.getElementById(element).style.display = "none";
}

function hide_errors() {
	document.getElementById("error_login").style.display = "none";
	document.getElementById("error_register1").style.display = "none";
	document.getElementById("error_register2").style.display = "none";
}

// Initial setup function
function start() {
	
	document.getElementById("login_prompt").addEventListener(
		"click", function() {show("login_inputs");}, false);

	document.getElementById("register_prompt").addEventListener(
		"click", function() {show("register_inputs");}, false);

	document.getElementById("close_login").addEventListener(
		"click", function() {hide("login_inputs");}, false);

	document.getElementById("close_register").addEventListener(
		"click", function() {hide("register_inputs");}, false);

}

// Start after page loads
window.addEventListener("load", start, false);