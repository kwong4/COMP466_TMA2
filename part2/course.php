<!DOCTYPE html>

<!-- COMP466 Assignment 2: Part 2 - course.php 									  -->
<!-- Name: Kevin Wong							 										  -->
<!-- ID: 3339323								 										  -->
<html>
	<head>
		<meta charset="utf-8">
		<title>Learn City Course</title>
		<link rel = "stylesheet" type = "text/css" href = "../shared/style.css">
		<script src = "course.js"></script>
	</head>

	<body class = "learn_city">

		<?php

			if (isset($_POST["view_course"])) {

				// Connect to MySQL
				if (!($database = mysqli_connect("localhost", "iw3htp", "password"))) {
				  	die("<p>Could not connect to database</p>");
				}

				// open Learn_City database
				if (!mysqli_select_db($database, "Learn_City")) {
					die("<p>Could not open Learn_City database</p>");
				}

	        	$course_id = $_POST["course_id"];

	        	// build SELECT query
               	$query = "SELECT Name " .
               			 "FROM courses " .
               			 "WHERE CourseId = '$course_id'";

               	// execute query in Learn_City database
				if (!($title = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

	        	// build SELECT query
               	$query = "SELECT Unit_number, Unit_title " .
               			 "FROM units " .
               			 "WHERE CourseId = '$course_id' " .
               			 "ORDER BY Unit_number";

               	// execute query in Learn_City database
				if (!($units = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				$row = mysqli_fetch_row($title);

				$title_converted = htmlspecialchars($row[0]);

				print('<!-- Title + Banner -->
					<ul class = "course_navigation">

						<!-- Section Title -->
						<div id = "title" class = "course_title">');

				print("$title_converted
						</div>
						<!-- Banner -->
						<li id = \"home\">Home</li>");

				$unit_count = 0;

				while ($row = mysqli_fetch_row($units)) {

					$unit_converted = htmlspecialchars($row[1]);

					print("<li id = \"unit_$unit_count\" content = \"unit_content_$unit_count\">$unit_converted</li>");

					$unit_count++;
				}

				print('<!-- Banner image -->
					<div class = "banner">
						<img class = "banner_image" src = "../shared/banner_img.jpg">
					</div>');

				print('</ul>');

				print("<div class = \"bulk\"id = \"home_content\">
					<h2>Welcome!</h2>
					<p>This Learn City course has the following units: </p>
					<ul>");

				// execute query in Learn_City database
				if (!($units = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				while ($row = mysqli_fetch_row($units)) {

					$unit_converted = htmlspecialchars($row[1]);

					print("<li class = \"important\">$unit_converted</li>");

					$unit_count++;
				}

				print("</ul>
					<p>These units are accessible by clicking on the desired unit with the banner at the top of the screen.</p>
					<p>Each Unit has a quiz to test the skills and knowledge you have learned.</p>
					<p>It is hoped that you will learn a lot from this course!</p>
					</div>");

				// Per unit
				for ($i = 0; $i < $unit_count; $i++) {
					print("<div id = \"unit_content_$i\">");

					// build SELECT query
	               	$query = "SELECT Section_number, Section_title " .
	               			 "FROM sections " .
	               			 "WHERE CourseId = '$course_id' AND Unit_number = '$i' " .
	               			 "ORDER BY Section_number";

	               	// execute query in Learn_City database
					if (!($sections = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if

					while ($row = mysqli_fetch_row($sections)) {

						$section_converted = htmlspecialchars($row[1]);

						print("<h3 class = \"section_portion\">$section_converted</h3>");

						// build SELECT query
		               	$query = "SELECT Paragraph_number, Paragraph " .
		               			 "FROM paragraphs " .
		               			 "WHERE CourseId = '$course_id' AND Unit_number = '$i' AND Section_number = '$row[0]' " .
		               			 "ORDER BY Paragraph_number";

		               	// execute query in Learn_City database
						if (!($paragraphs = mysqli_query($database, $query))) {
						  	print("<p>Could not execute query!</p>");
						  	die(mysqli_error($database));
						} // end if

						while ($row2 = mysqli_fetch_row($paragraphs)) {

							$paragraph_converted = htmlspecialchars($row2[1]);

							print("<p class = \"description_para\">$paragraph_converted</p>");
						}

					}

					// build SELECT query
	               	$query = "SELECT Question_number, Inquiry, Answer1, Answer2, Answer3, Answer4, AnswerNum " .
	               			 "FROM quizes " .
	               			 "WHERE CourseId = '$course_id' AND Unit_number = '$i' " .
	               			 "ORDER BY Unit_number, Question_number";

	               	// execute query in Learn_City database
					if (!($quizes = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if

					print('<div class = "bulk">
						<h2>Quiz</h2>');

					while ($row = mysqli_fetch_row($quizes)) {
						$inquiry_converted = htmlspecialchars($row[1]);

						print("<h4>$inquiry_converted</h4>");

						for ($j = 2; $j < 6; $j++) {
							$answer_num = $j - 2;
							if ($row[6] == $answer_num) {
								print("<input type = \"radio\" name = \"unit_$i" . $row[0] . "\" answer = \"1\">");
							}
							else {
								print("<input type = \"radio\" name = \"unit_$i" . $row[0] . "\" answer = \"0\">");
							}

							$answer_converted = htmlspecialchars($row[$j]);

							print("<label id = \"unit_" . $i . "_question_" . $row[0] . "_answer_$answer_num\" class = \"answer\">$answer_converted</label><br>");
						}
					}

					print('<input ' . "id = \"unit_questions_$i\" unit = \"$i\"" . ' type = "button" class = "submit" value = "Submit">
						</div>
					</div>');
				}

				mysqli_close($database);

	        }
	        else {
	        	print('<h1>Please view course from Learn City main page</h1>');
	        }

		?>

	</body>
</html>