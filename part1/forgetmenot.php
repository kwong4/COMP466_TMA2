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
				  	die(mysqli_error($database));
				} // end if

            	if (mysqli_num_rows($result) == 1) {
            		setcookie("logged_in", "true");
            		setcookie("username", $username);
            		header("Location: forgetmenot.php");
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

               		// execute query in Forgetmenot database
					if (!($result = mysqli_query($database, $query))) {
					  	print("<p>Could not execute query!</p>");
					  	die(mysqli_error($database));
					} // end if
					else {
						setcookie("logged_in", "true");
            			setcookie("username", $reg_username);
            			header("Location: forgetmenot.php");
					}
            	}
	        }
			else if (isset($_POST["add_bookmark"])) {
	        	$my_bookmark_page = "style = \"display: block;\"";
	        	$my_home_page = "style = \"display: none;\"";

	        	$bookmark = isset($_POST["bookmark"]) ? $_POST["bookmark"] : "";
		        $url = isset($_POST["url"]) ? $_POST["url"] : "";
		        $username = $_COOKIE["username"];

	            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
	            	$invalid_url = $url;
	            	$invalid_url_msg = "* Invalid URL";
	            	$invalid_name = $bookmark;
	            }
	            else {
	            	// build SELECT query
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
	        else if (isset($_POST["delete_bookmark"])) {
	        	$my_bookmark_page = "style = \"display: block;\"";
	        	$my_home_page = "style = \"display: none;\"";

	        	$url_name = isset($_POST["url_name"]) ? $_POST["url_name"] : "";
		        $url_link = isset($_POST["url_link"]) ? $_POST["url_link"] : "";
		        $username = $_COOKIE["username"];

		        // build SELECT query
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
	        
	        print('<!-- Title + Banner -->
				<ul class = "navigation">

					<!-- Section Title -->
					<div id = "title" class = "title">
						ForgetMeNot
					</div>');

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

			if (isset($_COOKIE["logged_in"])) {
				print(' <h1 class = "title_centre">Welcome ' .  $_COOKIE["username"] . '!</h1>');
			}
			else {
				print(" <h1 class = \"title_centre\" id = \"welcome\">Welcome to ForgetMeNot!</h1>");
			}
							
			// build SELECT query
           	$query = "SELECT Url " .
           			 "FROM bookmarks " .
           			 "GROUP BY Url, Name " .
           			 "ORDER BY COUNT(Url) DESC " .
           			 "LIMIT 10";
							
           	// execute query in Forgetmenot database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			print('<div class = "main_bookmarks">');

			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Bookmarks on the site yet. Be the first!</h3>
						<ol>');
			}
			else {
				print('	<h3 class = "title_centre">Top 10 Bookmarks</h3>
						<ol>');
			}

            while ($row = mysqli_fetch_row($result)) {

            	$str = file_get_contents($row[0]);
            	if(strlen($str) > 0) {
			    	$str = trim(preg_replace('/\s+/', ' ', $str));
			    	preg_match("/\<title\>(.*)\<\/title\>/i", $str, $title);
				}
               	print("<li><a href = \"$row[0]\">$title[1]</a></li>");

            } // end while

			print('		</ol>
					</div>
				</div>');

			print("<div id = \"mybookmark_content\" class = \"hidden\" $my_bookmark_page>");

			print('<div class = "my_bookmarks">
				<h1 class = "title_centre">Your BookMarks!</h1>');

			// build SELECT query
           	$query = "SELECT Name, Url " .
           			 "FROM bookmarks " .
           			 "WHERE Username = '" . $_COOKIE["username"] . "'";

           	// execute query in Forgetmenot database
			if (!($result = mysqli_query($database, $query))) {
				print("<p>Could not execute query!</p>");
				die(mysqli_error($database));
			} // end if

			if (mysqli_num_rows($result) == 0) {
				print('	<h3 class = "title_centre">No Bookmarks added yet.</h3>
						<ul>');
			}
			else {
				print('	<h3 class = "title_centre">Bookmarks</h3>
						<ul>');
			}

			$counter = 0;
			// fetch each record in result set
            while ($row = mysqli_fetch_row($result)) {

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

			print('</ul>
				</div>');

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
				</div>');

			print('</div>');

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
			mysqli_close($database);
		?>

	</body>
</html>