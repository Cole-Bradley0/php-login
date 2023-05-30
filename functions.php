<?php

function check_login($con)
{
	if(isset($_SESSION['user_id']))
	{
		$id = $_SESSION['user_id'];
		$query = "select * from users where user_id = '$id' limit 1";

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//Redirect to login
	header("Location: login.php");
	die;
}

function logged_in_redirect()
{
	if(isset($_SESSION['user_id']))
	{
		//Redirect to index
		header("Location: index.php");
		die;
	}
}

function random_num($max_length)
{
	$text = "";
	if($max_length < 5)
	{
		$max_length = 5;
	}
	$len = rand(4,$max_length);

	for ($i=0; $i < $len; $i++) { 
		$text .= rand(0,9);
	}

	return $text;
}