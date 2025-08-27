<?php

namespace AutoElita;

use Bitrix\AI\Result;
use \Bitrix\Crm;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;

if (!Loader::includeModule('iblock')) {
    throw new Exception("Модуль iblock не подключен");
}


//мне нужен код, который по фильтру: PARENT_ID_1036 и STAGE_ID находит сделки
class Main

{

    /**
     * Возвращает ID инфоблока по его коду
     * @param string $code
     * @param bool $codeIsApi
     * @param int $cacheTime
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getIblockIdByCode(string $code, bool $codeIsApi = false, int $cacheTime = 86400000): int
    {
        $filterField = $codeIsApi ? '=API_CODE' : '=CODE';
        $filter = [$filterField => $code];
        $iblock = \Bitrix\Iblock\IblockTable::getList([
            'filter' => $filter,
            'select' => ['ID'],
            'limit' => 1,
            'cache' => [
                'ttl' => $cacheTime
            ]
        ])->fetch();

        return (int)($iblock['ID'] ?? 0);
    }

    public static function addIblockIdElement($fields)
    {
        $el = new \CIBlockElement;

        if($PRODUCT_ID = $el->Add($fields))
           return $PRODUCT_ID;
        else
            return $el->LAST_ERROR;
    }

    public static function startBP($templateID, $documentId, $arParams = array())
    {
        $res = \CBPDocument::StartWorkflow(
            $templateID,
            $documentId,
            $arParams,
            $arErrorsTmp
        );

        return $res;
    }

}