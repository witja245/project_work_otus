<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php'); ?>
<?
$APPLICATION->IncludeComponent(
    "dellindev.companycustom:company.list",
    "",
    Array(),
    false
);
?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');