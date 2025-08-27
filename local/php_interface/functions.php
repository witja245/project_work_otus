<?php

function randomCount()
{
    try {
        $url = "https://www.random.org/integers/?num=1&min=0&max=10&col=1&base=10&format=plain&rnd=new";

        // Проверяем доступность URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception("Неверный URL");
        }

        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception("Не удалось получить данные");
        }

        $random_number = trim($response);

        if (!is_numeric($random_number)) {
            throw new Exception("Полученные данные не являются числом");
        }

        return (int)$random_number;

    } catch (Exception $e) {
        return "Произошла ошибка: " . $e->getMessage();
    }
}