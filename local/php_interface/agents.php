<?php
use AutoElita\Main;

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
/**
 * Агент получения случайного кол-ва товаров
 * @return void
 */
function agentRandomCountProducts()
{

    $products = Main::getTorgPredList();
    foreach ($products as $product) {
        $countProducts = randomCount();

        Main::addCountProduct($product['ID'], $countProducts);
    }
    return 'agentRandomCountProducts();';

}