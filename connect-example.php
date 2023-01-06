<?php
    // git ignore connect.php , 此為範本
    $servername = "";
    $username = "";
    $password = "";
    $dbname = "";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";

    //mysqli_set_charset($conn, "utf8"); // 防止寫入資料庫中文亂碼
    //date_default_timezone_set("Asia/Taipei");  // 調整資料庫時區為台北
    $conn->query("SET NAMES 'UTF8'");
    $conn->query("SET time_zone = '+08:00'");

?>