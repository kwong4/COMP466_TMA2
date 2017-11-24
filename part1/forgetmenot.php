<!DOCTYPE html>

<!-- COMP466 Assignment 2: Part 1 - forgetmenot.php 									  -->
<!-- Name: Kevin Wong							 										  -->
<!-- ID: 3339323								 										  -->
<html>
	<head>
		<meta charset="utf-8">
		<title>ForgetMeNot</title>
		<link rel = "stylesheet" type = "text/css" href = "../shared/style.css">
		<script src = "forgetmenot.js"></script>
	</head>

	<body>

		<?php

			// Global variables to display, hide, or keep track of user selection/action
	        $existing_username_error = "";
	        $existing_email_error = "";
	        $invalid_login_error = "";
	        $login_error = "";
	        $register_error = "";
	        $my_bookmark_page = "";
	        $my_home_page = "";
	        $invalid_url_msg = "";
	        $invalid_url = "";
	        $invalid_name = "";
	        $internalErrors = libxml_use_internal_errors(true);

	        // Connect to MySQL
			if (!($database = mysqli_connect("localhost", "iw3htp", "password"))) {
			  	die("<p>Could not connect to database</p>");
			}

			// open Forgetmenot database
			if (!mysqli_select_db($database, "Forgetmenot")) {
				die("<p>Could not open Forgetmenot database</p>");
			}

	        // if the user tries to login
	        if (isset($_POST["login_submit"])) {

	        	// Find username and password
	        	$username = $_POST["username"];
	        	$password = $_POST["password"];

	        	// build SELECT query
               	$query = "SELECT *
               			  FROM users
               			  WHERE Username = '$username' AND Password = '$password'";

                // execute query in Forgetmenot database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				// Check if login is correct
            	if (mysqli_num_rows($result) == 1) {
            		setcookie("logged_in", "true");
            		setcookie("username", $username);
            		header("Location: forgetmenot.php");
            	}
            	// Error message
            	else {
            		$login_error = "style = \"display: block;\"";
	        		$invalid_login_error = "* Invalid Login Info";
            	}
	        }
	        // Check if the the user registers
	        else if (isset($_POST["register_submit"])) {

	        	// Find register details
	        	$reg_username = $_POST["reg_username"];
		        $reg_email = $_POST["reg_email"];
		        $reg_password = $_POST["reg_password"];
		        $is_error = false;

	        	// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Username = '$reg_username'";
             
                // execute query in Forgetmenot database
				if (!($result = mysqli_query($database, $query))) {
					print("<p>Could not execute query!</p>");
					die(mysqli_error($database));
				} // end if

				// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Email = '$reg_email'";

               	// execute query in Forgetmenot database
				if (!($result2 = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if

				// Checks if user exists
            	if (mysqli_num_rows($result) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_username_error = "* Username already exists";
            		$is_error = true;
            	}
            	// Checks if email exists
            	if (mysqli_num_rows($result2) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_email_error = "* Email already exists";
            		$is_error = true;
            	}
            	// Checks if any errors
            	if ($is_error == false) {

            		// Register user into database if doesn't exist
            		$query = "INSERT INTO users " .
            				 "(Username, Password, Email) " .
               			  	 "VALUES ('$reg_username', '$reg_password', '$reg_email')";

               		// execute query in Forgetmenot database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
					else {

						// Login user after register
						setcookie("logged_in", "true");
            			setcookie("username", $reg_username);
            			header("Location: forgetmenot.php");
					}
            	}
	        }
	        // Checks if user adds bookmarks
			else if (isset($_POST["add_bookmark"])) {

				// Sets the initial pages
	        	$my_bookmark_page = "style = \"display: block;\"";
	        	$my_home_page = "style = \"display: none;\"";

	        	// Find the submitted info
	        	$bookmark = $_POST["bookmark"];
		        $url = $_POST["url"];
		        $username = $_COOKIE["username"];

		        // Check if url is valid
	            if (filter_var($url, FILTER_VALIDATE_URL) === false) {

	            	// Error message for valid message
	            	$invalid_url = $url;
	            	$invalid_url_msg = "* Invalid URL";
	            	$invalid_name = $bookmark;
	            }
	           	// Valid restaurant
	            else {
	            	// build INSERT query to add bookmark
	               	$query = "INSERT INTO bookmarks " .
	               			 "(Username, Name, Url) " .
	               			 "VALUES ('$username', '$bookmark', '$url') " . 
	               			 "ON DUPLICATE KEY UPDATE Url = VALUES(Url)";

	               	// execute query in Forgetmenot database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
	            }
	        }
	        // Check if user wants to delete bookmark
	        else if (isset($_POST["delete_bookmark"])) {

	        	// Sets the initial pages
	        	$my_bookmark_page = "style = \"display: block;\"";
	        	$my_home_page = "style = \"display: none;\"";

	        	// Find the submitted info
	        	$url_name = $_POST["url_name"];
		        $url_link = $_POST["url_link"];
		        $username = $_COOKIE["username"];

		        // build DELETE query to delete bookmark
               	$query = "DELETE FROM bookmarks " .
               			 "WHERE " .
               			 "Username = '$username' AND " .
               			 "Name = '$url_name' AND " . 
               			 "URL = '$url_link'";

               	// execute query in Forgetmenot database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysqli_error($database));
				} // end if
	        }
	        
	        // Title of HTML
	        print('<!-- Title + Banner -->
				<ul class = "navigation">

					<!-- Section Title -->
					<div id = "title" class = "title">
						ForgetMeNot
					</div>');

	        // Show banners based if user is logged in
	        if (isset($_COOKIE["logged_in"])) {
	        	print('	<!-- Banner -->
					<li id = "home">Home</li>
					<li id = "my_bookmarks">My Bookmarks</li>
					<li id = "sign_out">Sign Out</li>');
	        }
	        else {
	        	print('	<!-- Banner -->
					<li id = "login_prompt">Sign In</li>
					<li id = "register_prompt">Register</li>');
	        }
			print("</ul>
				<div id = \"home_content\" $my_home_page>");

			// Change welcome message if user logged in
			if (isset($_COOKIE["logged_in"])) {
				print(' <h1 class = "title_centre">Welcome ' .  $_COOKIE["username"] . '!</h1>');
			}
			else {
				print(" <h1 class = \"title_centre\" id = \"welcome\">Welcome to ForgetMeNot!</h1>");
			}
							
			// build SELECT query for top 10 bookmarks
           	$query = "SELECT Url " .
           			 "FROM bookmarks " .
           			 "GROUP BY Url " .
           			 "ORDER BY COUNT(Url) DESC " .
           			 "LIMIT 10";
							
           	// execute query in Forgetmenot database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			// Main bookmarks portion
			print('<div class = "main_bookmarks">');

			// Change message based if there are any bookmarks
			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Bookmarks on the site yet. Be the first!</h3>
						<ol>');
			}
			else {
				print('	<h3 class = "title_centre">Top 10 Bookmarks</h3>
						<ol>');
			}

			// If there are any results
            while ($row = mysqli_fetch_row($result)) {

            	// Get title of website because people may have different names
            	$doc = new DOMDocument();

            	if($doc->loadHTMLFile($row[0])) {
				    $list = $doc->getElementsByTagName("title");
				    if ($list->length > 0) {
				        $title = $list->item(0)->textContent;
				    }
				}

				// Top 10 bookmarks
               	print("<li><a href = \"$row[0]\">$title</a></li>");

            } // end while

            // HTML end tags
			print('		</ol>
					</div>
				</div>');

			// Create tab for My Bookmarks
			print("<div id = \"mybookmark_content\" class = \"hidden\" $my_bookmark_page>");

			// Style and title
			print('<div class = "my_bookmarks">
				<h1 class = "title_centre">Your BookMarks!</h1>');

			// build SELECT query for your bookmarks
           	$query = "SELECT Name, Url " .
           			 "FROM bookmarks " .
           			 "WHERE Username = '" . $_COOKIE["username"] . "'";

           	// execute query in Forgetmenot database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			// Checks if any bookmarks are added
			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Bookmarks added yet.</h3>
						<ul>');
			}
			else {
				print('	<h3 class = "title_centre">Bookmarks</h3>
						<ul>');
			}

			// Use counter to keep track of the courses
			$counter = 0;

			// fetch each record in result set
            while ($row = mysqli_fetch_row($result)) {

            	// Course info and ability to edit and delte course
               	print("<li><a id = item_$counter href = \"$row[1]\">$row[0]</a>
               		  	<label>
               		  	<button id = \"edit_it_$counter\">Edit</button>
               		  	<form class = \"one_line\"method = \"post\" action = forgetmenot.php>
               		  	<input type = \"text\" class = \"hidden\" name = \"url_name\" value = \"$row[0]\"></input>
               		  	<input type = \"text\" class = \"hidden\" name = \"url_link\" value = \"$row[1]\"></input>
               		  	<button type = \"submit\" name = \"delete_bookmark\">Delete</button></form>
               		  	</label>
               		</li>");

               $counter += 1;

            } // end while

            // HTML end tags
			print('</ul>
				</div>');

			// HTML to add a bookmark
			print("<div class = \"my_input\">");
			print('	<form class = "modal-my-content" method = "post" action = "forgetmenot.php">
						<label><strong>Bookmark Name: </strong></label>
						<br>');
			print("			<input type = \"text\" class = \"my_info\" placeholder = \"Enter Bookmark Name\" name = \"bookmark\" id = \"bookmark_name\" value = \"$invalid_name\" required>");
			print('		<br>
						<label><strong>Url: </strong></label>');
			print("		<label class = \"error_it\" id = \"error_url\">$invalid_url_msg</label>");
			print('		<br>');
			print("		<input type = \"url\" class = \"my_info\" placeholder = \"Enter Url\" name = \"url\" id = \"url_address\" value = \"$invalid_url\" required>");
			print('     <br>
						<button class = "action_button" type = "submit" name = "add_bookmark">Add/Modify Bookmark</button>
					</form>
				</div>
			</div>');

			// Login window
	        print("<div class = \"modal\" id = \"login_inputs\" $login_error>");
	        print('
				<form class = "modal-content animate" method = "post" action = "forgetmenot.php">
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

			// Register window
	        print("<div class = \"modal\" id = \"register_inputs\" $register_error>");
			print('
				<form class = "modal-content animate" method = "post" action = "forgetmenot.php">
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

			// Close Database and hide warnings
			mysqli_close($database);
			libxml_use_internal_errors($internalErrors);
		?>

	</body>
</html>