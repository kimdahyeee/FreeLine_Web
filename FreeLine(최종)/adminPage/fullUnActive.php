<?php session_start(); ?>
<?php

	require_once("../configDatabase.php");

	$beacon = mysqli_prepare($mysqli, "SELECT beaconID from Admin_tb where adminID = '".$_SESSION["adminID"]."'");

    if(!$beacon){
            echo "mysql 문제 ";
            die('mysql beacon error: '.mysqli_error($mysqli));
    }
    if(!mysqli_stmt_execute($beacon)){
            echo "statement문제 ";
            die('stmt beacon error: '.mysqli_stmt_error($beacon));
    }

    mysqli_stmt_execute($beacon);
    mysqli_stmt_bind_result($beacon, $beaconId);
    $beaconID ="";
    while(mysqli_stmt_fetch($beacon)){
            $beaconID = $beaconId;
    }

    $beaconFullActive = mysqli_prepare($mysqli, "Update BeaconInfo_tb set beaconFullCode='0' where beaconID= '".$beaconID."'");

    if(!$beaconFullActive){
        echo "mysql잘못 ";
        die('mysqli beaconActive error: '.mysqli_error($mysqli));
    }
    if(!mysqli_stmt_execute($beaconFullActive)){
        echo "stmt 잘못 ";
        die('stmt beaconActive err: '.mysqli_stmt_error($sql));
    }

    mysqli_stmt_execute($beaconFullActive);
    mysqli_stmt_close($beaconFullActive);

    echo "success";

?>
            



