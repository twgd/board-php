<?php
/*
檔案功能：登入操作
*/
	session_start();

	//判斷變數是否存在
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		
		// connect
		require('../../connect.php'); 
		
		$username = validation($_POST["username"]);
		$password = validation($_POST["password"]);

		// check username & password
		$sql = "SELECT * FROM `twgd_users` WHERE `username` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
	
		// check username
		if (!$result->num_rows) {
			$arr = array(
				'result' => 'error',
				'message' => '你還沒註冊帳號'
			);
			echo json_encode($arr);
			return;
		}

		// check password
		$row = $result->fetch_assoc();
		$hash = $row["password"];
		
		if (!password_verify($password ,$hash)) {
			$arr = array(
				'result' => 'error',
				'message' => '密碼打錯囉'
			);
			echo json_encode($arr);
			return;
		}

		// set Session
		$_SESSION["user_id"] = $row["user_id"];
		$_SESSION["username"] = $row["username"];
		$_SESSION["nickname"] = $row["nickname"];

		$arr = array('result' => 'success');
		echo json_encode($arr);

		// close
		$stmt->close();
		$conn->close();
	}
	
	

	// 驗證前後空格、反斜線、html 標籤
	function validation($value) {
		$value = trim($value);
		$value = stripcslashes($value);
		$value = htmlspecialchars($value);
		return $value;
	}


?>
