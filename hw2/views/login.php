<?php 
	/*
	檔案：登入頁面
	*/
	session_start();
	if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
		header("location:./comment.php");
	}
?>

<!DOCTYPE html>
<html>
<?php require('_head.php'); ?>
	<body>
		<div class="container">
			<div class="title">登入會員</div>
			
			<div class="form">
				<form action="./login.php" method="POST">
					<div class="input">
						<div class="input__que">帳號</div>
						<input class="input__ans" type="text" name="username" placeholder="你的帳號">
					</div>
					<div class="input">
						<div class="input__que">密碼</div>
						<input class="input__ans" type="password" name="password" placeholder="你的密碼">
					</div>
					<div class="input">
						<input class="input__btn" type="submit" name="submit" value="登入">
					</div>
					<div class="notice"></div>
				</form>
			</div>

			<div class="redirection">
				還不是會員嗎？
				<a href="./signup.php">註冊會員</a>
			</div>
		</div>

<?php require_once("./_footer.php") ?>
	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script type="text/javascript" src="../js/login.js"></script>
	</body>
</html>







