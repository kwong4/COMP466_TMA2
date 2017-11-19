/*
COMP466 Assignment 2: Part 1 - forgetmenot.js
Name: Kevin Wong							
ID: 3339323								
*/

function setCookie(cname, cvalue, exdays) {
    
    var expires = "expires = " + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

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

function sign_out() {
	var d = new Date();
    d.setTime(d.getTime() + (-1 * 24 * 60 * 60 * 1000));
	document.cookie = "logged_in=; expires=" + d.toUTCString() + "; username=; expires=" + d.toUTCString();
	window.location = "forgetmenot.php";
}

// Initial setup function
function start() {
	
	if (document.getElementById("login_prompt") !== null) {
		document.getElementById("login_prompt").addEventListener(
		"click", function() {show("login_inputs");}, false);
	}
	
	if (document.getElementById("register_prompt") !== null) {
		document.getElementById("register_prompt").addEventListener(
		"click", function() {show("register_inputs");}, false);
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