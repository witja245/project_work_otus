<?php

namespace AutoElita;

\Bitrix\Main\Loader::includeModule("crm");
class Deals
{
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
            array("ID", "STAGE_ID", 'TITLE', 'PARENT_ID_1036')
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
        $deals = self::getListDeals($filter);
        if(!empty($deals)){
            $res = count($deals);
        }else{
            $res = 0;
        }
        return $res;
    }
}