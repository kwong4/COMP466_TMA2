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

		<!-- Title + Banner -->
		<ul class = "navigation">

			<!-- Section Title -->
			<div id = "title" class = "title">
				ForgetMeNot
			</div>

			<!-- Banner -->
			<li id = "login_prompt">Sign In</li>

		</ul>

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

		<div class = "modal" id = "login_inputs">
			<form class = "modal-content animate">
				<div class = "container">
					<div class = "close_container">
						<span class="close" id = "close_login" title="Close Login">&times;</span>
					</div>

					<label><strong>Username: </strong></label>
					<input type="text" class = "login_info" placeholder="Enter Username" id ="username" required>
					<br>
					<label><strong>Password: </strong></label>
					<input type="password" class = "login_info" placeholder="Enter Password" id = "password" required>
			        <br>
					<button class = "login_button" type="submit">Login</button>
				</div>
			</form>
		</div>

	</body>
</html>