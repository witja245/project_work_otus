<?php
Bitrix\Main\Loader::registerAutoLoadClasses(null, [

    //Главный класс
    'AutoElita\Main' => '/local/php_interface/classes/Main.php',

    //Класс для работы со сделками
    'AutoElita\Deals' => '/local/php_interface/classes/Deals.php',

    //Класс для работы с катологом товаров
    'AutoElita\Products' => '/local/php_interface/classes/Products.php'
]);