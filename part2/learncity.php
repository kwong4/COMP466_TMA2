<!DOCTYPE html>

<!-- COMP466 Assignment 2: Part 2 - learncity.php 									  -->
<!-- Name: Kevin Wong							 										  -->
<!-- ID: 3339323								 										  -->
<html>
	<head>
		<meta charset="utf-8">
		<title>Learn City</title>
		<link rel = "stylesheet" type = "text/css" href = "../shared/style.css">
		<script src = "learncity.js"></script>
	</head>

	<body class = "learn_city">

		<?php

			// Global variables for errors and style variables
			$existing_username_error = "";
	        $existing_email_error = "";
	        $invalid_login_error = "";
	        $login_error = "";
	        $register_error = "";
	        $my_home_page = "";
	        $course_page = "";
	        $my_course_page = "";

	        // Connect to MySQL
			if (!($database = mysqli_connect("localhost", "iw3htp", "password"))) {
			  	die("<p>Could not connect to database</p>");
			}

			// open Learn_City database
			if (!mysqli_select_db($database, "Learn_City")) {
				die("<p>Could not open Learn_City database</p>");
			}

			// Login entry
	        if (isset($_POST["login_submit"])) {

	        	// User info
	        	$username = $_POST["username"];
	        	$password = $_POST["password"];

	        	// build SELECT query to see if correct
               	$query = "SELECT *
               			  FROM users
               			  WHERE Username = '$username' AND Password = '$password'";

                // execute query in Learn_City database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				// Checks if correct login info
            	if (mysqli_num_rows($result) == 1) {

            		// Set cookies for correct login
            		setcookie("logged_in", "true");
            		setcookie("username", $username);
            		header("Location: learncity.php");
            	}
            	else {
            		// Else display error
            		$login_error = "style = \"display: block;\"";
	        		$invalid_login_error = "* Invalid Login Info";
            	}
	        }
	        // Register
	        else if (isset($_POST["register_submit"])) {

	        	// Register info
	        	$reg_username = $_POST["reg_username"];
		        $reg_email = $_POST["reg_email"];
		        $reg_password = $_POST["reg_password"];
		        $is_error = false;

	        	// build SELECT query to check if username exists
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Username = '$reg_username'";
             
                // execute query in Learn_City database
				if (!($result = mysqli_query($database, $query))) {
					print("<p>Could not execute query!</p>");
					die(mysqli_error($database));
				} // end if

				// build SELECT query if email exists
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Email = '$reg_email'";

               	// execute query in Learn_City database
				if (!($result2 = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				// Check if username exists and show error if is
            	if (mysqli_num_rows($result) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_username_error = "* Username already exists";
            		$is_error = true;
            	}

            	// Check if email exists and show error if is
            	if (mysqli_num_rows($result2) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_email_error = "* Email already exists";
            		$is_error = true;
            	}

            	// Check if any error
            	if ($is_error == false) {

            		// Register user into database
            		$query = "INSERT INTO users " .
            				 "(Username, Password, Email) " .
               			  	 "VALUES ('$reg_username', '$reg_password', '$reg_email')";

               		// execute query in Learn_City database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
					else {

						// Set to logged in
						setcookie("logged_in", "true");
            			setcookie("username", $reg_username);
            			header("Location: learncity.php");
					}
            	}
	        }
	        // If adding course
	        else if (isset($_POST["add_course"])) {

	        	// Show My courses page and find username
	        	$my_home_page = "style = \"display: none;\"";
	        	$course_page = "style = \"display: none;\"";
	        	$my_course_page = "style = \"display: block;\"";
	        	$username = $_COOKIE["username"];

	        	// Check if file uploaded correctly
	        	if (isset($_FILES["myCourse"]) && ($_FILES["myCourse"]['error'] == UPLOAD_ERR_OK)) {

	        		//Parser for EML
	        		//------------------------------------------------
		        	$xml_course = simplexml_load_file($_FILES["myCourse"]["tmp_name"]);

		        	// Delimit special characters
		        	$course_name = mysqli_real_escape_string($database, $xml_course["name"]);

		        	// User incorrectly enters EML
		        	if ($xml_course->units->unit->count() != $xml_course->quizes->quiz->count()) {
						//Error
						die("Invalid EML. Please refer to FAQ");
					}	

					// build INSERT query for course
	               	$query = "SELECT * " .
	               			 "FROM courses " .
	               			 "WHERE Name = '$course_name'";

	               	// execute query in Learn_City database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if

					if (mysqli_num_rows($result) > 0) {
						die("Course name already exists. Please use an different name and try again.");
					}

		        	// build INSERT query for course
	               	$query = "INSERT INTO courses " .
	               			 "(Username, Name) " .
	               			 "VALUES ('$username','$course_name')";

	               	// execute query in Learn_City database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
					else {

						// Obtain course id if query executed correctly
						$query = "SELECT CourseId " .
	               			 "FROM Courses " .
	               			 "WHERE Username = '$username' AND Name = '$course_name'";

	               		// execute query in Learn_City database
						if (!($result = mysqli_query($database, $query))) {
						  	print("<p>Could not execute query!</p>");
						  	die(mysqli_error($database));
						} // end if
						
						// Error
						if (mysqli_num_rows($result) == 0) {
							die("Error finding course. May not exist.");
						}

						// Course id
						$row = mysqli_fetch_row($result);
						$course_id = $row[0];

						// Cycle through all of the units
						for ($i = 0; $i < $xml_course->units->unit->count(); $i++) {

							// Find unit title and delimit
							$unit_title = mysqli_real_escape_string($database, $xml_course->units->unit[$i]->title);

							// build INSERT query for units
			               	$query = "INSERT INTO units " .
			               			 "(CourseId, Unit_number, Unit_title) " .
			               			 "VALUES ('$course_id','$i','$unit_title')";

			               	// execute query in Learn_City database
							if (!($result = mysqli_query($database, $query))) {
							  	print("<p>Could not execute query!</p>");
							  	die(mysqli_error($database));
							} // end if

							// Cycle through all of the sections
			               	for ($j = 0; $j < $xml_course->units->unit[$i]->section->count(); $j++) {

			               		// Find section title and delimit
			               		$section_title = mysqli_real_escape_string($database, $xml_course->units->unit[$i]->section[$j]->sectiontitle);

			               		// build INSERT query for section
				               	$query = "INSERT INTO sections " .
				               			 "(CourseId, Unit_number, Section_number, Section_title) " .
				               			 "VALUES ('$course_id','$i','$j','$section_title')";

				               	// execute query in Learn_City database
								if (!($result = mysqli_query($database, $query))) {
								  	print("<p>Could not execute query!</p>");
								  	die(mysqli_error($database));
								} // end if

								// Cycle through all of the paragraphs
								for ($k = 0; $k < $xml_course->units->unit[$i]->section[$j]->paragraph->count(); $k++) {

									// Find paragraph info and delimit
									$paragraph = mysqli_real_escape_string($database, $xml_course->units->unit[$i]->section[$j]->paragraph[$k]);

									// build INSERT query for paragraph
					               	$query = "INSERT INTO paragraphs " .
					               			 "(CourseId, Unit_number, Section_number, Paragraph_number, Paragraph) " .
					               			 "VALUES ('$course_id','$i','$j','$k', '$paragraph')";

					               	// execute query in Learn_City database
									if (!($result = mysqli_query($database, $query))) {
									  	print("<p>Could not execute query!</p>");
									  	die(mysqli_error($database));
									} // end if
								}
			               	}

			               	// Cycle through all questions
			               	for ($j = 0; $j < $xml_course->quizes->quiz[$i]->question->count(); $j++) {

			               		// Delimit answers
			               		$question = mysqli_real_escape_string($database, $xml_course->quizes->quiz[$i]->question[$j]->inquiry);
		               			$answer1 = mysqli_real_escape_string($database, $xml_course->quizes->quiz[$i]->question[$j]->answer[0]);
		               			$answer2 = mysqli_real_escape_string($database, $xml_course->quizes->quiz[$i]->question[$j]->answer[1]);
		               			$answer3 = mysqli_real_escape_string($database, $xml_course->quizes->quiz[$i]->question[$j]->answer[2]);
		               			$answer4 = mysqli_real_escape_string($database, $xml_course->quizes->quiz[$i]->question[$j]->answer[3]);

		               			// Cycle through answers and find correct one
		               			$answer = 0;
		               			for ($k = 0; $k < 4; $k++) {
		               				if ($xml_course->quizes->quiz[$i]->question[$j]->answer[$k]->correct == "*") {
		               					$answer = $k;
		               					break;
		               				}
		               			}

		               			// build INSERT query to insert question
				               	$query = "INSERT INTO quizes " .
				               			 "(CourseId, Unit_number, Question_number, Inquiry, Answer1, Answer2, Answer3, Answer4, AnswerNum) " .
				               			 "VALUES ('$course_id','$i','$j','$question','$answer1','$answer2','$answer3','$answer4','$answer')";

				               	// execute query in Learn_City database
								if (!($result = mysqli_query($database, $query))) {
								  	print("<p>Could not execute query!</p>");
								  	die(mysqli_error($database));
								} // end if
			               	}
		        		}
					}
		   
	        	}
	        	else {
	        		// Error
	        		die("Error uploading file. Please try again.");
	        	}
	        }
	        // Checks if delete course
	        else if (isset($_POST["delete_course"])) {

	        	// Show My course page
	        	$my_home_page = "style = \"display: none;\"";
	        	$course_page = "style = \"display: none;\"";
	        	$my_course_page = "style = \"display: block;\"";

	        	// Grab user and course info
	        	$username = $_COOKIE["username"];
	        	$course_id = $_POST["course_id"];

	        	// build DELETE query for course
               	$query = "DELETE FROM courses " .
               			 "WHERE CourseId = '$course_id' AND Username = '$username'";

               	// execute query in Learn_City database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if
	        }

	        // Title and banner
	        print('<!-- Title + Banner -->
			<ul class = "navigation">

				<!-- Section Title -->
				<div id = "title" class = "learncity_title">
					Learn City
				</div>');

	        // Change tabs if logged in
	        if (isset($_COOKIE["logged_in"])) {
	        	print('<!-- Banner -->
					<li id = "home">Home</li>
					<li id = "courses">Courses</li>
					<li id = "my_courses">My Courses</li>
					<li id = "faq">FAQ</li>
				</ul>

				<!-- Login Banner -->
				<ul class = "login_nav">
					<li id = "sign_out">Sign Out</li>
				</ul>');
	        }
	        else {
	        	print('<!-- Banner -->
					<li id = "home">Home</li>
					<li id = "courses">Courses</li>
					<li id = "faq">FAQ</li>
				</ul>

				<!-- Login Banner -->
				<ul class = "login_nav">
					<li id = "login_prompt">Login</li>
					<li id = "register_prompt">Register</li>
				</ul>');
	        }

	        // Banner image
	        print('<!-- Banner image -->
			<div class = "banner">
				<img class = "banner_image" src = "../shared/learncity_background.jpg">
			</div>');

	        // Home Page
			//-----------------------------------------------------------------------------------
	        print("<div id = \"home_content\" $my_home_page>");

	        // Show different welcome message if logged in
	        if (isset($_COOKIE["logged_in"])) {
				print(' <h1 class = "title_centre">Welcome ' .  $_COOKIE["username"] . '!</h1>');
			}
			else {
				print(" <h1 class = \"title_centre\" id = \"welcome\">Welcome to Learn City!</h1>");
			}

			// Welcome page info
			print('<div class = "bulk">
				<p>Here at Learn City, we try to provide a platform for students and teachers all around!</p>
				<p>Please feel free to take any of the courses listed under the <strong>"Courses"</strong> tab</p>
				<p>If you have any courses you would like to post on our site, follow a guide under the <strong>"FAQ"</strong> tab</p>
					</div>
				</div>');

			// Courses page
			//-----------------------------------------------------------------------------------
			print("<div id = \"course_content\" class = \"hidden\" $course_page>");

			// build SELECT query for name and course id
           	$query = "SELECT Name, CourseId " .
           			 "FROM Courses ";
							
           	// execute query in Learn_City database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			// Show courses if there are some
			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Courses on the site yet. Be the first!</h3>
						<div class = "hidden">');
			}
			else {
				print('	<h3 class = "title_centre">Courses</h3>
						<div class = "box_curr_course">');
			}

			// Cycle through all the courses and add view course button
			$counter = 0;
			while ($row = mysqli_fetch_row($result)) {

				print("<div class = \"box_curr_course\">
               			<label id = \"course_$counter\" course_id = \"$row[1]\" >$row[0]</label>
               			<form class = \"next_to\" method = \"post\" action = \"course.php\">
               				<input type = \"text\" class = \"hidden\" name = \"course_id\" value = \"$row[1]\"></input>
		            		<button type = \"submit\" name = \"view_course\">View</button>
		            	</form>
               		   </div>
               		   <br>
						");

               	$counter += 1;

            } // end while

            // HTML tags
			print('</div>
				</div>');

			// My Courses page
			//-----------------------------------------------------------------------------------
			print("<div id = \"my_course_content\" class = \"hidden\" $my_course_page>");

			// build SELECT query for name and course id
           	$query = "SELECT Name, CourseId " .
           			 "FROM Courses " . 
           			 "WHERE Username = '" . $_COOKIE["username"] . "'";

           	// execute query in Learn_City database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			// Check if any courses are present
			if (mysqli_num_rows($result) == 0) {
				print('	<h2 class = "title_centre">No Courses added yet.</h3>
					<div class = "hidden">');
			}
			else {
				print('	<h2 class = "title_centre">Courses</h3>
					<div class = "box_curr_course">');
			}

			// Cycle through all of the courses and add button to view and delete
			$counter = 0;
			while ($row = mysqli_fetch_row($result)) {

               	print("<div class = \"box_curr_course\">
               			<label id = \"course_$counter\" course_id = \"$row[1]\" >$row[0]</label>
               			<form class = \"next_to\" method = \"post\" action = \"course.php\">
               				<input type = \"text\" class = \"hidden\" name = \"course_id\" value = \"$row[1]\"></input>
		            		<button type = \"submit\" name = \"view_course\">View</button>
		            	</form>
               			<form class = \"next_to\" method = \"post\" action = \"learncity.php\">
               				<input type = \"text\" class = \"hidden\" name = \"course_id\" value = \"$row[1]\"></input>
		            		<button type = \"submit\" name = \"delete_course\">Delete</button>
		            	</form>
               		   </div>
               		   <br>
						");

               	$counter += 1;
            } // end while

            // Add portion to allow user to add course from EML file
			print('</div>');
			print("<h2></h2>
				<div class = \"add_course\">
					<form method = \"post\" action = \"learncity.php\" enctype=\"multipart/form-data\">
	            		<input name = \"myCourse\" type = \"file\">
	            		<button type = \"submit\" name = \"add_course\">Submit Course</button>
	            	</form>
            	</div>
            </div>");

			// FAQ page
			//-----------------------------------------------------------------------------------
			print("<div id = \"faq_content\" class = \"hidden\">
					<h2>Welcome to the FAQ page!</h2>
					<p>Here will show answer your Frequently Asked Questions</p>
					<br>
					<br>
					<h3>Can anyone view/add a course?</h3>
					<p>Yes anyone can view/add a course if they would like to contribute to this site. However to be able to add a course, you must be registered and logged into our site. This is however not the case for viewing a course which you can do without logging in.</p>
					<br>
					<br>
					<h3>Who's courses can I delete?</h3>
					<p>You can only delete courses that you have uploaded from your profile. This is accessible from the <strong>My Courses</strong> tab once you log in.</p>
					<br>
					<br>
					<h3>How do I add a course?</h3>
					<p>You have to log in, go to the <strong>My Courses</strong> page and upload a EML (extension XML)</p>
					<br>
					<br>
					<h3>What is a EML file?</h3>
					<p>An EML file is an Educational Markup Language that is formatted to be easy to read and write so that your course you want to upload has a good template to follow</p>
					<br>
					<br>
					<h3>Can you explain your EML format?</h3>
					<p>Yeah forsure! Please refer to the following image:</p>
					<img src = \"..\\shared\\EML.PNG\">
					<br>
					<div class = \"left\">
						<p>So our Educational Markup Language starts off with a xml version tag which just tells that the file is an XML file.</p>
						<p>Next we have a course tag. If you are unfimilar with XML, tags or inputs are shown as &#60;&#62;. This is a start tag. Most of the time start tags will always have end tags specified as &#60;&#47;a&#62;. This is the case for our EML.</p>
						<p>Within these tags you define what the tag is, for example we have a course tag that starts in Line 3, and ends in Line 25. Anything in between the start and end tags are your values.</p>
						<p>A start tag however can have attribute such as name in Line 3. We use this to determine the course name. Within our EML course, we have a units tag. Within this unit tag, we can have multiple unit tags. We only show one here but you would be able to have multiple units.</p>
						<p>Furthermore, within a unit there must be a title for that unit, any number of sections. However each section must have a sectiontitle but can have as many paragraphs as the section requires.</p>
						<p>Next we have our quizes tag. These are similar to the units tags but hold quizes. You must have a the same number of quiz tags as unit tags to ensure each unit has a quiz. Within a quiz tag, you can have as many questions as you like.</p>
						<p>In a question tag, you must have a inquiry - Question. And four answers, with the correct one having a * within the correct attribute.</p>
						<p>After you have a EML format marked up, upload it to your <strong>My Courses</strong> page and see your course come to life!</p>
					</div>
				</div>");

			// Login prompt
			print("<div class = \"modal\" id = \"login_inputs\" $login_error>");
	        print('
				<form class = "modal-content animate" method = "post" action = "learncity.php">
					<div class = "container">
						<div class = "close_container">
							<span class = "close" id = "close_login" title = "Close Login">&times;</span>
						</div>

						<label><strong>Username: </strong></label>');
	        print("		<label class = \"error_it\" id = \"error_login\">$invalid_login_error</label>");
			print('		<input type = "text" class = "info" placeholder = "Enter Username" name = "username" required>
						<br>
						<label><strong>Password: </strong></label>
						<input type = "password" class = "info" placeholder = "Enter Password" name = "password" required>
				        <br>
						<button class = "action_button" type = "submit" name = "login_submit">Login</button>
					</div>
				</form>
			</div>');

			// Register prompt
	        print("<div class = \"modal\" id = \"register_inputs\" $register_error>");
			print('
				<form class = "modal-content animate" method = "post" action = "learncity.php">
					<div class = "container">
						<div class = "close_container">
							<span class = "close" id = "close_register" title = "Close Login">&times;</span>
						</div>
						<br>
						<label><strong>Username: </strong></label>');
			print("		<label class = \"error_it\" id = \"error_register1\">$existing_username_error</label>");
			print('		<input type = "text" class = "info" placeholder = "Enter Username" name ="reg_username" required>
						<br>
						<br>
						<label><strong>Email: </strong></label>');
			print("		<label class = \"error_it\" id = \"error_register2\">$existing_email_error</label>");
			print('		<input type = "email" class = "info" placeholder = "Enter Email" name = "reg_email" required>
						<br>
						<br>
						<label><strong>Password: </strong></label>
						<input type = "password" class = "info" placeholder = "Enter Password" name = "reg_password" required>
						<button class = "action_button" type = "submit" name = "register_submit">Register</button>
					</div>
				</form>
			</div>');

			// Close sql database
			mysqli_close($database);
		?>

	</body>
</html>