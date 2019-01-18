<?php
define('VERSION', '1.0.0.1');
/*
 * Техническое задание
 * необходимо из МойСклад выгрузить данные за определенные временные промежутки 
 * 03.08.2016-26.11.2017;
 * 03.022018 - 18.10.2018 с указанными датами включительно. 
 * И только в случае, если не возникнет дублей заказов - то взять ещё отрезок с 
 * 01.01.2019 по 10.01.2019
 * Данные которые нужны для выгрузки
 *  - номер заказа,
 *  - дата заказа,
 *  - товары в заказе,
 *  - по возможности взять ФИО клиента, телефон и имейл
 */
if (is_file('configuser.php')) {
	require_once('configuser.php');
}
if (is_file('../vendor/autoload.php')) {
	require_once('../vendor/autoload.php');
}
function library($class) {
    $prefixPath=__DIR__.'/class/';
    $relativePath=str_replace('\\', '/', $class);
    if(!file_exists($file=$prefixPath.$relativePath.'.php')){
	if(!file_exists($file=$prefixPath.strtolower($relativePath).'.php'))
	    if(!file_exists($file=$prefixPath.ucfirst($relativePath).'.php'))
		return;
    }
    require_once $file;
}

spl_autoload_register('library');
session_start();

$work = new Worker();
$dataStart = new DateTime('2016-08-03 00:00:00');
$dataEnd = new DateTime('2017-11-26 23:59:59');
$dataStart2 = new DateTime('2018-02-03 00:00:00');
$dataEnd2 = new DateTime('2018-10-18 23:59:59');
$dataStart3 = new DateTime('2019-01-01 00:00:00');
$dataEnd3 = new DateTime('2019-01-10 23:59:59');


$work->getCustomerOrder($dataStart,$dataEnd);