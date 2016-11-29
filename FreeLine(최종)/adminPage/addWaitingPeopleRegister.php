<?php 
    session_start();
	require_once("../configDatabase.php");

	$customerName=mysqli_real_escape_string($mysqli, $_POST['customerName']);
    $customerTel = mysqli_real_escape_string($mysqli, $_POST['customerTel']);
    $customerNumberOfPeople = mysqli_real_escape_string($mysqli, $_POST['customerNumberOfPeople']);

    //beaconID 가져오기
    $beaconIDGet = mysqli_prepare($mysqli, "SELECT beaconID FROM Admin_tb WHERE adminID = '".$_SESSION["adminID"]."'");
    mysqli_stmt_execute($beaconIDGet);
    mysqli_stmt_bind_result($beaconIDGet, $beaconIDrec);
    $beaconID = "";
    while (mysqli_stmt_fetch($beaconIDGet)) {
    	$beaconID = $beaconIDrec;
    }

    mysqli_stmt_close($waitNumStatement);

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
	$userWhoWait = mysqli_prepare($mysqli, "INSERT INTO WaitingPeople_tb(beaconID, waitNum, waitName, waitTel, waitNumOfPeople, waitTime) values('".$beaconID."',".$waitNum.",'".$customerName."','".$customerTel."',".$customerNumberOfPeople.",NOW())");

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
		
    echo "success";
	mysqli_stmt_close($userStatistics);
    mysqli_close($mysqli);
?>