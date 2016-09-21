<?php
spl_autoload_register('apiAutoload');
function apiAutoload($classname)
{
    include_once __DIR__ . '/request.php';
    include_once __DIR__ . '/../app/AddressStorageService.php';
    if (preg_match('/[a-zA-Z]+Controller$/', $classname)) {
        include_once __DIR__ . '/../app/Controllers/' . $classname . '.php';
        return true;
    } elseif (preg_match('/[a-zA-Z]+Model$/', $classname)) {
        include_once __DIR__ . '/../app/Model/' . $classname . '.php';
        return true;
    }
}