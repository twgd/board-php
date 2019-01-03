// ajax 新增留言
$('.container').on('submit', 'form[name="comment_add"]', e =>{
	
	e.preventDefault();
	// 取表單資料
	const el = $(e.target);
	const userId = el.parent().find('input[name="user_id"]').val();
	const content = el.parent().find('textarea[name="content"]').val();
	const parentId = el.parent().find('input[name="parent_id"]').val();
	const csrftoken = el.parent().find('input[name="csrftoken"]').val();
	
	// ajax
	$.ajax({
		type: 'POST',
		url: '../controllers/comment_add.php',
		data: {
			user_id : userId,
			content : content,
			parent_id : parentId,
			csrftoken : csrftoken
		},
		success: resp => {
			const res = JSON.parse(resp);
			if (res.parent_id === 0) {							
				// 顯示第一層 Comment
				showNewComment(el, res.user_id, res.username, res.com_id, res.created_at, res.content);
			} else {
				// 顯示第二層 Reply
				showNewReply(el, res.username, res.com_id, res.created_at, res.content);
				// 原 PO 回覆要顯示不同背景
				const originalUsername = el.parent().parent().find('.comment__container').find('.username').text();
				if (originalUsername === '@'+ res.username){
					el.parent().prev().addClass('self');
				}
			}
		},
		error: () => {
			//console.log('failed');
		}
	});
	// 清空留言區
	el.parent().find('textarea[name="content"]').val('');
})

// 顯示新第一層 Comment 的 function
const showNewComment = (element, user_id, username, com_id, created_at, content) => {
	const newCommentHtml = `
		<div class="board__container">
			<div class="comment__container">
				<div class="status">
					<div>
						<div class="nickname">${nickname}</div>
						<div class="username">@${username}</div>
					</div>
					<div class="time__container">
						<div class="time">${created_at}</div>
					</div>
				</div>				
				<div class="content">${content}</div>
				<div class="edit-container">
					<!--編輯按鈕-->
					<div class="edit btn__reply">編輯</div>
					<div class="hidden">${com_id}</div>
					<!--刪除按鈕-->
					<form action="../controllers/comment_delete.php" method="POST">
						<input type="hidden" name="com_id" value="${com_id}"/>
						<input type="hidden" name="csrftoken" value="${csrftoken}"/>
						<input class="delete btn__reply" type="submit" value="刪除"/>
					</form>
				</div>
			</div>
			<div class="reply">
				<div class="expand">
					點我發表回覆
				</div>
				<div class="hidden user_id">${user_id}</div>
				<div class="hidden com_id">${com_id}</div>	    
			</div>
		</div>`;
	element.parent().after(newCommentHtml);
}

// 顯示新第二層 Reply 的 function
const showNewReply = (element, username, com_id, created_at, content) => {
	const newReplyHtml = `
		<div class="reply">
			<div class="status">
				<div>
					<div class="nickname nickname__reply">${nickname}</div>
					<div class="username username__reply">@ ${username}</div>
				</div>
				<div class="time__container">
					<div class="time">${created_at}</div>				
				</div>
			</div>
			<div class="content">${content}</div>	
			<div class="edit-container">
				<!--編輯按鈕-->
				<div class="edit btn__reply">編輯</div>
				<div class="hidden">${com_id}</div>
				<!--刪除按鈕-->
				<form action="../controllers/comment_delete.php" method="POST">
					<input type="hidden" name="com_id" value="${com_id}"/>
					<input type="hidden" name="csrftoken" value="${csrftoken}"/>
					<input class="delete btn__reply" type="submit" value="刪除"/>
				</form>
			</div>
		</div>`;
	element.parent().before(newReplyHtml);
}


