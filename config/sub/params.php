<?php

defined('HOST') || define('HOST', 'coublike.ru');
defined('ADMIN_URL') || define('ADMIN_URL', 'adminka');

define('FLASH_ERROR', 'danger');
define('FLASH_OK', 'success');
define('FLASH_AHTUNG', 'warning');


$isEn = true;

if (isset($_GET['lng'])) {
    setcookie('lng', $_GET['lng'], 9999999999, '/');
    if ($_GET['lng'] != 'en')
        $isEn = false;
}
elseif (isset($_COOKIE['lng'])) {
    if ($_COOKIE['lng'] != 'en')
        $isEn = false;
}
//elseif (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], HOST)!==false) {
//    $isEn = false;
//}
elseif (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'ru-RU') !== false) {
    $isEn = false;
}

if ($isEn) {
    define('LOC_IS_RU', false);
    define('LOC_TAG', 'en');
    define('LOC_LANG', 'en-US');
} else {
    define('LOC_IS_RU', true);
    define('LOC_TAG', 'ru');
    define('LOC_LANG', 'ru-RU');
}


return [
    'adminEmail' => 'admin@' . HOST,
    'supportEmail' => 'support@' . HOST,
    'freeLikes' => 20, // ежедневный подарок
    'earnBuyGift' => 10, // % от покупки лайков
    'earnLikeGift' => 5, // % от заработка
    'refLikeGift' => 5,  // % от зарабока рефералов
    // TODO : level gift referrers
    'lang' => [
        'ru' => ['img' => '/img/lang/ru.png', 'title' => 'Русский', 'url' => '?lng=ru'],
        'en' => ['img' => '/img/lang/en.png', 'title' => 'English', 'url' => '?lng=en'],
    ],
    'taskStatsMode' => [ // Задаем режим сбора статистики в зависимости от суммы потраченного на задание
         0 => 1,
         1000 => 2,
         3000 => 3,
         5000 => 4,
         10000 => 5,
         20000 => 6,
         50000 => 7,
    ]
];
