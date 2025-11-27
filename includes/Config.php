<?php

$lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
    ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
    : 'en';
$supportedLanguages = ['es', 'en'];

if (!in_array($lang, $supportedLanguages)) {
    $lang = 'en';
};

return include __DIR__ . "/../lang/$lang.php";
