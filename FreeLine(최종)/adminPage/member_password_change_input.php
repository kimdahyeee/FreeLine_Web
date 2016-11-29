<!DOCTYPE HTML>
<?php session_start();?>
<?php require_once("../configDatabase.php"); ?>
<html>
<head>
    <title>FreeLine 관리자페이지</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        .member{
            height: 44px;
            font-size: 16px;
            width: 100%;
            margin-bottom: 10px;
            -webkit-appearance: none;
            background: #fff;
            border: 1px solid #d9d9d9;
            border-top: 1px solid #c0c0c0;
            /* border-radius: 2px; */
            padding: 0 8px;
            box-sizing: border-box;
            margin-top: 20px;
        }
        .btn.btn-lg {
            padding: 10px 25px;
        }
        .btn.btn-primary {
            border: 2px solid #ffffff;
            color: #ffffff;
        }
        
        .pass_change{
            text-align: center;
        }
    </style>
    
</head>
<body>
<?php
	if(!isset($_SESSION['adminID']) || !isset($_SESSION['adminPW'])){
?>
	<script>
		alert('로그인 후 시도해주세요');
		location.href = 'login.php';
	</script>

<?php
	}else{
		?>
    <div class="pass_change">
        <form method="post" action = "./member_password_change_ok.php">
			<input type="text" name="changePass" class="member" placeholder="바꿀 비밀번호를 입력해주세요." required="required" />
			<input id="confirmBtn" class="btn btn-primary btn-lg" type="submit" value="확인">
		</form>
    </div>
<?php }?>
</body>
<script> 
$( document ).ready(function() { 
	$('#confirmBtn').trigger('click'); 
}); 
</script> 
</html>