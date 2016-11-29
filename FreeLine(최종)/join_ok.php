<?php
	require_once("./configDatabase.php");

        $name=mysqli_real_escape_string($mysqli, $_POST['name']);
        $id = mysqli_real_escape_string($mysqli, $_POST['id']);
        $password = mysqli_real_escape_string($mysqli, $_POST['pwd']);
        $password2 = mysqli_real_escape_string($mysqli, $_POST['rePwd']);
        $storeName = mysqli_real_escape_string($mysqli, $_POST['storeName']);
        $storeTel = mysqli_real_escape_string($mysqli, $_POST['storeTel']);
        $storeCtg = mysqli_real_escape_string($mysqli, $_POST['storeCategory']);
        $storeIntro = mysqli_real_escape_string($mysqli, $_POST['storeIntro']);
        $storePostCode = mysqli_escape_string($mysqli, $_POST['storePostCode']);
        $storeAddr = mysqli_real_escape_string($mysqli, $_POST['storeAddr']);
        $storeAddrDetail = mysqli_real_escape_string($mysqli, $_POST['storeAddrDetail']);
        $beaconID = mysqli_real_escape_string($mysqli, $_POST['beaconID']);
    
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
            $newfile01= $id.".".$file01_type; //파일명 생성;

        $dir="./images/";  //업로드할 디렉터리 지정
        move_uploaded_file($_FILES['file01']['tmp_name'],$dir.$newfile01); //파일업로드;
        chmod($dir.$newfile01,0777);
    }

	//BeaconInfo_tb에정보  삽입
	$saveBeacon = mysqli_prepare($mysqli, "INSERT INTO BeaconInfo_tb(beaconID, storeName, storeTel, storeIntroduction, storePostCode, storeLocation, storeLocationDetail, storeImage, storeCategory) values('$beaconID', '$storeName', '$storeTel', '$storeIntro', '$storePostCode', '$storeAddr', '$storeAddrDetail','$newfile01','$storeCtg')"); //카테고리 추가할것

	if(!$saveBeacon){
		echo "실패";
		die('mysql saveBeacon error:'.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($saveBeacon)){
		echo "실패";
		die('stmt saveBeacon error: '.mysqli_stmt_error($sql));
	}
	
	mysqli_stmt_close($saveBeacon);
	
	//Admin_tb에 정보  삽입
        $sql = mysqli_prepare($mysqli, "INSERT INTO Admin_tb(adminID, adminPW, adminName, beaconID) values('$id','$password','$name','$beaconID')");
   
	if(!$sql){
		echo "실패";
		die('mysql sql error : '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($sql)){
		echo "실패";
		die('stmt sql error: '.mysqli_stmt_error($sql));
	}

        mysqli_stmt_close($sql);
        mysqli_close($mysqli);

?>
<script>
	location.href="main.html";
</script>
