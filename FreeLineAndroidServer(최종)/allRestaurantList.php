<?php
	require_once("./configDatabase.php");

	$sql = mysqli_prepare($mysqli, "SELECT beaconID, storeName, storeTel, storeLocation, storeIntroduction, storeImage, storeCategory FROM BeaconInfo_tb");
	if(!$sql){
		echo json_encode(array('resultCode'=>'-1'));
		die("statement query error: ".mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($sql)){
		echo json_encode(array('resultCode'=>'-1'));
		die("statement execute error: ".mysqli_stmt_execute($sql));
	}

	mysqli_stmt_store_result($sql);
	mysqli_stmt_bind_result($sql, $beaconID, $storeName, $storeTel, $storeLocation, $storeIntroduction, $storeImage, $storeCategory);

	$list = array();
	$count = 0;

	while(mysqli_stmt_fetch($sql)){
		$list['resultCode'] = "1";
		$list[$count] = array();
		$list[$count]['beaconID'] = $beaconID;
		$list[$count]['storeName'] = $storeName;
		$list[$count]['storeTel'] = $storeTel;
		$list[$count]['storeLocation'] = $storeLocation;
		$list[$count]['storeIntroduction'] = $storeIntroduction;
		$list[$count]['storeImage'] = $storeImage;
		$list[$count]['storeCategory'] = $storeCategory;
		$count++;
	}

	echo json_encode($list, JSON_UNESCAPED_UNICODE);
	mysqli_stmt_close($sql);
	mysqli_close($mysqli);
?>