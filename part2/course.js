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

// Validate answers from quiz
function validateAnswers(unit) {

	// Sets initial variables
	var correct = 0;
	var total = 0;

	var radio_group = document.getElementsByName("unit_" + unit + total);

	// Cycles through each question on the current unit
	while(radio_group.length != 0) {

		for (var i = 0; i < radio_group.length; i++) {
			if (radio_group[i].getAttribute("answer") == 1) {

				if (radio_group[i].checked) {
					correct++;
				}

				document.getElementById("unit_" + unit + "_question_" + total + "_answer_" + i).setAttribute("class", "highlight");
			}
		}

		total++;

		radio_group = document.getElementsByName("unit_" + unit + total);
	}

	// Alert user of Percentage of quiz
	alert("Quiz Percentage: " + correct / total * 100 + "%");
}

// Initial setup function
function start() {

	if (document.getElementById("home") !== null) {
		show_content("home_content");
	}

	if (document.getElementById("home") !== null) {
		document.getElementById("home").addEventListener(
			"click", function() {show_content("home_content");}, false);
	}

	var counter = 0;

	while (document.getElementById("unit_" + counter) !== null && document.getElementById("unit_questions_" + counter) !== null) {
		document.getElementById("unit_" + counter).addEventListener(
		"click", function() {show_content(this.getAttribute("content"));}, false);

		document.getElementById("unit_questions_" + counter).addEventListener(
		"click", function() {validateAnswers(this.getAttribute("unit"));}, false);

		counter++;
	}

}

// Start after page loads
window.addEventListener("load", start, false);