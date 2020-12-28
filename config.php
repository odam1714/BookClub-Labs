<?php

	$current_uri = $_SERVER['REQUEST_URI'];
	$current_array = explode('/', $current_uri);
	$current_page = end($current_array);

	$dbserver = 'localhost';
	$dbuser = 'root';
	$dbpass = 'root';
	$dbname = 'library';

?>