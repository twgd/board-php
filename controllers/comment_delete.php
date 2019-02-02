<?php 
	session_start();
/*
檔案功能：刪除留言
*/

	if (isset($_SESSION["user_id"]) && isset($_POST["com_id"]) && isset($_POST["csrftoken"]) && isset($_COOKIE["csrftoken"])){
		
		require("../connect.php");

		$user_id = $_SESSION["user_id"];
		$com_id = $_POST["com_id"];
		$csrftoken = $_POST["csrftoken"];
		
		// csrf token
		if (!$csrftoken === $_COOKIE["csrftoken"]) {
			$arr = array(
				'result' => 'error',
				'message' => 'csrf 怪怪的'
			);
			echo json_encode($arr);	
			return;
		}

		// delete data
		$sql = "UPDATE `comments` SET `is_deleted` = 1 WHERE `com_id` = ? AND `user_id` = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ii", $com_id, $user_id);

		if (!$stmt->execute()){
			$arr = array(
				'result' => 'error',
				'message' => '資料庫怪怪的'
			);
			echo json_encode($arr);	
			return;
		}
		
		// 影響資料 0 列
		if($stmt->affected_rows === 0){
			$arr = array(
				'result' => 'error',
				'message' => '無法刪除，有地方怪怪的'
			);
			echo json_encode($arr);
			return;
		}

		$arr = array('result' => 'success');
		echo json_encode($arr);

		// close
		$stmt->close();
		$conn->close();
	}

?>