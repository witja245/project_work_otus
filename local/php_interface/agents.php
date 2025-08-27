<?php
use AutoElita\Main;
use AutoElita\Products;

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');
/**
 * Агент получения случайного кол-ва товаров
 * @return void
 */
function agentRandomCountProducts()
{

    $products = Products::getTorgPredList();

    foreach ($products as $product) {

        $countProducts = randomCount();

        if ($countProducts == 0){
            $iblockId = Main::getIblockIdByCode('bizproccess');
            $PROP = array();
            $PROP['DETALI_DLYA_ZAKUPKI'] = $product['NAME'];
            $PROP['KOLICHESTVO_DETALEY'] = 10;
            $arFields = [
                "IBLOCK_ID" => $iblockId,
                "NAME" => '111111',
                "PROPERTY_VALUES" => $PROP,
                "KOLICHESTVO_DETALEY" => $iblockId,
            ];
            $elementId =  Main::addIblockIdElement($arFields);

            
            Main::startBP(17, [
                "lists", "BizprocDocument", $elementId
            ]);
        }
        if($countProducts > 0){
            Products::addCountProduct($product['ID'], $countProducts);
        }

    }
    return 'agentRandomCountProducts();';

}