# Interlinear UI
(Run this after the back-end is setup)

## Prerequisites

1. PHP
2. Apache Server

## Instructions

1. Load the UI files on the Apache server
2. Update the following values in the header_config.php file
	
	$location = 'http://localhost:8080/interlinear/';
	$host = 'localhost';
	$name = 'root';
	$pass = '';
	$db = 'interlinear_fulldb';
where location is the location of the UI
	  host is the Host name
	  name is the username
	  pass is the password 
	  db is the database name
3. Now your UI should be up and running
	Check in your browser,
	http://localhost:8080/interlinear/grk2ayt/detail.php?id=23146
	You can edit the links on the page, verse by verse on the following link,
	http://localhost:8080/interlinear/grk2ayt/detail_old.php?id=23146
	You can also modify the links based on the Strong's numbers,
	http://localhost:8080/interlinear/grk2ayt/strong_old.php?s=26