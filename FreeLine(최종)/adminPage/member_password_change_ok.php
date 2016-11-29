<?php 
	session_start();

	require_once("../configDatabase.php");	

	$chPassword = mysqli_real_escape_string($mysqli, $_POST['changePass']);

	$sql = mysqli_prepare($mysqli, "UPDATE Admin_tb SET adminPW ='". $chPassword."' WHERE adminID = '".$_SESSION['adminID']."'");
	
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
		echo("<script>alert('회원정보 수정 되었습니다. 다시 로그인해주세요.'); window.open('','_parent').close(); window.opener.location.href='../main.html';</script>");
		session_destroy();
	}

	mysqli_stmt_close($sql);
    mysqli_close($mysqli);

?>
