<?php 

$user = "root";
$password = "E3C7A3EDCE9B20B111C1230A4F76F76E30C6EA493ED339D98A3C87867A3E6155";

$option = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=test_task_db;charset=utf8', $user, $password, $option);
}
catch (PDOException $e) {
    die('Error — ' . $e->getMessage());
}
