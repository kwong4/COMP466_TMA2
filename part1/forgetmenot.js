/*
COMP466 Assignment 2: Part 1 - forgetmenot.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show Login screen
function show_prompts(element) {
	document.getElementById(element).style.display = "block";
	hide_errors();
}

// Show content
function show_content(element) {
	hide_content();
	document.getElementById(element).style.display = "block";
}

// Hide content
function hide_content() {
	document.getElementById("home_content").style.display = "none";
	document.getElementById("mybookmark_content").style.display = "none";
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

function edit(count) {
	var id = count.substring(8, count.length);
	id = parseInt(id);
	document.getElementById("bookmark_name").value = document.getElementById("item_" + id).innerHTML;
	document.getElementById("url_address").value = document.getElementById("item_" + id).href;
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

	if (document.getElementById("home") !== null) {
		document.getElementById("home").addEventListener(
		"click", function() {show_content("home_content");}, false);
	}

	if (document.getElementById("my_bookmarks") !== null) {
		document.getElementById("my_bookmarks").addEventListener(
		"click", function() {show_content("mybookmark_content");}, false);
	}

	if (document.getElementById("edit_it") !== null) {
		document.getElementById("edit_it").addEventListener(
		"click", edit, false);
	}

	var counter = 0;

	while (document.getElementById("edit_it_" + counter) !== null) {
		document.getElementById("edit_it_" + counter).addEventListener(
		"click", function() {edit(this.id);}, false);

		counter += 1;
	}

}

// Start after page loads
window.addEventListener("load", start, false);