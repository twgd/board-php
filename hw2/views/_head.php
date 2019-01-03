<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/main.css">

<?php 
switch ($_SERVER['PHP_SELF']) {
    case '/twgd/week8/hw2/views/comment.php': ?>
        <link rel="stylesheet" type="text/css" href="../css/comment.css">
        <title>twgd week8 會員制留言板</title>
<?php
        break;
    case '/twgd/week8/hw2/views/login.php': ?>
    <link rel="stylesheet" type="text/css" href="../css/login-and-signup.css" />    
    <title>twgd week8 登入會員</title>
<?php    
        break;
    case '/twgd/week8/hw2/views/signup.php': ?>
    <link rel="stylesheet" type="text/css" href="../css/login-and-signup.css" />    
    <title>twgd week8 註冊會員</title>
<?php 
        break;
    }?>

</head>