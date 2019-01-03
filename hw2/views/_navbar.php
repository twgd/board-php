<nav class="navbar navbar-dark bg-dark fixed-top">
	<a class="navbar-brand mb-0 h1" href="./comment.php">留言板</a>
  	
<?php 	

	if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {  ?>

		<a class="btn btn-outline-info my-2 my-sm-0" href="./logout.php">登出</a>

<?php	
	} else { ?>

		<a class="btn btn-outline-info my-2 my-sm-0" href="./login.php">登入/註冊</a>
		
<?php 	} ?>

</nav>