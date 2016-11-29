<?php

	require_once("./configDatabase.php");

	$beaconID = mysqli_real_escape_string($mysqli, $_POST['beaconID']);

	$storeList = mysqli_prepare($mysqli, "SELECT beaconID, beaconFullCode, beaconActiveCode, storeName, storeImage, storeTel, storeLocation, storeIntroduction, storeCategory from BeaconInfo_tb WHERE beaconID='".$beaconID."'");

	if(!$storeList){
		echo json_encode(array('resultCode'=>'-1'));
		die('mysql preWait err: '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($storeList)){
		echo json_encode(array('resultCode'=>'0'));
		die('stmt preWait error: '.mysqli_stmt_error($storeList));
	}

	mysqli_stmt_store_result($storeList);
	mysqli_stmt_bind_result($storeList, $beaconID, $beaconFullCode, $beaconActiveCode, $storeName, $storeImage, $storeTel, $storeLocation, $storeIntroduction, $storeCategory);

	$openStore = array();
	while(mysqli_stmt_fetch($storeList)){
		$openStore['resultCode'] = "1";
		$openStore['beaconID'] = $beaconID;
		$openStore['beaconFullCode'] = $beaconFullCode;
		$openStore['beaconActiveCode'] = $beaconActiveCode;
		$openStore['storeName'] = $storeName;
		$openStore['storeImage'] = $storeImage;
		$openStore['storeTel'] = $storeTel;
		$openStore['storeLocation'] = $storeLocation;
		$openStore['storeIntroduction'] = $storeIntroduction;
		$openStore['storeCategory'] = $storeCategory;
	}

	mysqli_stmt_close($storeList);

	$statement = mysqli_prepare($mysqli, "SELECT count(*) FROM WaitingPeople_tb WHERE beaconID='".$beaconID."'");

	if(!$statement){
		echo json_encode(array('resultCode'=>'0'));	
	}

	if(!mysqli_stmt_execute($statement)){
		echo json_encode(array('resultCode'=>'-1'));
		die("statement execute err: ".mysqli_stmt_error($statement));
	}

	mysqli_stmt_store_result($statement);
	mysqli_stmt_bind_result($statement, $count);

	$restaurantSelect = array();
	$resultCode = "1";

	while(mysqli_stmt_fetch($statement)){
		$restaurantSelect['resultCode'] = $resultCode;
		$restaurantSelect['totalWaitingCount'] = $count;
	}

	echo json_encode($openStore + $restaurantSelect, JSON_UNESCAPED_UNICODE);
	mysqli_stmt_close($statement);
	mysqli_close($mysqli);


?>
