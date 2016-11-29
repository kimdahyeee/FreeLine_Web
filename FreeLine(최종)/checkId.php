<?php
	require_once("./configDatabase.php");

	$id = mysqli_real_escape_string($mysqli, $_POST['id']);

	$sql = mysqli_prepare($mysqli, "SELECT adminID From Admin_tb WHERE adminID ='".$id."'");

	if(!$sql){
		echo "mysql 문제";
		die('mysql sql error: '.mysqli_error($mysqli));
	}
	if(!mysqli_stmt_execute($sql)){
		echo "statement 문제";
		die('stmt sql error: '.mysqli_stmt_error($sql));
	}

	mysqli_stmt_execute($sql);
	mysqli_stmt_bind_result($sql, $check);

	$check2="";
	while(mysqli_stmt_fetch($sql)){
		$check2 = $check;
	}

    if($check2 == $id){
        echo "fail";
    }else{
        echo "success";
    }
		
	mysqli_stmt_close($sql);
	mysqli_close($mysqli);

?>