// ajax 編輯留言
$('.container').on('submit', 'form[name="comment_edit"]', e => {
	e.preventDefault();
	// 取表單資料
	const comId = $(e.target).parent().find('input[name="com_id"]').val();
	const content = $(e.target).parent().find('textarea[name="content"]').val();
	const csrftoken = $(e.target).parent().find('input[name="csrftoken"]').val();

	$.ajax({
		type:'POST',
		url:'../controllers/comment_edit.php',
		data:{
			com_id : comId,
			content: content,
			csrftoken : csrftoken
		},
		success: resp => {
			const res = JSON.parse(resp);
			// 更新留言顯示的內容
			const contentArea = $(e.target).parent().find('.content');
			contentArea.show().text(res.content);
			// 更新顯示時間
			const timeEdit = `
				<div class="time">${res.created_at}</div>
				<div class="time time-edit">已編輯 ${res.updated_at}</div>`;
			$(e.target).parent().find('.time__container').html(timeEdit);
			// 顯示編輯刪除按鈕
			$(e.target).parent().find('.edit-container').show();
			// 收回編輯表單
			$(e.target).remove();
			
		}
	})
})




// ajax 刪除留言
$('.container').on('click', '.delete', e => {
	e.preventDefault();

	const comId = $(e.target).parent().find('input[name="com_id"]').val();
	const csrftoken = $(e.target).parent().find('input[name="csrftoken"]').val();
	
	$.ajax({
		type:'POST',
		url:'../controllers/comment_delete.php',
		data:{
			com_id : comId,
			csrftoken : csrftoken
		},
		success: resp => {
			const res = JSON.parse(resp);
			if(res.result === 'success'){
				const el = $(e.target).parent().parent().parent();
				if(el.hasClass('comment__container')){
					// 刪除第一層留言
					el.parent().remove();
				} else if (el.hasClass('reply')){
					// 刪除第二層留言
					el.remove();
				}
			}
		}
	})
})


// 展開回覆留言表單
$('.container').on('click', '.expand', e => {
	showReplyForm(e);
})

// 取消回覆
$('.container').on('click', '.cancel-reply', e => {
	$(e.target).parent().parent().find('.expand').show();
	$(e.target).parent().remove();
})

// 展開編輯表單
$('.container').on('click', '.edit', e => {
	showEditForm(e);
})

// 取消編輯
$('.container').on('click', '.cancel-edit', e => {
	$(e.target).parent().parent().find('.content').show();
	$(e.target).parent().parent().find('.edit-container').show();
	$(e.target).parent().remove();
})


// 展開回覆表單 function
const showReplyForm = t => {
	$(t.target).hide();

	const userId = $(t.target).parent().find('.user_id').text();
	const parentId = $(t.target).parent().find('.com_id').text();
	
	const reply_form = `
		<form name="comment_add" action="../controllers/comment_add.php" method="POST">
			<div class="status">
				<div class="nickname nickname__reply">${nickname}</div>
			</div>
			<input class="input__user_id" type="hidden" name="user_id" value="${userId}">
			<div>
				<textarea class="textarea__content" name="content" rows="3" placeholder="留下回覆"></textarea>
			</div>
			<input type="hidden" name="parent_id" value="${parentId}">
			<input type="hidden" name="csrftoken" value="${csrftoken}"/>
			<div class="cancel cancel-reply btn__reply">取消</div>
			<input class="btn btn-info" type="submit" name="submit" value="回覆">
		</form>
	`
	$(t.target).parent().append(reply_form);
}

// 展開編輯表單 function
const showEditForm = t => {
	$(t.target).parent().hide();
	$(t.target).parent().parent().find('.content').hide();

	const comId = $(t.target).parent().find('.com_id').text();
	const content = $(t.target).parent().parent().find('.content').text();

	const comment_form_edit = `
		<form name="comment_edit" action="../controllers/comment_edit.php" method="POST">			
			<div>
				<textarea class="textarea__content" name="content" rows="5">${content}</textarea>
			</div>
			<input type="hidden" name="com_id" value="${comId}">
			<input type="hidden" name="csrftoken" value="${csrftoken}"/>
			<div class="cancel cancel-edit btn__reply">取消</div>
			<input class="btn btn-info" type="submit" name="submit" value="更新">
		</form>
	`
	$(t.target).parent().parent().append(comment_form_edit);
}





