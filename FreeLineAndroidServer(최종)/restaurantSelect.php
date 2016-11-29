<?php

	require_once("./configDatabase.php");

	$userNum = mysqli_real_escape_string($mysqli, $_POST['userNum']);
	
	$userNumStatement = mysqli_prepare($mysqli, "SELECT count(userNum) FROM WaitingPeople_tb WHERE userNum ='".$userNum."'");
	if(!mysqli_stmt_execute($userNumStatement)){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql waitNum error: '.mysqli_stmt_error($userNumStatement));
	}
	if(!$userNumStatement){
		echo json_encode(array('resultCode'=>'0'));
		die('mysql waitNum err: '.mysqli_error($mysqli));
	}

	mysqli_stmt_execute($userNumStatement);
	mysqli_stmt_bind_result($userNumStatement, $userNumExist);

	$userNumEx ="";	
	while(mysqli_stmt_fetch($userNumStatement)){
			$userNumEx = $userNumExist;
	}	

	mysqli_stmt_close($userNumStatement);

	if($userNumEx == ''){
		echo json_encode(array('resultCode'=>'1'));
	}
	else{
		//이미 대기표 뽑은 경우
		echo json_encode(array('resultCode'=>'2'));
	}
	mysqli_close($mysqli);

?>
