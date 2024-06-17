<?php
ini_set('display_errors', 1); error_reporting(E_ERROR);// Report all PHP errors
$dbhost = "localhost";  // MySQL主機位址，localhost=本機
$dbuser = "4110e225";  // MySQL帳號
$dbpassword = "!QAZ2wsx";  // MySQL密碼
$dbname = "db4110e225"; // 資料庫名稱
$conn = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname) // 連結MySQL資料庫
   or trigger_error('Error connecting to MySQL server.', E_USER_ERROR);

$today_YmdHis=date("YmdHis"); //今天日期-年月日時分秒
$today_Ymd=date("Y-m-d");     //今天日期-年月日
?>
