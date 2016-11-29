<?php

	require_once("./configDatabase.php");

	//클라이언트에서 서버로
	$beaconID = mysqli_real_escape_string($mysqli, $_POST['beaconID']);
	$waitName = mysqli_real_escape_string($mysqli, $_POST['waitName']);
	$waitTel = mysqli_real_escape_string($mysqli, $_POST['waitTel']);
	$userNum = mysqli_real_escape_string($mysqli, $_POST['userNum']);
	$waitNumOfPeople = mysqli_real_escape_string($mysqli, $_POST['waitNumOfPeople']);

	//대기번호 만들기
	$waitNumStatement = mysqli_prepare($mysqli, "SELECT max(waitNum) from WaitingPeople_tb WHERE beaconID='".$beaconID."'");
	if(!mysqli_stmt_execute($waitNumStatement)){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql waitNum error: '.mysqli_stmt_error($waitNumStatement));
	}
	if(!$waitNumStatement){
		echo json_encode(array('resultCode'=>'0'));
		die('mysql waitNum err: '.mysqli_error($mysqli));
	}
	mysqli_stmt_execute($waitNumStatement);
	mysqli_stmt_bind_result($waitNumStatement, $max);
	$waitNum ="";	
	while(mysqli_stmt_fetch($waitNumStatement)){
			$waitNum = $max + 1;
	}	
	
	mysqli_stmt_close($waitNumStatement);

	//대기자 대기db에 넣기	
	$userWhoWait = mysqli_prepare($mysqli, "INSERT INTO WaitingPeople_tb(beaconID, waitNum, waitName, waitTel, waitNumOfPeople, waitTime, userNum) values('".$beaconID."',".$waitNum.",'".$waitName."','".$waitTel."',".$waitNumOfPeople.",NOW(), '".$userNum."')");

	if(!$userWhoWait){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql userWhoWait error: '.mysqli_error($mysqli));
	}

	if(!mysqli_stmt_execute($userWhoWait)){
		echo json_encode(array('resultCode'=>'0'));
		die('stmt userWhoWait execute err: '.mysqli_stmt_error($userWhoWait));
	}	
	mysqli_stmt_close($userWhoWait);

	
	//통계 디비에 넣기
	$userStatistics = mysqli_prepare($mysqli, "INSERT INTO Statistics_tb(beaconID, waitTime) values('".$beaconID."',NOW())");
	if(!$userStatistics){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql userStatistics error: '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($userStatistics)){
		echo json_encode(array('resultCode'=>'0'));
		die('stmt userStatistics error: '.mysqli_stmt_error($userStatistics));
	}
		
	mysqli_stmt_close($userStatistics);
	
	//앞 대기 인원 출력
	$preWait = mysqli_prepare($mysqli, "SELECT count(waitNum) from WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum<".$waitNum."");

	if(!$preWait){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql preWait error: '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($preWait)){
		echo json_encode(array('resultCode'=>'0'));
		die('stmt preWait error: '.mysqli_stmt_error($preWait));
	}
	mysqli_stmt_execute($preWait);
	mysqli_stmt_bind_result($preWait, $pre);
	
	$pre2="";
	while(mysqli_stmt_fetch($preWait)){
		$pre2 = $pre;
	}

	mysqli_stmt_close($preWait);

	echo json_encode(array('resultCode'=>'1', 'waitNum'=>"$waitNum", 'preWaitingCount'=>"$pre2"));

	mysqli_close($mysqli);
	echo "1";
?>