<?php

use Bitrix\Main\EventManager;
use AutoElita\Main;
use Bitrix\Main\SystemException;

$eventManager = EventManager::getInstance();
$eventManager->addEventHandlerCompatible(
    'crm',
    'OnBeforeCrmDealAdd',
    'OnBeforeCrmDealAddHandler',
);
function OnBeforeCrmDealAddHandler(&$arFields)
{
    try {

        $dealsCrm = Main::getListDeals(["PARENT_ID_1036" => $arFields['PARENT_ID_1036']]);

        if (!empty($dealsCrm)) {
            throw new SystemException("Повторная сделка, есть активные сделки. Сначала нужно завершить предыдущие.");
            return false;
        }

    } catch (SystemException $exception) {
        $arFields['RESULT_MESSAGE'] = $exception->getMessage();
        return false;
    }

}