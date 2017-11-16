/*
COMP466 Assignment 2: Part 1 - forgetmenot.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show Login screen
function login_menu() {
	document.getElementById("login_inputs").style.display = "block";
}

// Hide Login screen
function hide_login_menu() {
	document.getElementById("login_inputs").style.display = "none";
}

// Initial setup function
function start() {
	
	document.getElementById("login_prompt").addEventListener(
		"click", login_menu, false);

	document.getElementById("close_login").addEventListener(
		"click", hide_login_menu, false);

	// Get the modal
	var modal = document.getElementById('login_inputs');

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	}
}

// Start after page loads
window.addEventListener("load", start, false);