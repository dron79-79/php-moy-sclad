<?php

use Curl\Curl;
class MoySclad {
    
    /**
     * Для JSON API установлены следующие ограничения:
     * Не более 100 запросов за 5 секундный период
     * Не более 5 параллельных запросов от одного пользователя
     * Не более 20 параллельных запросов от аккаунта
     * Не более 500 запросов с одного ip-адреса
     * Не более 10 Мб данных в одном запросе, отправляемом на сервер
     * Не более 100 элементов объектов (позиций, материалов, продуктов), передаваемых в одном массиве в запросе.
     *  array Ограничения накладываемые API Мой склад
     */
    
    const MAXCOUNTREQUESTPER5S = 100;
    const MAXCOUNTPARALELLREQUESTUSER = 5;
    const MAXCOUNTPARALELLREQUESTACOUNT = 20;
    const MAXCOUNTREQUESTPERONEIP = 500;
    const MAXSIZEPOSTMB = 10;
    const MAXCOUNTENTITYPERREQUEST = 100;
    
    private $error;
    private $curl;
    private static $counter = 0;
    private $requestes = 0;


    public function __construct() {
	$this->curl = new Curl(); 
	$this->authorization();
	++self::$counter;
    }
    
    private function getSessionRequestes() {
	if (isset($_SESSION['requestes'])){
	    $this->requestes = $_SESSION['requestes'];
	} else {
	    $this->requestes = 0;
	}
    }
    
    private function addSessionRequestes() {
	if (isset($_SESSION['requestes'])){
	    $_SESSION['requestes'] = $_SESSION['requestes']+1;
	} else {
	    $_SESSION['requestes'] = 1;
	}
    }
    
    
    /**
     * Аутентификация по протоколу Basic Auth
     */
    public function authorization(){
	$this->curl->setBasicAuthentication(MOYSCLADLOGIN, MOYSCLADPASS);
    }
    
    /**
     * Запрос всех Заказов Покупателей на данной учётной записи
     * @param int $limit Максимальное количество сущностей для извлечения.
     * @param int $offset Отступ в выдаваемом списке сущностей
     * @param string $search URL Параметр для поиска по имени документа. Фильтр документов по указанной поисковой строке. Фильтрация происходит по полю name
     * @return stdClass Object включающий в себя поля:
     *	meta Метаданные о выдаче,
     *  context - Метаданные о сотруднике, выполнившем запрос.
     *	rows - Массив JSON объектов, представляющих собой Заказы Покупателей.
     */
    public function getCustomerOrder($limit = 25, $offset = 0, $search = null){
	$this->addSessionRequestes();
	$url = MOYSCLADURL."entity/customerorder";
	$data = array('limit' => $limit, 'offset' => $offset, 'search' => $search);
	$customerorders = $this->curl->get($url, $data);
	return $customerorders;
    }
    
    /**
     * Получить список всех Контрагентов
     * @param int $limit Максимальное количество сущностей для извлечения
     * @param int $offset Отступ в выдаваемом списке сущностей
     * @return stdClass Object включающий в себя поля:
     * meta Метаданные о выдаче,
     * context - Метаданные о сотруднике, выполнившем запрос.
     * rows - Массив JSON объектов, представляющих собой Контрагентов.
     */
    public function getCounterparties($limit = 25, $offset = 0){
	$this->addSessionRequestes();
	$url = MOYSCLADURL."entity/counterparty";
	$data = array('limit' => $limit, 'offset' => $offset);
	$counterparties = $this->curl->get($url, $data);
	return $counterparties;
    }

    /**
     * Возвращает JSON представление Контрагента с указанным id.
     * @param string $accountId id Контрагента. Example: 7944ef04-f831-11e5-7a69-971500188b19
     * @return stdClass Object
     */
    public function getCounterparty($accountId) {
	$this->addSessionRequestes();
	$url = MOYSCLADURL."/entity/counterparty/".$accountId;
	$counterparty = $this->curl->get($url);
	return $counterparty;
    }
    
    /**
     * Получить список контактных лиц Контрагента с указанным id.
     * @param string $accountId id Контрагента. (required) Example: 7944ef04-f831-11e5-7a69-971500188b19
     * @param int $limit Максимальное количество сущностей для извлечения. (optional) Default: 25 Example: 100
     * @param int $offset Отступ в выдаваемом списке сущностей (optional) Default: 0 Example: 40
     * @return stdClass Object массив JSON представлений контактных лиц Контрагента
     */
    public function getContactpersons($accountId, $limit = 25, $offset = 0){
	$this->addSessionRequestes();
	$url = MOYSCLADURL."/entity/counterparty/".$accountId."/contactpersons";
	$data = array('limit' => $limit, 'offset' => $offset);
	$contactpersons = $this->curl->get($url, $data);
	return $contactpersons;
    } 
}
