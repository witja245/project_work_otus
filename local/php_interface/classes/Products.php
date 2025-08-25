<?php

namespace AutoElita;

use \AutoElita\Main;
use \Bitrix\Main\Loader;
Loader::IncludeModule('catalog');

Loader::includeModule("crm");
class Products
{
    const sklad = 1;

    /**
     * Получить товары в сделке по ИД сдеки
     * @param $dealsId
     * @return array
     */
    public static function getProductDeals($dealsId)
    {

        $arFilter = [
            "OWNER_TYPE" => "D", // "L" - тип
            "OWNER_ID" => $dealsId, //ID сделки, лида, предложения
            "CHECK_PERMISSIONS" => "N" //не проверять права доступа текущего пользователя
        ];
        $arSelect = [
            "*"
        ];
        $res = \CCrmProductRow::GetList(['ID' => 'DESC'], $arFilter, false, false, $arSelect);
        $result = [];
        while ($arProduct = $res->Fetch()) {

            $result[] = $arProduct;
        }

        return $result;
    }

    /**
     * Получаем по ид товара кол-во на складе
     * @param $productId
     * @return mixed|string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getProductRemains($productId)
    {

        if ($productId) {
            $storeData = \Bitrix\Catalog\StoreTable::getList([
                'filter' => ['ID' => 1],
                'select' => ['ID']
            ])->fetch();

            $storeId = $storeData['ID'];

            $storeProductData = \Bitrix\Catalog\StoreProductTable::getList([
                'filter' => ['PRODUCT_ID' => $productId, 'STORE_ID' => $storeId]
            ])->fetch();

            $quantity = $storeProductData["AMOUNT"];
            return $quantity;
        } else {
            return "Товар с ID {$productId} не найден.";
        }

    }

    /**
     * Получения всех торговых предложений
     * @return array
     */
    public static function getTorgPredList($productId = false)
    {

        $arInfo = \CCatalogSKU::GetInfoByProductIBlock(Main::getIblockIdByCode('catalog'));
        $filter = [
            'IBLOCK_ID' => $arInfo['IBLOCK_ID'],
            "ACTIVE" => "Y"
        ];
        if ($productId) {
            $key = 'PROPERTY_' . $arInfo['SKU_PROPERTY_ID'];
            $filter[$key] = $productId;
        }

        $rsOffers = \CIBlockElement::GetList(
            array(),
            $filter,
            false,
            false,
            array("ID", 'NAME', 'ACTIVE')
        );

        $arProducts = array();
        while ($arOffer = $rsOffers->GetNext()) {

            $arProducts[] = $arOffer;
        }

        return $arProducts;
    }

    /**
     * Добавление кол-ва товаров на склад
     * @param $productId
     * @param $count
     * @return void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function addCountProduct($productId, $count, $add = false)
    {
        $storeData = \Bitrix\Catalog\StoreTable::getList([
            'filter' => ['ID' => 1],
            'select' => ['ID']
        ])->fetch();

        $storeId = $storeData['ID'];

        $storeProductData = \Bitrix\Catalog\StoreProductTable::getList([
            'filter' => ['PRODUCT_ID' => $productId, 'STORE_ID' => $storeId]
        ])->fetch();
        if ($add == false){
            $newAmount = intval($count);
        }
        if ($add == true){
            $currentAmount = $storeProductData['AMOUNT'];

            $newAmount = $currentAmount + intval($count);
        }


        $updateFields = [
            "PRODUCT_ID" => $productId,
            "STORE_ID" => $storeId,
            "AMOUNT" => $newAmount,
            'AVAILABLE' => 'Y'
        ];

        \Bitrix\Catalog\StoreProductTable::update($storeProductData['ID'], $updateFields);

    }
}