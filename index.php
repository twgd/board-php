<?php 
	session_start();
?>

<!DOCTYPE html>
<html>
<?php 
	$title="留言板";
	$css_link="./css/comment.css";
	require('./views/_head.php');
?>
	
	<body>
<?php 	require("./views/_navbar.php"); ?>

		<div class="container col-md-8">
			<div class="board__container">

	<?php 	// 如果登入，顯示留言表單
			if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {

				// 產生一組 csrftoken
				$csrftoken = md5(time().rand());
				setcookie("csrftoken", $csrftoken, 0, "/");
				// 取 Session 的值
				$username = $_SESSION["username"];
				$nickname = $_SESSION["nickname"];

				//顯示留言表單
				require("./views/_comment_form.php"); 

			} else { ?>
				<div class="redirection">
					想要留言？
					<a class="btn btn-info" href="./login.php">登入留言</a>
				</div>
	<?php 	} ?>
			</div>


<?php
	//連接資料庫
	require("./connect.php"); 

	// 讀取 comments 第一層
	$sql = "SELECT 
				* 
			FROM 
				`comments` 
			INNER JOIN 
				`users` 
			ON
				comments.user_id = users.user_id 
			WHERE 
				`parent_id`=0 AND `is_deleted`=0
			ORDER BY 
				created_at DESC"; 
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();

	// 設定頁數
	$total = $result->num_rows;
	$number = 10;
	$pages = ceil($total/$number);

	if (!isset($_GET["page"])){ 
	        $page = 1; 
	    } else {
	        $page = $_GET["page"]; 
	    }
	    $start = ($page-1) * $number; 
	    $stmt = $conn->prepare($sql . " LIMIT ? , ? ");
	    $stmt->bind_param("ii", $start, $number);
	    $stmt->execute();
		$result = $stmt->get_result();

	//顯示 comments 第一層
	for($i=1; $i<= $result->num_rows; $i++) {
		$row = $result->fetch_assoc(); 
		$parent_id = $row["com_id"]; ?>

			<div class="board__container">
				<div class="comment__container">
					<div class="status">
						<div>
							<div class="nickname"> <?= $row["nickname"] ?></div>
							<div class="username">@<?= $row["username"] ?></div>
						</div>
						<div class="time__container">
							<div class="time"><?= $row["created_at"]?></div>
				<?php 	if ($row["updated_at"]) { ?>
							<div class="time time-edit">已編輯 <?= $row["updated_at"]?></div> 
				<?php	} ?>	
						</div>
					</div>				
					<div class="content"> <?= $row["content"]?></div>

		<?php 	//會員可以 編輯 & 刪除留言
				if (isset($_SESSION["user_id"]) && $row["user_id"] === $_SESSION["user_id"]){ ?>		
					<div class="edit-container">
						<!--編輯按鈕-->
						<div class="edit btn__reply">編輯</div>
						<div class="hidden com_id"><?= $row["com_id"] ?></div>
						<!--刪除按鈕-->
						<form name="comment_delete" action="../controllers/comment_delete.php" method="POST">
							<input type="hidden" name="com_id" value="<?= $row['com_id'] ?>"/>
							<input type="hidden" name="csrftoken" value="<?= $csrftoken ?>"/>
							<input class="delete btn__reply" type="submit" value="刪除"/>
						</form>
					</div>
					
		<?php 	} ?>
				</div>

<?php	// 讀取 comments 第二層 (回覆)
		$sql_rpy = "SELECT 
						* 
					FROM 
						`comments` 
					INNER JOIN 
						`users` 
					ON 
						comments.user_id = users.user_id 
					WHERE 
						`parent_id` = ? AND `is_deleted` =0
					ORDER BY `created_at`"; 
		$stmt_rpy = $conn->prepare($sql_rpy);
		$stmt_rpy->bind_param("i", $parent_id);
		$stmt_rpy->execute();
		$result_rpy = $stmt_rpy->get_result();

		// 顯示 comments 第二層 (回覆)
		for($j=1; $j<= $result_rpy->num_rows; $j++) {
			$row_rpy = $result_rpy->fetch_assoc();	

			// 如果是會員自己的回覆，背景顯示不同顏色
			if ($row["user_id"] === $row_rpy["user_id"]){ ?>
				<div class="reply self">
		<?php	} else { ?>
				<div class="reply">
	<?php	} ?>	<div class="status">
						<div>
							<div class="nickname nickname__reply"><?= $row_rpy["nickname"] ?></div>
							<div class="username username__reply">@<?= $row_rpy["username"] ?></div>
						</div>
						<div class="time__container">
							<div class="time"><?= $row_rpy["created_at"] ?></div>
				<?php 	if ($row_rpy["updated_at"]) { ?>
							<div class="time time-edit">已編輯 <?= $row_rpy["updated_at"]?></div> 
				<?php	} ?>						
						</div>					
					</div>
					<div class="content"><?= $row_rpy["content"] ?></div>

		<?php 	//編輯 & 刪除留言
				if (isset($_SESSION["user_id"]) && $row_rpy["user_id"] === $_SESSION["user_id"]){ ?>		
					<div class="edit-container">
						<!--編輯按鈕-->
						<div class="edit btn__reply">編輯</div>
						<div class="hidden com_id"><?= $row_rpy["com_id"] ?></div>
						<!--刪除按鈕-->
						<form name="comment_delete" action="../controllers/comment_delete.php" method="POST">							
							<input type="hidden" name="com_id" value="<?= $row_rpy['com_id'] ?>"/>
							<input type="hidden" name="csrftoken" value="<?= $csrftoken ?>"/>
							<input class="delete btn__reply" type="submit" value="刪除"/>
						</form>
					</div>
		<?php 	} ?>
				</div>

<?php	} ?>

				<div class="reply">

	<?php 	// 如果登入，顯示回覆表單
			if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) { ?>

					<div class="expand">
						點我發表回覆
					</div>
					<div class="hidden com_id"><?= $row["com_id"] ?></div>
<?php
			} else { ?>
					<div class="redirection">
						想要回覆？
						<a class="btn btn-info" href="login.php">登入回覆</a>
					</div>
	<?php 	} ?>
				    
				</div>

			</div>
<?php
	} ?>
			<!--頁數-->
			<nav aria-label="Page navigation">
				<ul class="pagination justify-content-center">
		<?php	if (isset($pages)) { ?>
					<li class="page-item"><a class="page-link text-info" href="./?page=1"> << </a></li>
		<?php		for($i=1; $i <= $pages ; $i++){ 
						if($i >= $page-3 && $i  <= $page+3){?>
					<li class="page-item"><a class="page-link text-info" href="./?page=<?= $i ?>"><?= $i ?></a></li>
		<?php 			}
					} ?>
					<li class="page-item"><a class="page-link text-info" href="./?page=<?= $pages ?>"> >> </a></li>
		<?php	} ?>
				</ul>
			</nav>		
		</div>

<?php
	//引入頁尾
	require('./views/_footer.php'); 
	$result->close();
	$result_rpy->close();
	$conn->close();
?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script type="text/javascript">
			let csrftoken = "<?php if (isset($csrftoken)) echo $csrftoken ?>";
			let nickname = "<?php if (isset($nickname)) echo $nickname ?>";
		</script>
		<script type="text/javascript" src="./js/comment.js"></script>

	</body>
</html>
