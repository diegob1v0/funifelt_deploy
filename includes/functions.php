<?php

define('FOLDER_IMAGES', $_SERVER['DOCUMENT_ROOT'] . '/build/img/apps/');
define('FOLDER_APK', $_SERVER['DOCUMENT_ROOT'] . '/build/apks/');
define('FOLDER_ALLIES', $_SERVER['DOCUMENT_ROOT'] . '/build/img/allies/');

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado y su rol
function getAuth(): array
{

    session_start();
    $roleID = $_SESSION['roleID'] ?? '1';
    $array = [];

    if (!isset($_SESSION['login'])) {

        $array = [
            'isAuth' => false,
            'roleID' => '1'
        ];

        return $array;
    }

    $array = [
        'isAuth' => true,
        'roleID' => $roleID
    ];

    return $array;
}

function redirect($auth, $RoleAccept): void
{
    if (!$auth['isAuth'] || !in_array($auth['roleID'], $RoleAccept)) {
        header('Location: /');
        exit;
    }
}

function showNotification($code): string
{
    $message = '';


    switch ($code) {
        case 1:
            $message = translate('created_successfully');
            break;
        case 2:
            $message = translate('updated_successfully');
            break;
        case 3:
            $message = translate('deleted_successfully');
            break;
        case 4:
            $message = translate('created_failed');
            break;
        case 5:
            $message = translate('updated_failed');
            break;
        default:
            $message = false;
            break;
    }
    return $message;
}


function validateOrRedirect(string $url): int
{
    $id = $_GET['id'] ?? null;
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location: {$url}");
    }
    return $id;
}

function safeFolderName($name): string
{
    $name = strtolower($name);

    $name = str_replace(' ', '_', $name);

    $name = preg_replace('/[^a-z0-9_-]/', '', $name);

    if (empty($name)) {
        $name = 'undefined';
    }

    return $name;
}

function createCompanyImageFolder($owner, $type)
{
    if ($type === 'apps') {
        $folderOwner = FOLDER_IMAGES . s($owner) . '/';
    }

    if ($type === 'apks') {
        $folderOwner = FOLDER_APK . s($owner) . '/';
    }

    if ($type === 'allies') {
        $folderOwner = FOLDER_ALLIES . s($owner) . '/';
    }

    if (!is_dir($folderOwner)) {
        mkdir($folderOwner);
    }


    return $folderOwner;
}

function isMobileDevice()
{
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

    return preg_match('/iphone|ipad|ipod|android|mini|windows\sce|palm/i', $userAgent);
}


function translate(string $key): string
{
    global $translations;
    return $translations[$key] ?? $key;
}
