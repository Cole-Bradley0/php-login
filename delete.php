<?php
	// Connect to db and get user_data
	session_start();
	include("connection.php");
	include("functions.php");
	$user_data = check_login($con);

	// Delete comment if authorized
	if($_SERVER['REQUEST_METHOD'] == "GET"){
		if(!isset($_GET["id"])){
			header("location: index.php");
			die();
		}
	}

	$id = $_GET["id"];

	//Validate authorization
	$commentQuery = "SELECT * FROM comments WHERE id = $id";
	$commentResults = mysqli_query($con, $commentQuery);
	$row = mysqli_fetch_array($commentResults);
	if($row['user_name'] !== $user_data['user_name']){
		echo "You do not have permission to delete this comment!";
		echo "<br><a href='../index.php'>back</a>";
		die();
	}

	//Delete
	$delete_query = "DELETE FROM comments WHERE id = $id";
	$result = mysqli_query($con, $delete_query);
	if(!$result){
		echo "Could not delete comment!<br>";
	}
	else{
		header("location: ../index.php");
		die();
	}


