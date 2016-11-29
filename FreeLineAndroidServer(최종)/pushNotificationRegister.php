<?php

	require_once("./configDatabase.php");
	$token = mysqli_real_escape_string($mysqli, $_POST['Token']);
	$userNum = mysqli_real_escape_string($mysqli, $_POST['UserNum']);

	if(isset($token)){
		//db에 토큰 저장
		$query = "INSERT INTO PushInfo_tb(userNum, token) VALUES ('$userNum','$token') ON DUPLICATE KEY UPDATE token = '$token'; ";

		mysqli_query($mysqli, $query);

		mysqli_close($mysqli);
	}

?>