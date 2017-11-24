/*
COMP466 Assignment 2: Part 2 - learncity.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show content
function show_content(element) {
	hide_content();
	document.getElementById(element).style.display = "block";
}

// Hide all content
function hide_content() {
	document.getElementById("home_content").style.display = "none";
	document.getElementById("course_content").style.display = "none";
	document.getElementById("faq_content").style.display = "none";

	// Hide if available
	if (document.getElementById("my_course_content") !== null) {
		document.getElementById("my_course_content").style.display = "none";
	}
}

// Show prompts and hide others
function show_prompts(element) {
	document.getElementById(element).style.display = "block";
	hide_errors();
}

// Hide element
function hide(element) {
	document.getElementById(element).style.display = "none";
}

// Sign out and expire cookie
function sign_out() {
	var d = new Date();
    d.setTime(d.getTime() + (-1 * 24 * 60 * 60 * 1000));
	document.cookie = "logged_in=; expires=" + d.toUTCString() + "; username=; expires=" + d.toUTCString();
	window.location = "learncity.php";
}

// Initial setup function
function start() {
	
	// Login prompt
	if (document.getElementById("login_prompt") !== null) {
		document.getElementById("login_prompt").addEventListener(
		"click", function() {show_prompts("login_inputs");}, false);
	}
	
	// Register prompt
	if (document.getElementById("register_prompt") !== null) {
		document.getElementById("register_prompt").addEventListener(
		"click", function() {show_prompts("register_inputs");}, false);
	}
	
	// Close login
	if (document.getElementById("close_login") !== null) {
		document.getElementById("close_login").addEventListener(
		"click", function() {hide("login_inputs");}, false);
	}
	
	// Close register 
	if (document.getElementById("close_register") !== null) {
		document.getElementById("close_register").addEventListener(
		"click", function() {hide("register_inputs");}, false);
	}

	// Sign out
	if (document.getElementById("sign_out") !== null) {
		document.getElementById("sign_out").addEventListener(
		"click", sign_out, false);
	}

	// Home
	if (document.getElementById("home") !== null) {
		document.getElementById("home").addEventListener(
		"click", function() {show_content("home_content");}, false);
	}

	// Courses page
	if (document.getElementById("courses") !== null) {
		document.getElementById("courses").addEventListener(
		"click", function() {show_content("course_content");}, false);
	}

	// My Courses page
	if (document.getElementById("my_courses") !== null) {
		document.getElementById("my_courses").addEventListener(
		"click", function() {show_content("my_course_content");}, false);
	}

	// Frequently Asked Questions page
	if (document.getElementById("faq") !== null) {
		document.getElementById("faq").addEventListener(
		"click", function() {show_content("faq_content");}, false);
	}

	// Cycle through all edit events
	var counter = 0;
	while (document.getElementById("edit_it_" + counter) !== null) {
		document.getElementById("edit_it_" + counter).addEventListener(
		"click", function() {edit(this.id);}, false);

		counter += 1;
	}

}

// Start after page loads
window.addEventListener("load", start, false);