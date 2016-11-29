<?php session_start(); ?>
<!-- <html>
<head>
	<title>Beacon management Page</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="assets/css/main.css" />
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body> 
-->
<?php
	require_once("./configDatabase.php");

	$beacon = mysqli_prepare($mysqli,"SELECT beaconID FROM Admin_tb WHERE adminID= '".$_SESSION['adminID']."'");
	
	if(!$beacon){
		echo "mysql 잘못 ";
		die ('mysqli beacon error: '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($beacon)){
		echo "stmt 잘못 ";
		die ('stmt beacon err: '.mysqli_stmt_error($beacon));
	}

	mysqli_stmt_store_result($beacon);
	mysqli_stmt_bind_result($beacon, $beaconid2);

	$beaconID="";
	while(mysqli_stmt_fetch($beacon)){
		$beaconID=$beaconid2;
	}

	$ActiveCode = mysqli_prepare($mysqli, "UPDATE BeaconInfo_tb SET beaconActiveCode='0' WHERE beaconID= '".$beaconID."'");
	mysqli_stmt_execute($ActiveCode);
	mysqli_stmt_close($beacon);
	mysqli_stmt_close($ActiveCode);
	mysqli_close($mysqli);

	session_destroy();
?>
<meta http-equiv='refresh' content='0; url=main.html'>
<!--
</body>
</html>
-->
