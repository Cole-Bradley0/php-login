<?php
	//Connect to db and get user_data
	session_start();
	include("connection.php");
	include("functions.php");
	$user_data = check_login($con);

	//Read in existing comment
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		if(!isset($_GET["id"])){
			header("location: index.php");
			die();
		}
	}

	$commentResults = null;
	$id = $_GET["id"];
	try{
		// Get comment
		$statement = $con->prepare("SELECT * FROM comments WHERE id = ?");
		$statement->bind_param("i", $id);
		$statement->execute();
		$commentResults = $statement->get_result();
	}
	catch(Exception $e){
		echo "Something went wrong.<br><br>";
	}

	if(!$commentResults)
	{
		echo "Could not read comments from database!<br>";
		echo "<br><a href='../index.php'>back</a>";
		die();
	}
	elseif(!mysqli_num_rows($commentResults) > 0){
		echo "Could not find comment with given id from database!<br>";
		echo "<br><a href='../index.php'>back</a>";
		die();
	}

	$row = mysqli_fetch_array($commentResults);

	// Validate authorization
	if($row['user_name'] !== $user_data['user_name']){
		echo "You do not have permission to edit this comment!";
		echo "<br><a href='../index.php'>back</a>";
		die();
	}
	

	// Update comment
	$edited_text = "";
	$result = null;
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$edited_text = $_POST["edit_box"];

		if(empty($edited_text)){
			echo "Comment cannot be empty<br>";
		}
		else{
			try{
				// Prepare the query
			    $query = "UPDATE comments SET comment=? WHERE id = ?";
			    $stmt = mysqli_prepare($con, $query);

			    // Bind the parameters
			    mysqli_stmt_bind_param($stmt, "si", $edited_text, $id);

			    // Execute the prepared statement
			    mysqli_stmt_execute($stmt);

			    // Get the result
			    $stmt->get_result();

			    // Close the prepared statement
			    mysqli_stmt_close($stmt);

			    // Return to index
			    header("location: ../index.php");
			}
			catch(Exception $e){
				echo "Could not update comment!<br>";
			}

			die();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Index Page</title>
</head>
<body>

	Hello, <?=$user_data['user_name']?>. <a href="../logout.php">Logout</a>

	<br>
	<br>
	<br>
	<div>
		<form method="post">
			<label>Edit Comment:</label>
			<!--<input type="text" name="id" value="<?=$id?>"> -->
			<input type="text" name="edit_box" value="<?=$row['comment']?>">
			<input id="button" type="submit" value="Edit">
		</form>
		
	</div>
	<br>
	<br>
	<a href="../index.php">back</a>

</body>
</html>