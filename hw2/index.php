<?php 
    session_start();
    if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {

        // 產生一組 csrftoken
        $csrftoken = md5(time().rand());
        setcookie("csrftoken", $csrftoken, 0, "/");

        // 取 Session 的值
        $user_id = $_SESSION["user_id"];
        $username = $_SESSION["username"];
        $nickname = $_SESSION["nickname"];
    }
?>

<!DOCTYPE html>
<html>
<?php require('_head.php'); ?>
	
	<body>

	</body>
</html>
