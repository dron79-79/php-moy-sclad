<?php

use Curl\Curl;
use Nette\Database\Connection;
class Worker {

    private $moySclad;
    private $db;
    
    public function __construct() {
	$this->moySclad = new MoySclad(); 
	$dsn  = DBTIP.":host=".DBHOST.";dbname=".DBNAME;
	$this->db = new Connection($dsn, DBUSER, DBPAS);
	
    }
    
    /**
     * Запрашиваем список заказов клиентов из CRM "Мой Склад"
     * Данные которые нужны для выгрузки - номер заказа, дата заказа, товары в заказе, по возможности взять ФИО клиента, телефон и имейл
     * @var DateTime $dataStart Дата начала выборки
     * @var DateTime $dataEnd Дата Окончания выборки
     */
    public function getCustomerOrder($dataStart,$dataEnd){
	/*
	 * В цикле делаем запрос на выборку заказов
	 * результаты пишем в таблицу customerOrder все дальнейшие выборки будем делать из нашей базы данных
	 */
	$orders = $this->moySclad->getCustomerOrder();
	$this->db->query($sql);
	
	print_r($orders);
    }
    
    private function installTable() {
	$sql ="CREATE TABLE `customerOrder` (
	    `id` char(36) NOT NULL,
	    `accountId` char(36) NOT NULL,
	    `ownerId` char(36) NOT NULL,
	    `groupId` char(36) NOT NULL,
	    `version` int(11) NOT NULL,
	    `updated` datetime NOT NULL,
	    `name` varchar(36) NOT NULL,
	    `description` varchar(255) NOT NULL,
	    `externalCode` varchar(36) NOT NULL,
	    `moment` datetime NOT NULL,
	    `applicable` int(1) NOT NULL,
	    `currencyId` char(36) NOT NULL,
	    `sum` varchar(36) NOT NULL,
	    `storeId` char(36) NOT NULL,
	    `agentId` char(36) NOT NULL,
	    `organizationId` char(36) NOT NULL,
	    `organizationAccountId` char(36) NOT NULL,
	    `stateId` char(36) NOT NULL,
	    `created` datetime NOT NULL,
	    `deliveryPlannedMoment` datetime NOT NULL,
	    `vatEnabled` int(1),
	    `payedSum`  varchar(36) NOT NULL,
	    `shippedSum` varchar(36) NOT NULL,
	    `invoicedSum` varchar(36) NOT NULL,
	    `reservedSum` varchar(36) NOT NULL,
	    PRIMARY KEY (`id`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$this->db->query($sql);
	
	$sql ="CREATE TABLE `customerOrder_attributes` (
	    `customerOrderId` char(36) NOT NULL,
	    `attributeId` char(36) NOT NULL,
	    `name` varchar(36) NOT NULL,
	    `type` varchar(36) NOT NULL,
	    `valueId` char(36) NOT NULL,
	    `valueName` varchar(36) NOT NULL,
	    PRIMARY KEY (`customerOrderId`,`attributeId`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$this->db->query($sql);
	
	$sql ="CREATE TABLE `customerOrder_demands` (
	    `customerOrderId` char(36) NOT NULL,
	    `demandId` char(36) NOT NULL,
	    `type` varchar(36) NOT NULL,
	    PRIMARY KEY (`customerOrderId`,`demandId`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$this->db->query($sql);
	
	$sql ="CREATE TABLE `customerOrder_payments` (
	    `customerOrderId` char(36) NOT NULL,
	    `paymentId` char(36) NOT NULL,
	    `type` varchar(36) NOT NULL,
	    `linkedSum` varchar(36) NOT NULL,
	    PRIMARY KEY (`customerOrderId`,`paymentId`)
	    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$this->db->query($sql);
	
    }
    /**
     * Добавляем в таблицу базы данных заказ клиента
     * @param stdClass $customerOrder стандартный объект с данными заказа клиента
     * @return boolean результат
     */
    private function addCustomerOrder($customerOrder){
	//$this->db->quote($string);
	$id = $this->db->quote($customerOrder->id);
	$accountId = $this->db->quote($customerOrder->accountId);
	$ownerId = $this->getUid($customerOrder->owner->meta->href);
	$ownerId = $this->db->quote($ownerId);
	$groupId = $this->getUid($customerOrder->group->meta->href);
	$groupId = $this->db->quote($groupId);
	$version = $this->db->quote($customerOrder->version);
	$updated = $this->db->quote($customerOrder->updated);
	$name = $this->db->quote($customerOrder->name);
	$description = $this->db->quote($customerOrder->description);
	$externalCode = $this->db->quote($customerOrder->externalCode);
	$moment = $this->db->quote($customerOrder->moment);
	$applicable = $this->db->quote($customerOrder->applicable);
	$currencyId = $this->getUid($customerOrder->rate->currency->meta->href);
	$currencyId = $this->db->quote($currencyId);
	$sum = $this->db->quote($customerOrder->sum);
	$storeId = $this->getUid($customerOrder->store->meta->href);
	$storeId = $this->db->quote($storeId);
	$agentId = $this->getUid($customerOrder->agent->meta->href);
	$agentId = $this->db->quote($agentId);
	$organizationId = $this->getUid($customerOrder->organization->meta->href);
	$organizationId = $this->db->quote($organizationId);
	$organizationAccountId = $this->getUid($customerOrder->organizationAccount->meta->href);
	$organizationAccountId = $this->db->quote($organizationAccountId);
	$stateId = $this->getUid($customerOrder->state->meta->href);
	$stateId = $this->db->quote($stateId);
	$created = $this->db->quote($customerOrder->created);
	$deliveryPlannedMoment = isset($customerOrder->deliveryPlannedMoment)?$customerOrder->deliveryPlannedMoment:'';
	$deliveryPlannedMoment = $this->db->quote($deliveryPlannedMoment);
	$vatEnabled = isset($customerOrder->vatEnabled)?$customerOrder->vatEnabled:'';
	$vatEnabled = $this->db->quote($vatEnabled);
	$payedSum = isset($customerOrder->payedSum)?$customerOrder->payedSum:'';
	$payedSum = $this->db->quote($payedSum);
	$shippedSum = isset($customerOrder->shippedSum)?$customerOrder->shippedSum:'';
	$shippedSum = $this->db->quote($shippedSum);
	$invoicedSum = isset($customerOrder->invoicedSum)?$customerOrder->invoicedSum:'';
	$invoicedSum = $this->db->quote($invoicedSum);
	$reservedSum = isset($customerOrder->reservedSum)?$customerOrder->reservedSum:'';
	$reservedSum = $this->db->quote($reservedSum);
	
	$sql = "INSERT INTO customerOrder (id, accountId, ownerId, groupId, version, updated, name, description, externalCode, moment, applicable, currencyId, sum, storeId, agentId, organizationId, organizationAccountId, stateId, created, deliveryPlannedMoment, vatEnabled, payedSum, shippedSum, invoicedSum, reservedSum "
		. "VALUES ('".$id."', '".$accountId."', '".$ownerId."', '".$groupId."', '".$version."', '".$updated."', '".$name."', '".$description."', '".$externalCode."', '".$moment."', '".$applicable."', '".$currencyId."', '".$sum."', '".$storeId."', '".$agentId."', '".$organizationId."', '".$organizationAccountId."', '".$stateId."', '".$created."', '".$deliveryPlannedMoment."', '".$vatEnabled."', '".$payedSum.", '".$shippedSum."', '".$invoicedSum."', '".$reservedSum."');";
	$this->db->query($sql);
    }
    
    /**
     * Выбираем из ссылки идентификатор
     * @param string $url Ссылка на ресурс
     * @return string|boolean Возвращает идентификатор или false
     */
    private function getUid($url){
	$temp = array_reverse(explode("/", trim($url,"/")));
	if(isset($temp['0'])){
	    return $temp['0'];
	} else {
	    return false;
	}
	
    }

}

