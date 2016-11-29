<?php session_start(); ?>

<?php
	
	define("GOOGLE_API_KEY", "AIzaSyBUOurWxGDkgqHSQ5aGlZ3lW___dYp7uO4");

	function send_notification ($tokens, $message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
                'to' =>$tokens,
                'notification'=> $message
            );

        $headers = array(
                'Authorization: key ='.GOOGLE_API_KEY,
                'Content-Type: application/json'
            );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if($result == FALSE ){
            die('Curl failed: ' . curl_error($ch));
        }

       curl_close($ch);

       return $result;

       
   }


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
    mysqli_stmt_bind_result($beacon, $beaconID1);
    $beaconID ="";
    while(mysqli_stmt_fetch($beacon)){
            $beaconID = $beaconID1;
    }

    //대기번호
    $waitNumberStm = mysqli_prepare($mysqli, "SELECT min(waitNum) from WaitingPeople_tb where beaconID= '".$beaconID."'");

    if(!$waitNumberStm){
        echo "mysql 문제";
        die('mysql waitNumber error: '.mysqli_stmt_error($mysqli));
    }
    if(!mysqli_stmt_execute($waitNumberStm)){
        echo "statement문제";
        die('stmt waitNumber error: '.msyqli_stmt_error($waitNumberStm));
    }

    mysqli_stmt_store_result($waitNumberStm);
    mysqli_stmt_bind_result($waitNumberStm, $waitNumber);

    while (mysqli_stmt_fetch($waitNumberStm)) {
        if($waitNumber != NULL){
            $_SESSION['waitNum'] = $waitNumber; //이제 불려질 번호
        }else{
            $_SESSION['waitNum'] = 0;
        }
    }

    mysqli_stmt_close($waitNumberStm);
    

//push 알림 전송
    $waitNumber1 = $waitNumber +1;
    $waitNumber2 = $waitNumber +2;
    $tokenReceive = mysqli_prepare($mysqli, "SELECT token FROM PushInfo_tb WHERE exists (SELECT userNum FROM WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum ='".$waitNumber."')");

    if(!$tokenReceive){
    echo "mysql 문제 ";
    die('mysql tokenReceive error: '.mysqli_error($mysqli));
    }

    if(!mysqli_stmt_execute($tokenReceive)){
        echo "statement 문제 ";
        die('stmt tokenReceive error: '.mysqli_stmt_error($tokenReceive));
    }
    
    mysqli_stmt_execute($tokenReceive);

    $token ="";
    
    mysqli_stmt_bind_result($tokenReceive, $tokenRec);
    while(mysqli_stmt_fetch($tokenReceive)){
            $token = $tokenRec;
    }

    $title ="FreeLine";
    $message ="차례가 임박했습니다! 매장앞으로와주세요.";
    $messageArr = array("title"=>$title, "text" => $message);
    send_notification($token, $messageArr);

    $tokenReceive1 = mysqli_prepare($mysqli, "SELECT token FROM PushInfo_tb WHERE exists (SELECT userNum FROM WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum ='".$waitNumber1."')");

    if(!$tokenReceive1){
    echo "mysql 문제 ";
    die('mysql tokenReceive error: '.mysqli_error($mysqli));
    }

    if(!mysqli_stmt_execute($tokenReceive1)){
        echo "statement 문제 ";
        die('stmt tokenReceive error: '.mysqli_stmt_error($tokenReceive1));
    }

    mysqli_stmt_execute($tokenReceive1);
    $token1 ="";
    mysqli_stmt_bind_result($tokenReceive1, $tokenRec1);
    while(mysqli_stmt_fetch($tokenReceive1)){
            $token1 = $tokenRec1;
    }
    if($token1 != ''){
        $title ="FreeLine";
        $message ="차례가 임박했습니다! 매장앞으로와주세요.";

        $messageArr = array("title"=>$title, "text" => $message);
        send_notification($token1, $messageArr);
    }

    $tokenReceive2 = mysqli_prepare($mysqli, "SELECT token FROM PushInfo_tb WHERE exists (SELECT userNum FROM WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum ='".$waitNumber2."')");
    mysqli_stmt_execute($tokenReceive2);
    $token2 = "";
    mysqli_stmt_bind_result($tokenReceive2, $tokenRec2);
    while(mysqli_stmt_fetch($tokenReceive2)){
            $token2 = $tokenRec2;
    }

    if($token2 != ''){
        $title ="FreeLine";
        $message ="차례가 임박했습니다! 매장앞으로와주세요.";

        $messageArr = array("title"=>$title, "text" => $message);
        send_notification($token2, $messageArr);
    }

    mysqli_stmt_close($tokenReceive);
    mysqli_stmt_close($tokenReceive1);
    mysqli_stmt_close($tokenReceive2);


    //불려진 사람 삭제
	$deleteWait = mysqli_prepare($mysqli, "DELETE from WaitingPeople_tb WHERE beaconID='".$beaconID."' && waitNum = '".$waitNumber."'");

	if(!$deleteWait){
		echo "mysql 문제 ";
		die('mysql deleteWait error: '.mysqli_error($mysqli));
	}

	if(!mysqli_stmt_execute($deleteWait)){
		echo "statement 문제 ";
		die('stmt deleteWait error: '.mysqli_stmt_error($deleteWait));
	}
	mysqli_stmt_execute($deleteWait);
	mysqli_stmt_close($deleteWait);
	mysqli_stmt_close($beacon);

    echo "success";
	mysqli_close($mysqli);

?>
