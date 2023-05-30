<?php
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$comment = $_POST['comment'];
		$user_name = $user_data['user_name'];

		if(!empty($comment) && !is_numeric($comment))
		{
			//Sanatize input
			$comment = addslashes($comment);
			//Save comment to db
			$query = "insert into comments (user_name,comment) values ('$user_name','$comment')";
			mysqli_query($con, $query);
		}
		else{
			echo "Please enter valid information (comment must not be empty or numeric)<br><br>";
		}
	}

	$commentQuery = "SELECT * FROM comments";
	$commentResults = mysqli_query($con, $commentQuery);

	if(!$commentResults || !mysqli_num_rows($commentResults) > 0)
	{
		echo "Could not read comments from database!<br>";
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Index Page</title>
</head>
<body>

	Hello, <?=$user_data['user_name']?>. <a href="logout.php">Logout</a>
	<h1>PHP and MySQL login app</h1>
	<h2>CRUD functions:</h2>
	<form method="post">
		<label>Comment:</label>
		<input id="text" type="text" name="comment" ><br><br>
		<input id="button" type="submit" value="Submit"><br><br>
	</form>
	<?php 
	while($row = mysqli_fetch_array($commentResults)) {
	    echo $row['user_name'] . ": " . $row['comment'];
	    echo "<br>" . $row['date'];
	    if($user_data['user_name'] === $row['user_name']){
	    	echo "
	    	<br><a href='edit.php/?id=$row[id]'>edit</a>
	    	<br><a href='delete.php/?id=$row[id]'>delete</a>
	    	";
	    }
	    echo "<br><br>";
	}?>

</body>
</html>