<?php	include 'config.php'; 
	if(!isset($_SESSION))
		session_start();

	if(!isset($_SESSION['role']) || $_SESSION['role'] > 2) {
		if($current_page =='gallery.php' || 
			$current_page =='fileupload.php' || 
			$current_page =='books.php' || 
			$current_page =='users.php') {
				header('location:index.php');
		}
	} else if($_SESSION['role'] != 1) {
		if($current_page =='users.php') header('location:index.php');
	}

?> 
<!DOCTYPE html>
<html>
<head>
	<title>Mandy's Book Club</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="main.css?id=13">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,900&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
</head>
<body>

<header>
	<img src="media/header.png" id="headerimg">
	<h1 id="logo">Mandy's Book Club</h1>
	<div id="menu">
		<ul id="mainMenu">
			<li class="menuitems"><a href="index.php" class="<?php echo ($current_page =='index.php')?'active': NULL?>">Home</a></li>
			<li class="menuitems"><a href="about.php" class="<?php echo ($current_page =='about.php')?'active': NULL?>">About Us</a></li>
			<li class="menuitems"><a href="browse.php" class="<?php echo ($current_page =='browse.php')?'active': NULL?>">Browse</a></li>
			<li class="menuitems"><a href="mybooks.php" class="<?php echo ($current_page =='mybooks.php')?'active': NULL?>">My Books</a></li>
			<li class="menuitems"><a href="contact.php" class="<?php echo ($current_page =='contact.php')?'active': NULL?>">Contact</a></li>
			<?php
			if(isset($_SESSION['role'])){
				if ($_SESSION['role']=="1" || $_SESSION['role']=="2") {
			?>
					<li class="menuitems"><a href="gallery.php" class="<?php echo ($current_page =='gallery.php')?'active': NULL?>">Gallery</a></li>
					<li class="menuitems"><a href="fileupload.php" class="<?php echo ($current_page =='fileupload.php')?'active': NULL?>">Upload images</a></li>
					<li class="menuitems"><a href="books.php" class="<?php echo ($current_page =='books.php')?'active': NULL?>">Manage Books</a></li>
			<?php		
				} 
				if ($_SESSION['role']=="1") {
			?>
					<li class="menuitems"><a href="users.php" class="<?php echo ($current_page =='users.php')?'active': NULL?>">Manage Users</a></li>
			<?php
				} 
			}
			?>
			
	</div>
</header>