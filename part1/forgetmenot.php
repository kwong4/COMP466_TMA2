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
	        $login_sucessful = false;
	        $existing_username_error = "";
	        $existing_email_error = "";
	        $invalid_login_error = "";
	        $login_error = "";
	        $register_error = "";

	        // Connect to MySQL
			if (!($database = mysqli_connect("localhost", "iw3htp", "password"))) {
			  	die("<p>Could not connect to database</p>");
			}

			// open Forgetmenot database
			if (!mysqli_select_db($database, "Forgetmenot")) {
				die("<p>Could not open Forgetmenot database</p>");
			}

	        // ensure that all fields have been filled in correctly
	        if (isset($_POST["login_submit"])) {

	        	$username = isset($_POST["username"]) ? $_POST["username"] : "";
	        	$password = isset($_POST["password"]) ? $_POST["password"] : "";

	        	// build SELECT query
               	$query = "SELECT *
               			  FROM users
               			  WHERE Username = '$username' AND Password = '$password'";

                // execute query in Forgetmenot database
				if (!($result = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysql_error());
				} // end if

            	mysqli_close($database);

            	if (mysqli_num_rows($result) == 1) {
            		$login_sucessful = true;
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


	        	// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Username = '$reg_username'";

				// Connect to MySQL
				if (!( $database = mysqli_connect("localhost", "iw3htp", "password"))) {
				  	die("<p>Could not connect to database</p>");
				}

                // open Forgetmenot database
				if (!mysqli_select_db( "Forgetmenot", $database)) {
					die("<p>Could not open MailingList database</p>");
				}
             
                // execute query in Forgetmenot database
				if (!($result = mysqli_query($database, $query))) {
					print("<p>Could not execute query!</p>");
					die(mysql_error());
				} // end if

				// build SELECT query
               	$query = "SELECT * " .
               			 "FROM users " .
               			 "WHERE Username = '$reg_email'";

               	// execute query in Forgetmenot database
				if (!($result2 = mysqli_query($database, $query))) {
				  	print("<p>Could not execute query!</p>");
				  	die(mysql_error());
				} // end if

            	mysql_close($database);

            	if (mysqli_num_rows($result) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_username_error = "* Username already exists";
            	}
            	else if (mysqli_num_rows($result2) == 1) {
            		$register_error = "style = \"display: block;\"";
            		$existing_email_error = "* Email already exists";
            	}
            	else {
            		$query = "INSERT INTO users " .
            				 "(Username, Password, Email) " .
               			  	 "VALUES ('$reg_username', '$reg_password', '$reg_email')";

               		// execute query in Forgetmenot database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysql_error());
					} // end if
					else {
						$login_sucessful = true;
					}
            	}
	        }

	        print('<!-- Title + Banner -->
				<ul class = "navigation">

					<!-- Section Title -->
					<div id = "title" class = "title">
						ForgetMeNot
					</div>');

	        if ($login_sucessful == true) {
	        	print('	<!-- Banner -->
					<li>Home</li>
					<li>My Bookmarks</li>
					<li>Sign Out</li>');
	        }
	        else {
	        	print('	<!-- Banner -->
					<li id = "login_prompt">Sign In</li>
					<li id = "register_prompt">Register</li>');
	        }

			print('</ul>

				<div id = "home">
					<h1 class = "title_centre">Welcome to ForgetMeNot!</h1>
					<div class = "main_bookmarks">

						<h3 class = "title_centre">Top 10 Bookmarks</h3>
						<ul>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
							<li>Test</li>
						</ul>
					</div>
				</div>
	        	');

	        print("<div class = \"modal\" id = \"login_inputs\" $login_error>");
	        print('
				<form class = "modal-content animate" method = "post" action = "forgetmenot.php">
					<div class = "container">
						<div class = "close_container">
							<span class = "close" id = "close_login" title = "Close Login">&times;</span>
						</div>

						<label><strong>Username: </strong></label>');
	        print("		<label class = \"error\" id = \"error_login\">$invalid_login_error</label>");
			print('		<input type = "text" class = "info" placeholder = "Enter Username" id = "username" required>
						<br>
						<label><strong>Password: </strong></label>
						<input type = "password" class = "info" placeholder = "Enter Password" id = "password" required>
				        <br>
						<button class = "action_button" type = "submit" name = "login_submit">Login</button>
					</div>
				</form>
			</div>');

	        print("<div class = \"modal\" id = \"register_inputs\" $register_error>");
			print('
				<form class = "modal-content animate" method = "post" action = "forgetmenot.php">
					<div class = "container">
						<div class = "close_container">
							<span class = "close" id = "close_register" title = "Close Login">&times;</span>
						</div>
						<br>
						<label><strong>Username: </strong></label>');
			print("		<label class = \"error\" id = \"error_register1\">$existing_username_error</label>");
			print('		<input type = "text" class = "info" placeholder = "Enter Username" id ="reg_username" required>
						<br>
						<br>
						<label><strong>Email: </strong></label>');
			print("		<label class = \"error\" id = \"error_register2\">$existing_email_error</label>");
			print('		<input type = "email" class = "info" placeholder = "Enter Email" id = "reg_email" required>
						<br>
						<br>
						<label><strong>Password: </strong></label>
						<input type = "password" class = "info" placeholder = "Enter Password" id = "reg_password" required>
						<button class = "action_button" type = "submit" name = "register_submit">Register</button>
					</div>
				</form>
			</div>');

		?>

	</body>
</html>