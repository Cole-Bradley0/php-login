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

	$id = $_GET["id"];
	$commentQuery = "SELECT * FROM comments WHERE id = $id";
	$commentResults = mysqli_query($con, $commentQuery);

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

	//Validate authorization
	if($row['user_name'] !== $user_data['user_name']){
		echo "You do not have permission to edit this comment!";
		echo "<br><a href='../index.php'>back</a>";
		die();
	}
	

//Update comment
	$edited_text = "";
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		$edited_text = $_POST["edit_box"];

		if(empty($edited_text)){
			echo "Comment cannot be empty<br>";
		}
		else{
			//Sanatize input
			$edited_text = addslashes($edited_text);
			$commentQuery = "UPDATE comments SET comment='$edited_text' WHERE id = $id";
			$result = mysqli_query($con, $commentQuery);
			if(!$result){
				echo "Could not update comment!<br>";
			}
			else{
				header("location: ../index.php");
				die();
			}
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