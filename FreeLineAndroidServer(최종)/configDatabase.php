<?php

	date_default_timezone_set('Asia/Seoul');
	header('Content-Type:text/html; charset=utf-8');

	define("SERVER_ADDRESS", "http://cslab2.kku.ac.kr/~201341307/");
	define("GOOGLE_API_KEY", "AIzaSyB3n3v06mK7fBf9wmuQ0aii7VTuObHt38c");
	
	$mysqli = new mysqli('localhost', '201341307', 'rlaek862', '201341307');
	if($mysqli->connect_errno){
		die('Connection Error('.$mysqli->connect_errno.'): '.
			$mysqli->connect_error);
	}

	$mysqli->set_charset('utf8');

?>
