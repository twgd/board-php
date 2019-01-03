<?php 
	session_start();
	// 如果登入，顯示留言表單
	if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
		$arr = array('status' => 'login');
		echo json_encode($arr);

		// 產生一組 csrftoken
		$csrftoken = md5(time().rand());
		setcookie("csrftoken", $csrftoken, 0, "/");
		// 取 Session 的值
		$user_id = $_SESSION["user_id"];
		$username = $_SESSION["username"];
		$nickname = $_SESSION["nickname"];

	} else {
		$arr = array('status' => 'logout');
		echo json_encode($arr);
	} 

	// connect
	require("../../connect.php"); 

	// get data
	$sql = "SELECT twgd_comments.com_id AS com_id,twgd_comments.content AS content, twgd_comments.parent_id AS parent_id, twgd_comments.created_at AS created_at,twgd_comments.updated_at AS updated_at, twgd_users.user_id AS user_id, twgd_users.username AS username, twgd_users.nickname AS nickname 
		FROM `twgd_users` 
		INNER JOIN `twgd_comments` 
		ON twgd_users.user_id = twgd_comments.user_id 
		ORDER BY created_at DESC"; 
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	$arr_all = array();
	$arr_main = array();
	while($row = $result->fetch_assoc()){
		array_push($arr_all, $row);
		$parent_id = $row["parent_id"];	
		if($parent_id === 0){			
			array_push($arr_main, $row);
		}
	}
	var_dump($arr_all);

	// set pages
	$total = count($arr_main);
	$number = 10;
	$pages = ceil($total/$number);

	if (!isset($_GET["page"])){ 
		$page = 1; 
	} else {
		$page = $_GET["page"]; 
	}
	$begin = ($page-1) * $number + 1;
	
	// output data
	for($i=$begin; $i<=$page*$number; $i++){
		//echo json_encode($arr_main[$i]);

	}

	
	
	// close
	$stmt->close();
	$conn->close();
	
?>