<?php
	/*
	登出頁
	*/
	session_start();
	
	//清除 Session
	session_unset();
	session_destroy();
		
	//清空 csrftoken Cookie
	setcookie("csrftoken", "", time()-1, "/");
	
	//轉回留言板
	$url = "./index.php";
	echo "<script>window.location.href='$url';</script>";
?>