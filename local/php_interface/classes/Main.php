<?php

namespace AutoElita;

use \Bitrix\Crm;


\Bitrix\Main\Loader::IncludeModule('iblock');

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
            'limit'  => 1,
            'cache'  => [
                'ttl' => $cacheTime
            ]
        ])->fetch();

        return (int) ($iblock['ID'] ?? 0);
    }

}