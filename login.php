<?php
session_start();

	include("connection.php");
	include("functions.php");

	logged_in_redirect();

	// Attempt login
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];

		if(!empty($user_name) && !empty($password) && !is_numeric($user_name) && !str_contains($user_name, '\'') && !str_contains($user_name, '"') && !str_contains($user_name, '\\'))
		{
			// Read user from db
			$result = null;
			try{
				$statement = $con->prepare("SELECT * FROM users WHERE user_name = ? LIMIT 1");
				$statement->bind_param("s", $user_name);
				$statement->execute();
				$result = $statement->get_result();
			}
			catch(Exception $e){
				echo "Something went wrong.<br><br>";
			}

			// Check result
			if($result && mysqli_num_rows($result) > 0)
			{
				$user_data = mysqli_fetch_assoc($result);

				if($user_data['password'] === $password)
				{
					$_SESSION['user_id'] = $user_data['user_id'];
					header("Location: index.php");
					die;
				}
			}
			echo "<p class=\"text-white bg-danger\">Username or password is incorrect</p>";
		}
		else{
			echo "<p class=\"text-white bg-danger\">Please enter valid information</p>";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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
			<div style="font-size: 26px; color: white; text-align:center">Login</div><br>

			<input id="text" type="text" name="user_name"><br><br>
			<input id="text" type="password" name="password"><br><br>

			<input id="button" type="submit" class="btn btn-primary" style="width:100%" value="Login"><br><br>

			<a href="signup.php" class="btn btn-primary" style="width:100%">Don't have an account? Signup!</a><br><br>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">
</body>
</html>