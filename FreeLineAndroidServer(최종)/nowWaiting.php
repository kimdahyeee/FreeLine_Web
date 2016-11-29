<?php

        require_once("./configDatabase.php");

        $userNum = mysqli_real_escape_string($mysqli, $_POST['userNum']);       //userNum -> params2

        //비콘아이디 받아오기
        $userInfo = mysqli_prepare($mysqli, "SELECT beaconID, waitNum from WaitingPeople_tb WHERE userNum='".$userNum."'");
        if(!$userInfo){
                echo json_encode(array('resultCode'=>'-1'));
                die('mysql preWait err: '.mysqli_error($mysqli));
        }
        if(!mysqli_stmt_execute($userInfo)){
                echo json_encode(array('resultCode'=>'0'));
                die('stmt preWait error: '.mysqli_stmt_error($userInfo));
        }

        mysqli_stmt_execute($userInfo);
        mysqli_stmt_bind_result($userInfo, $beaconId, $waitNumber);

        $beaconID ="";
        $waitNum ="";
        while(mysqli_stmt_fetch($userInfo)){
                $beaconID = $beaconId;
                $waitNum = $waitNumber;
        }

        mysqli_stmt_close($userInfo);
        $preWait = mysqli_prepare($mysqli, "SELECT count(waitNum)from WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum < ".$waitNum."");
        if(!$preWait){
                echo json_encode(array('resultCode'=>'-1'));
                die('mysql preWait err: '.mysqli_error($mysqli));
        }
        if(!mysqli_stmt_execute($preWait)){
                echo json_encode(array('resultCode'=>'0'));
                die('stmt preWait error: '.mysqli_stmt_error($preWait));
        }
        mysqli_stmt_execute($preWait);
        mysqli_stmt_bind_result($preWait, $pre);

        $preCount ="";
        while(mysqli_stmt_fetch($preWait)){
                $preCount = $pre;
        }

        echo json_encode(array('resultCode'=>'1', 'preWaitingCount'=>$preCount, 'waitNum'=>$waitNum));
        mysqli_stmt_close($preWait);

        mysqli_close($mysqli);

?>
