<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


// Подключаем необходимые модули
use Bitrix\Main\Loader;
use Bitrix\Crm\DealProductRowTable;
use AutoElita\Main;

// Подключаем необходимые модули
use Bitrix\Main\Entity;
use Bitrix\Crm\CompanyTable;
use Bitrix\Main\UserAccessException;
use Bitrix\Main\Type\DateTime;
\Bitrix\Main\Loader::includeModule("crm");
// Функция для получения списка компаний

$res = Main::addCountProduct(43, 0);
p($res);



