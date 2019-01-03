<?php
/*
檔案功能：新增留言
*/
	if (isset($_POST['user_id']) && isset($_POST['content']) && isset($_POST['csrftoken']) && isset($_POST['parent_id']) && !empty($_POST['content'])) {
		// connect
		require('../../connect.php'); 

		$user_id = $_POST['user_id'];
		$content = htmlspecialchars($_POST['content']);
		$parent_id = $_POST['parent_id'];
		$csrftoken = $_POST['csrftoken'];

		// csrftoken
		if (!$csrftoken === $_COOKIE['csrftoken']) {
			$arr = array(
				'result' => 'error',
				'message' => 'csrf 驗證怪怪的'
			);
			echo json_encode($arr);
			return;	
		}

		// insert data
		$sql = "INSERT INTO twgd_comments(user_id, content, parent_id) VALUES (?, ?, ?)"; 
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("isi",$user_id, $content, $parent_id);
		
		if (!$stmt->execute()) {
			$arr = array(
				'result' => 'error',
				'message' => '資料庫怪怪的'
			);
			echo json_encode($arr);			
			return;
		}

		// output
		$last_id = $conn->insert_id;

		$sql_last = "SELECT * FROM `twgd_comments` INNER JOIN `twgd_users` ON twgd_comments.user_id = twgd_users.user_id WHERE com_id = ? ";
		$stmt_last = $conn->prepare($sql_last);
		$stmt_last->bind_param("i", $last_id);
		
		if (!$stmt_last->execute()) {
			$arr = array(
				'result' => 'error',
				'message' => '資料庫怪怪的'
			);
			echo json_encode($arr);	
			return;
		}

		$arr = $stmt_last->get_result()->fetch_assoc();
		echo json_encode($arr);
		
		//close
		$stmt->close();
		$stmt_last->close();
		$conn->close();
	}

?>
