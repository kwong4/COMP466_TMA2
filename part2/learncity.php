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

			// ensure that all fields have been filled in correctly
	        if (isset($_POST["login_submit"])) {

	        	$username = isset($_POST["username"]) ? $_POST["username"] : "";
	        	$password = isset($_POST["password"]) ? $_POST["password"] : "";

	        	// build SELECT query
               	$query = "SELECT *
               			  FROM users
               			  WHERE Username = '$username' AND Password = '$password'";

                // execute query in Learn_City database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

            	if (mysqli_num_rows($result) == 1) {
            		setcookie("logged_in", "true");
            		setcookie("username", $username);
            		header("Location: learncity.php");
            	}
            	else {
            		$login_error = "style = \"display: block;\"";
	        		$invalid_login_error = "* Invalid Login Info";
            	}
	        }
	        else if (isset($_POST["register_submit"])) {

	        	$reg_username = isset($_POST["reg_username"]) ? $_POST["reg_username"] : "";
		        $reg_email = isset($_POST["reg_email"]) ? $_POST["reg_email"] : "";
		        $reg_password = isset($_POST["reg_password"]) ? $_POST["reg_password"] : "";
		        $is_error = false;

	        	// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Username = '$reg_username'";
             
                // execute query in Learn_City database
				if (!($result = mysqli_query($database, $query))) {
					print("<p>Could not execute query!</p>");
					die(mysqli_error($database));
				} // end if

				// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Email = '$reg_email'";

               	// execute query in Learn_City database
				if (!($result2 = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

            	if (mysqli_num_rows($result) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_username_error = "* Username already exists";
            		$is_error = true;
            	}
            	if (mysqli_num_rows($result2) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_email_error = "* Email already exists";
            		$is_error = true;
            	}
            	if ($is_error == false) {

            		$query = "INSERT INTO users " .
            				 "(Username, Password, Email) " .
               			  	 "VALUES ('$reg_username', '$reg_password', '$reg_email')";

               		// execute query in Learn_City database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
					else {
						setcookie("logged_in", "true");
            			setcookie("username", $reg_username);
            			header("Location: learncity.php");
					}
            	}
	        }

	        print('<!-- Title + Banner -->
			<ul class = "navigation">

				<!-- Section Title -->
				<div id = "title" class = "learncity_title">
					Learn City
				</div>');

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

	        print('<!-- Banner image -->
			<div class = "banner">
				<img class = "banner_image" src = "../shared/learncity_background.jpg">
			</div>');

	        // Home
			//-----------------------------------------------------------------------------------

	        print("<div id = \"home_content\" $my_home_page>");

	        if (isset($_COOKIE["logged_in"])) {
				print(' <h1 class = "title_centre">Welcome ' .  $_COOKIE["username"] . '!</h1>');
			}
			else {
				print(" <h1 class = \"title_centre\" id = \"welcome\">Welcome to Learn City!</h1>");
			}

			print('<p>Here at Learn City, we try to provide a platform for students and teachers all around!</p>
				<p>Please feel free to take any of the courses listed under the "Courses" tab</p>
				<p>If you have any courses you would like to post on our site, follow a guide under the "FAQ" tab</p>
				</div>');

			// Courses
			//-----------------------------------------------------------------------------------

			print("<div id = \"course_content\" class = \"hidden\" $course_page>");

			// build SELECT query
           	$query = "SELECT Name, CourseId " .
           			 "FROM Courses ";
							
           	// execute query in Learn_City database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Courses on the site yet. Be the first!</h3>
						<ol>');
			}
			else {
				print('	<h3 class = "title_centre">Courses</h3>
						<ol>');
			}


			$counter = 0;
			while ($row = mysqli_fetch_row($result)) {

               	print("<li id = \"course_$counter\" course_id = \"$row[1]\" >$row[0]</li>");

               	$counter += 1;

            } // end while

			print('</ol>');

			print('</div>');

			// My Courses
			//-----------------------------------------------------------------------------------

			print("<div id = \"my_course_content\" class = \"hidden\" $my_course_page>");

			// build SELECT query
           	$query = "SELECT Name, CourseId " .
           			 "FROM Courses " . 
           			 "WHERE Username = '" . $_COOKIE["username"] . "'";
							
           	// execute query in Learn_City database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Courses added yet.</h3>
						<ol>');
			}
			else {
				print('	<h3 class = "title_centre">Courses</h3>
						<ol>');
			}


			$counter = 0;
			while ($row = mysqli_fetch_row($result)) {

               	print("<li id = \"course_$counter\" course_id = \"$row[1]\" >$row[0]</li>");

               	$counter += 1;

            } // end while

			print('</ol>');

			print('</div>');


			// FAQ
			//-----------------------------------------------------------------------------------

			print("<div id = \"faq_content\" class = \"hidden\">
					<h2>Welcome to the FAQ page!</h2>
					<p>Instructions...</p>
				</div>");

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
			mysqli_close($database);
		?>

	</body>
</html>