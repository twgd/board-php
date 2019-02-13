# 會員制留言板系統：使用 PHP & AJAX

- http://poketrainer.tw/board-php/

------

## 功能簡介

### 留言

- 雙層留言：主留言及子留言
- 分頁顯示留言
- 可區別子留言是否與主留言同一人 ( 樓主留言底色不同 )

### 會員系統

- 可註冊成為會員
- 會員才可以留言，並可以編輯及刪除自己的留言

-------

## 後端技術

使用 PHP 開發

### 資訊安全
- 連線資料庫 sql 使用 prepare statement 防止 SQL injection
- 使用 `htmlspecialchars()` HTML Escape 防止 XSS 攻擊
- 留言設置 csrf token 防止 CSRF 攻擊

### 會員系統
- 使用 session 判斷會員登入狀態
- password 經過 hash 處理，密碼不可存明碼


## 前端技術

- 使用 AJAX 改善體驗：新增 / 編輯 / 刪除留言，不會換頁
- 前端 Javascript 使用 jQyery 開發


## 部署

- AWS EC2 / Ubuntu 18.04 / apache 2.4 / PHP 7.2 / MySQL 5.7
- 使用 scp 上傳