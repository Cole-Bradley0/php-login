<?php
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	//Post comment
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$comment = $_POST['comment'];
		$user_name = $user_data['user_name'];

		if(!empty($comment) && !is_numeric($comment))
		{
			//Save comment to db
			try{
			    // Prepare the query
			    $query = "INSERT INTO comments (user_name,comment) VALUES (?, ?)";
			    $stmt = mysqli_prepare($con, $query);

			    // Bind the parameters
			    mysqli_stmt_bind_param($stmt, "ss", $user_name, $comment);

			    // Execute the prepared statement
			    mysqli_stmt_execute($stmt);

			    // Close the prepared statement
			    mysqli_stmt_close($stmt);
			}
			catch(Exception $e){
				echo "<p class=\"text-white bg-danger\">Could not save comment to database!</p><br>";
			}
		}
		else{
			echo "<p class=\"text-white bg-danger\">Please enter valid information (comment must not be empty or only numbers)</p><br>";
		}
	}

	//Get all comments
	$commentQuery = "SELECT * FROM comments";
	$commentResults = null;

	try{
		$commentResults = mysqli_query($con, $commentQuery);
		if(!$commentResults || !mysqli_num_rows($commentResults) > 0)
		{
			echo "<p class=\"text-white bg-danger\">Could not read comments from database!</p><br>";
		}
	}
	catch(Exception $e){
		echo "<p class=\"text-white bg-danger\">Could not read comments from database!</p><br>";
	}

	

?>

<!DOCTYPE html>
<html>
<head>
	<title>Index Page</title>
	<link href="bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body style="margin-left:50px">

	Hello, <?=$user_data['user_name']?>. <a href="logout.php" class="btn btn-sm btn-secondary">Logout</a>

	<h1>PHP and MySQL login app</h1>

	<h2>CRUD functions:</h2>
	<form method="post">
		<i class="bi bi-chat-right-dots-fill"></i>
		<input id="text" type="text" name="comment" style="margin:10px">
		<input id="button" class="btn btn-primary" type="submit"  value="Submit"><br><br>
	</form>
	<?php 
	while($row = mysqli_fetch_array($commentResults)) {
		echo "<div class=\"p-2 rounded shadow\" style=\"width:550px\">";
	    echo "<b>" . $row['user_name'] . "</b>" . ": " . $row['comment'];
	    echo "<br>" . $row['date'];
	    if($user_data['user_name'] === $row['user_name']){
	    	echo "
	    	<br><a href='edit.php/?id=$row[id]' class=\"btn btn-sm btn-secondary\">edit</a>
	    	<a href='delete.php/?id=$row[id]' class=\"btn btn-sm btn-danger\">delete</a>
	    	";
	    }
	    echo "</div><br>";
	}?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js">
</body>
</html>