<?php
session_start();

	include("connection.php");
	include("functions.php");

	logged_in_redirect();

	// Sign up
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name) && !str_contains($user_name, '\'') && !str_contains($user_name, '"') && !str_contains($user_name, '\\') && !str_contains($password, '\'') && !str_contains($password, '"') && !str_contains($password, '\\'))
		{
			// Insert new user into db
			try {
			    // Generate random user_id
			    $user_id = random_num(20);

			    // Prepare the query
			    $query = "INSERT INTO users (user_id, user_name, password) VALUES (?, ?, ?)";
			    $stmt = mysqli_prepare($con, $query);

			    // Bind the parameters
			    mysqli_stmt_bind_param($stmt, "iss", $user_id, $user_name, $password);

			    // Execute the prepared statement
			    mysqli_stmt_execute($stmt);

			    // Close the prepared statement
			    mysqli_stmt_close($stmt);

			} catch (Exception $e) {
				echo "<p class=\"text-white bg-danger\">Something went wrong.</p>";
			}

			header("Location: login.php");
			die;
		}
		else{
			echo "<p class=\"text-white bg-danger\">Please enter valid information</p>";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link href="bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<body>
	<style type="text/css">
	
	#text{
		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 100%;
	}

	#box{
		background-color: grey;
		margin: auto;
		width: 300px;
		padding: 20px;
	}

	</style>

	<div id="box">
		<form method="post">
			<div style="font-size: 26px; color: white; text-align:center">Signup</div><br>

			<input id="text" type="text" name="user_name"><br><br>
			<input id="text" type="password" name="password"><br><br>

			<input id="button" class="btn btn-primary" style="width:100%" type="submit" value="Signup"><br><br>

			<a href="login.php" class="btn btn-primary" style="width:100%">Return to Login</a><br><br>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">
</body>
</html>