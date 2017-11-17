/*
COMP466 Assignment 2: Part 1 - forgetmenot.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show Login screen
function show(element) {
	document.getElementById(element).style.display = "block";
}

// Hide Login screen
function hide(element) {
	document.getElementById(element).style.display = "none";
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

	document.getElementById('login_inputs').addEventListener("click",
		function() {this.style.display = "none";}, false);

	document.getElementById('register_inputs').addEventListener("click",
		function() {this.style.display = "none";}, false);;

}

// Start after page loads
window.addEventListener("load", start, false);