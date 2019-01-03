$('form').submit( e => {
    e.preventDefault();
    const el = $(e.target);

    // 取表單資料
    const username = el.find('input[name="username"]').val();
    const password = el.find('input[name="password"]').val();

    // 驗證必填
    if (username === ''|| password === ''){       
        let notice = '帳號密碼不可以空白';
        el.find('.notice').text(notice);
    } else {
        // ajax
        $.ajax({
            type:'POST',
            url:'../controllers/login_check-user.php',
            data:{
                username : username,
                password : password
            },
            success: resp => {
                const res = JSON.parse(resp);
                console.log(res);
                // 登入成功，轉址到留言板
                if (res.result === 'success') {
                    window.location.href = '../views/comment.php';
                // 登入失敗，顯示錯誤訊息
                } else if (res.result === 'error') {
                    let notice = res.message;
                    el.find('.notice').text(notice);
                }
            }
        })
    }
})

// 重新輸入時，清空原來的錯誤訊息
$('form').find('input').on('focus', e => {
    $('.notice').text('');
})