/*
COMP466 Assignment 2: Part 2 - learncity.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show Login screen
function show_prompts(element) {
	document.getElementById(element).style.display = "block";
	hide_errors();
}

// Hide Login screen
function hide(element) {
	document.getElementById(element).style.display = "none";
}

function sign_out() {
	var d = new Date();
    d.setTime(d.getTime() + (-1 * 24 * 60 * 60 * 1000));
	document.cookie = "logged_in=; expires=" + d.toUTCString() + "; username=; expires=" + d.toUTCString();
	window.location = "learncity.php";
}

// Initial setup function
function start() {
	
	if (document.getElementById("login_prompt") !== null) {
		document.getElementById("login_prompt").addEventListener(
		"click", function() {show_prompts("login_inputs");}, false);
	}
	
	if (document.getElementById("register_prompt") !== null) {
		document.getElementById("register_prompt").addEventListener(
		"click", function() {show_prompts("register_inputs");}, false);
	}
	
	if (document.getElementById("close_login") !== null) {
		document.getElementById("close_login").addEventListener(
		"click", function() {hide("login_inputs");}, false);
	}
	
	if (document.getElementById("close_register") !== null) {
		document.getElementById("close_register").addEventListener(
		"click", function() {hide("register_inputs");}, false);
	}

	if (document.getElementById("sign_out") !== null) {
		document.getElementById("sign_out").addEventListener(
		"click", sign_out, false);
	}

}

// Start after page loads
window.addEventListener("load", start, false);