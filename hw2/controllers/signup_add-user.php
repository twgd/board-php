<?php 
/*
檔案功能：註冊 / 新增使用者
*/
	session_start();

	if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"]) && isset($_POST["nickname"])) { 
		
		//connect
		require('../../connect.php');

		$username = validation($_POST["username"]);
		$password = validation($_POST["password"]);
		$password2 = validation($_POST["password2"]);
		$nickname = validation($_POST["nickname"]);

		// ckeck username
		$sql = "SELECT * FROM `twgd_users` WHERE `username` = ?" ;
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows !== 0) {
			$arr = array(
				'result' => 'error',
				'message' => '帳號已經有人使用'
			);
			echo json_encode($arr);
			return;
		}

		// check password
		if ($password !== $password2) {
			$arr = array(
				'result' => 'error',
				'message' => '密碼有打錯喔'
			);
			echo json_encode($arr);
			return;			
		}

		// insert data
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$sql_hash = "INSERT INTO twgd_users(username, password, nickname) VALUES (?, ?, ?)";
		$stmt_hash = $conn->prepare($sql_hash);
		$stmt_hash->bind_param("sss",$username, $hash, $nickname);

		if (!$stmt_hash->execute()) {
			$arr = array(
				'result' => 'error', 
				'message' => '資料庫錯誤'
			);
			echo json_encode($arr);
			return;
		}

		// set session
		$last_id = $conn->insert_id;
		$_SESSION['user_id'] = $last_id;
		$_SESSION['username'] = $username;
		$_SESSION['nickname'] = $nickname;

		// success
		$arr = array(
			'result' => 'success',
			'message' => '註冊成功！即將自動為你登入'
		);
		echo json_encode($arr);

		// close
		$stmt->close();	
		$stmt_hash->close();
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

