<?php
//這個檔案現在沒有用到
//回覆表單用 comment.js 的 showReplyForm 這個 function
 ?>

<form action="../controllers/comment_add.php" method="POST">
	<div class="status">
		<div class="nickname"><?= $nickname ?></div>
	</div>
	<input class="input__user_id" type="hidden" name="user_id" value="<?= $user_id ?>">
	<div>
		<textarea class="textarea__content" name="content" rows="3" placeholder="留下回覆"></textarea>
	</div>
	<input type="hidden" name="parent_id" value="<?= $row["com_id"]?>">
	<input type="hidden" name="csrftoken" value="<?= $csrftoken ?>"/>
	<input class="btn btn-info" type="submit" name="submit" value="回覆">
</form>