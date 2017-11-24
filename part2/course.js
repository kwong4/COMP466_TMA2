/*
COMP466 Assignment 2: Part 2 - course.js
Name: Kevin Wong							
ID: 3339323								
*/

// Show element
function show_content(element) {
	hide_content();
	document.getElementById(element).style.display = "block";
}

// Hide all content
function hide_content() {

	// Cycle through all of the units to hide
	var counter = 0;

	while (document.getElementById("unit_content_" + counter) !== null) {
		document.getElementById("unit_content_" + counter).style.display = "none";

		counter++;
	}

	// Hide home content
	if (document.getElementById("home_content") !== null) {
		document.getElementById("home_content").style.display = "none";
	}
}

// Validate answers from quiz
function validateAnswers(unit) {

	// Sets initial variables
	var correct = 0;
	var total = 0;

	// Find current unit
	var radio_group = document.getElementsByName("unit_" + unit + total);

	// Cycles through each question on the current unit
	while(radio_group.length != 0) {

		// Check how many of the answers are correct
		for (var i = 0; i < radio_group.length; i++) {
			if (radio_group[i].getAttribute("answer") == 1) {

				// Check if answer is correct
				if (radio_group[i].checked) {
					correct++;
				}

				// Highlight correct answer
				document.getElementById("unit_" + unit + "_question_" + total + "_answer_" + i).setAttribute("class", "highlight");
			}
		}

		// Total questions
		total++;

		// Next question
		radio_group = document.getElementsByName("unit_" + unit + total);
	}

	// Alert user of Percentage of quiz
	alert("Quiz Percentage: " + correct / total * 100 + "%");
}

// Initial setup function
function start() {

	// If home content exists show it and set button
	if (document.getElementById("home") !== null) {
		show_content("home_content");

		document.getElementById("home").addEventListener(
			"click", function() {show_content("home_content");}, false);
	}

	// Cycle through all of the units and set listeners for tab and submit quiz buttons
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