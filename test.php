<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


// Подключаем необходимые модули
use Bitrix\Main\Loader;
use AutoElita\Main;
use AutoElita\Deals;
$filter = ['PARENT_ID_1036'=> 3];
$res = Deals::getCountDeals($filter);
p($res);
