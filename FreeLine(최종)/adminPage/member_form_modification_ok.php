<?php session_start(); ?>
<?php

	require_once("../configDatabase.php");	

	$storeName = mysqli_real_escape_string($mysqli, $_POST['storeName']);
	$storeTel = mysqli_real_escape_string($mysqli, $_POST['storeTel']);
	$storeIntro = mysqli_real_escape_string($mysqli, $_POST['storeIntro']);
    $storeCtg = mysqli_real_escape_string($mysqli, $_POST['storeCategory']);
	$storePostCode = mysqli_real_escape_string($mysqli, $_POST['storePostCode']);
	$storeAddr = mysqli_real_escape_string($mysqli, $_POST['storeAddr']);
	$storeAddrDetail = mysqli_real_escape_string($mysqli, $_POST['storeAddrDetail']);
	$beaconID = mysqli_real_escape_string($mysqli, $_POST['beaconID']);
	$checkFile = mysqli_real_escape_string($mysqli, $_POST['check_file']);
	$image_name = mysqli_real_escape_string($mysqli, $_POST['image_name']);

	if($checkFile == '1'){
	    	if($_FILES[file01][name]){
		        $size= $_FILES['file01']['size'];
		            if($size > 2097152) Error('파일용량:2MB 제한합니다.'); 
		        
		        $file01_name=strtolower($_FILES['file01']['name']); //파일명과 확장자를 소문자로 변경
		        $file01_split= explode(".",$file01_name);   // 파일명과 확장자를 분리

		        $extexplode= $file01_split[count($file01_split)-2.3]; //파일명만 가져오기
		        $file01_type= $file01_split[count($file01_split)-1]; // 확장자만 가져오기

		        $img_ext= array('jpg','jpeg','gif','png'); //이미지 확장자 종류를 배열에 넣는다.
		        if(array_search($file01_type, $img_ext) === false)Error('이미지 파일이 아닙니다.');

		            $tates= date("mdhis", time());  //날짜 (월,일,시간,분,초)
		            $newfile01= $_SESSION['adminID'].".".$file01_type; //파일명 생성;

		        $dir="../../images/";  //업로드할 디렉터리 지정
		        move_uploaded_file($_FILES['file01']['tmp_name'],$dir.$newfile01); //파일업로드;
		        chmod($dir.$newfile01,0777);/*
			}*/
	    }

    	$updateFile = mysqli_prepare($mysqli, "UPDATE BeaconInfo_tb SET storeImage= '".$newfile01."' WHERE beaconID IN (SELECT beaconID FROM Admin_tb WHERE adminID='".$_SESSION['adminID']."')");

    	if(!$updateFile){
			echo "실패";
			die('mysql updateFile error:'.mysqli_error($mysqli));
		}
		if(!mysqli_stmt_execute($updateFile)){
			echo "실패";
			die('stmt updateFile error: '.mysqli_stmt_error($sql));
		}
	
		mysqli_stmt_close($updateFile);
	}
	
	$sql = mysqli_prepare($mysqli, "UPDATE BeaconInfo_tb SET storeName ='". $storeName."', beaconID = '".$beaconID."', storeTel = '".$storeTel."', storeIntroduction = '".$storeIntro."', storeCategory = '".$storeCtg."', storePostCode ='".$storePostCode."', storeLocation='".$storeAddr."', storeLocationDetail='".$storeAddrDetail."' WHERE beaconID IN (SELECT beaconID FROM Admin_tb WHERE adminID='".$_SESSION['adminID']."')");
	
	if(!$sql){
		echo "mysql 문제";
		die('mysql sql error: '.mysqli_error($mysqli));
		echo("<script>alert('회원정보 수정에 실패했습니다.'); history.back();</script>");

	}
	else if(!mysqli_stmt_execute($sql)){
		echo "statement문제";
		die('stmt sql error: '.mysqli_stmt_error($sql));
		echo("<script>alert('회원정보 수정에 실패했습니다.'); history.back();</script>");
	}

	else {
		echo("<script>alert('회원정보 수정 되었습니다.'); location.href='./member_form.html';</script>");
	}

	mysqli_stmt_close($sql);

    $sql_beacon = mysqli_prepare($mysqli, "UPDATE Admin_tb SET beaconID ='". $beaconID."' WHERE adminID='".$_SESSION['adminID']."')");
    mysqli_stmt_execute($sql_beacon);
    mysqli_stmt_close($sql_beacon);
    mysqli_close($mysqli);

?>