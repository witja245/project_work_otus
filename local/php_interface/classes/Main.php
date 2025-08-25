<?php

namespace AutoElita;

use \Bitrix\Crm;

\Bitrix\Main\Loader::IncludeModule('crm');
\Bitrix\Main\Loader::IncludeModule('catalog');
\Bitrix\Main\Loader::IncludeModule('iblock');

//мне нужен код, который по фильтру: PARENT_ID_1036 и STAGE_ID находит сделки
class Main
{

    const sklad = 1;
    const IBLOCK_ID = 14;
    /**
     * Получние сделок по по id привязаного автомобиля
     * @param $arParams
     * @return void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getListDeals($filter)
    {

        $dbDeal = \CCrmDeal::GetListEx(
            array("ID" => "ASC"),
            $filter,
            false,
            false,
            array("*")
        );

        $deals = array();
        while ($arDeal = $dbDeal->fetch()) {

            switch ($arDeal['STAGE_ID']) {
                case 'C1:WON':
                    $deals = false;
                    break;
                case 'C1:APOLOGY':
                    $deals = false;
                    break;
                case 'C1:LOSE':
                    $deals = false;
                    break;

                default:
                    $deals[] = $arDeal;
                    break;
            }

        }

        return $deals;
    }

    /**
     * получение кол-во сделок по фильтру
     * @param $filter
     * @return int
     */
    public static function getCountDeals($filter)
    {

        $dbDeal = \CCrmDeal::GetListEx(
            array("ID" => "ASC"),
            $filter,
            false,
            false,
            array("*")
        );

        $deals = array();
        while ($arDeal = $dbDeal->fetch()) {

            switch ($arDeal['STAGE_ID']) {
                case 'C1:WON':
                    $deals = false;
                    break;
                case 'C1:APOLOGY':
                    $deals = false;
                    break;
                case 'C1:LOSE':
                    $deals = false;
                    break;

                default:
                    $deals[] = $arDeal;
                    break;
            }

        }
        if(!empty($deals)){
            $res = count($deals);
        }else{
            $res = 0;
        }

        return $res;
    }

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
                'filter' => ['ID' => self::sklad],
                'select' => ['*']
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

        $arInfo = \CCatalogSKU::GetInfoByProductIBlock(self::IBLOCK_ID);
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
            array("*")
        );
        $arProducts = array();
        while ($arOffer = $rsOffers->GetNext()) {
            $arProducts[]=$arOffer;
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
            'filter' => ['ID' => self::sklad],
            'select' => ['*']
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