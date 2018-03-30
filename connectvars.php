<?php
	//Connection constants
	
	define('DB_HOST', 'db');
	define('DB_USER', 'user');
	define('DB_PASSWORD', 'pass');
	define('DB_NAME', 'dbname');
	
	define('TABLE_NAME', 'tablename');
	
	//Database connect
	
	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
	or die('Error connecting to MySQL server.');

	$db_selected = mysql_select_db(DB_NAME, $dbc);
	if (!$db_selected) {
		die ('Error selecting database : ' . mysql_error());
	}
	
?>