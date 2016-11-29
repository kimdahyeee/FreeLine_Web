<?php session_start(); ?>
<?php

	require_once("./configDatabase.php");

	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	$pass = mysqli_real_escape_string($mysqli, $_POST['password']);
	
	$sql = mysqli_prepare($mysqli, "SELECT adminID, adminPW, beaconID FROM Admin_tb where adminID= '".$id."' and adminPW= '".$pass."'");

	if(!$sql){
		echo "mysql 잘못됐다";
		die('mysqli sql error : '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($sql)){
		echo "stmt 잘못";
		die('stmt sql execute err: '.mysqli_stmt_error($sql));
	}

	mysqli_stmt_store_result($sql);
	mysqli_stmt_bind_result($sql, $myID, $myPwd, $beaconID);

	$id2="";
	$pwd2="";
	$beaconID2 ="";
	while(mysqli_stmt_fetch($sql)){
		$id2 = $myID;
		$pwd2 = $myPwd;
		$beaconID2=$beaconID;
	}

	if($id2==$id && $pwd2==$pass){
		echo "<script> location.href='main.html' </script>";
		$_SESSION["adminID"] = $id;
		$_SESSION["adminPW"] = $pass;
		$beaconActive = mysqli_prepare($mysqli, "Update BeaconInfo_tb set beaconActiveCode='1' where beaconID= '".$beaconID2."'");
		
		if(!$beaconActive){
			echo "mysql잘못 ";
			die('mysqli beaconActive error: '.mysqli_error($mysqli));
		}
		if(!mysqli_stmt_execute($beaconActive)){
			echo "stmt 잘못 ";
			die('stmt beaconActive err: '.mysqli_stmt_error($sql));
		}

		mysqli_stmt_execute($beaconActive);
		mysqli_stmt_close($beaconActive);
        
        echo "success";
        
        ?>
            <script>
                location.href="./adminPage/adminMain.html";
            </script>
        <?php
		
	}else{
		echo "로그인에 실패하셨습니다.";
	}
	
	mysqli_stmt_close($sql);
	mysqli_close($mysqli);

?>



