<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'vendor/autoload.php';

use taskforce\exceptions\FileException;
use taskforce\utils\ExportCsv;

$files = ['data/users.csv', 'data/categories.csv', 'data/locations.csv', 'data/responds.csv', 'data/reviews.csv', 'data/tasks.csv'];

foreach ($files as $file) {
$ExportCsv = new ExportCsv($file);
try {
    $ExportCsv->export();
    $ExportCsv->createFileSQL();
} catch (FileException $e) {
    error_log("Ошибка создания файла", $e->getMessage());
}
}







