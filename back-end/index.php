<?php

include('database.php');
include('function.php');

header('Access-Control-Allow-Origin: *');

/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

// API ключ
$key = 'a3c6d94268f7e15f021c3a4f3072141a';

// Получаем все валюты
$getAllCurrency = file_get_contents('http://currate.ru/api/?get=currency_list&key=' . $key, false);
$getAllCurrency = json_decode($getAllCurrency, true);

// Если мы получаем ответ от сервреа 200, то получаем данные
// и ауктолизируем БД, если нет, то просто выводим даные из БД
if($getAllCurrency['status'] == '200'){
	foreach($getAllCurrency['data'] as $i => $value){
		// Получаем данные каждой валюты
		$getDataCurrency = file_get_contents('http://currate.ru/api/?get=rates&pairs=' . $value . '&key=' . $key, false);
		$getDataCurrency = json_decode($getDataCurrency, true);

		$currency = array_keys($getDataCurrency['data'])[0];
		$course = array_values($getDataCurrency['data'])[0];

		// Обновляем данные в БД
		$updatedDataСurrency = $pdo->prepare("UPDATE `currency` SET `course` = :course WHERE `name` = :name");
		$updatedDataСurrency->execute(array('course' => $course, 'name' => $currency));
	}
}

// Получаем обновлённые данные
$getActualData = $pdo->prepare("SELECT * FROM `currency`");
$getActualData->execute();
$getActualDataResult = $getActualData->fetchAll(PDO::FETCH_ASSOC);

// Формируем JSON ответ
foreach($getActualDataResult as $a => $value){
	$getActualDataArray[$a] = [
		'name' => $value['name'],
		'course' => $value['course']
	];
}

echo json_encode($getActualDataArray);
