$('form').submit( e => {
    e.preventDefault();
    const el = $(e.target);
    
    // 取表單資料
    const username = el.find('input[name="username"]').val();
    const password = el.find('input[name="password"]').val();
    const password2 = el.find('input[name="password2"]').val();
    const nickname = el.find('input[name="nickname"]').val();
    
    // 驗證必填
    if (username === ''|| password === ''|| password2 === ''|| nickname === ''){ 
        let notice = '請填寫完整再送出';
        showNotice(el, notice);
    } else {
        // ajax 
        $.ajax({
            type:'POST',
            url:'../controllers/signup_add-user.php',
            data:{
                username : username,
                password : password,
                password2 : password2,
                nickname : nickname
            },
            success: resp => {
                res = JSON.parse(resp);
                // 註冊成功：顯示成功訊息 & 自動導向留言板
                if (res.result === 'success'){
                    el.find('.notice').text(''); // 避免原來的錯誤訊息殘留          
                    el.find('.success').show().text(res.message);
                    setTimeout(() => {
                        window.location.href = '../views/comment.php';
                    }, 1000);                                
                // 顯示其他失敗訊息
                } else if (res.result === 'error') {         
                    let notice = res.message;
                    showNotice(el, notice);
                }
            }
        })
    }
})

// 重新輸入時，清空原來的錯誤訊息
$('form').find('input').on('focus', e => {
    $('.notice').text('');
})

// 顯示 error 訊息
const showNotice = (element, message) => {
    $(element).find('.notice').text(message);
}