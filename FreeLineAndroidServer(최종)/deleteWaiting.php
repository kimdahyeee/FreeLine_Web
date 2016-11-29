<?php

	require_once("./configDatabase.php");

	$beaconID = mysqli_real_escape_string($mysqli, $_POST['beaconID']);
	$waitNum = mysqli_real_escape_string($mysqli, $_POST['waitNum']);

	$deleteSt = mysqli_prepare($mysqli, "DELETE from WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum = ".$waitNum."");

	mysqli_stmt_execute($deleteSt);

	mysqli_stmt_close($deleteSt);
	echo json_encode(array('resultCode'=>'1'));

	mysqli_close($mysqli);

?>
