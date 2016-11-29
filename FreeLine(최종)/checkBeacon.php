<?php
    require_once("./configDatabase.php");

    $beaconId = mysqli_real_escape_string($mysqli, $_POST['beaconId']);

    $sql = mysqli_prepare($mysqli, "SELECT beaconID From BeaconInfo_tb WHERE beaconID ='".$beaconId."'");

    if(!$sql){
        echo "mysql 문제";
        die('mysql sql error: '.mysqli_error($mysqli));
    }
    if(!mysqli_stmt_execute($sql)){
        echo "statement 문제";
        die('stmt sql error: '.mysqli_stmt_error($sql));
    }

    mysqli_stmt_execute($sql);
    mysqli_stmt_bind_result($sql, $ch);

    $ch2="";
    while(mysqli_stmt_fetch($sql)){
            $ch2 = $ch;
    }

    if($ch2 == $beaconId){
        echo "fail";
    }else{
        echo "success";
    }

    mysqli_stmt_close($sql);
    mysqli_close($mysqli);

?>

