<?php

use Curl\Curl;
class Worker {

    private $moySclad;
    
    public function __construct() {
	$this->moySclad = new MoySclad(); 
	
    }
    
    /**
     * Запрашиваем список заказов клиентов из CRM "Мой Склад"
     * Данные которые нужны для выгрузки - номер заказа, дата заказа, товары в заказе, по возможности взять ФИО клиента, телефон и имейл
     * @var DateTime $dataStart Дата начала выборки
     * @var DateTime $dataEnd Дата Окончания выборки
     */
    public function getCustomerOrder($dataStart,$dataEnd){
	$orders = $this->moySclad->getCustomerOrder();
	print_r($orders);
    }

}

