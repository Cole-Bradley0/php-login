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
			echo "Username or password is incorrect";
		}
		else{
			echo "Please enter valid information";
		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<style type="text/css">
	
	#text{
		height: 25px;
		border-radius: 5px;
		padding: 4px;
		border: solid thin #aaa;
		width: 97%;
	}

	#button{
		padding: 10px;
		width: 100px;
		color: white;
		background-color: lightblue;
		border: none;
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
			<div style="font-size: 20px; color: white;">Login</div><br>

			<input id="text" type="text" name="user_name"><br><br>
			<input id="text" type="password" name="password"><br><br>

			<input id="button" type="submit" value="Login"><br><br>

			<a href="signup.php">Click to signup</a><br><br>
		</form>
	</div>
</body>
</html>