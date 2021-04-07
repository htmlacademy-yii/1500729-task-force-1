<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once ('TaskClass.php');

$test = new TaskClass(1,2);
$active = $test->getNewStatus('in_work', 'action_done');
print($active);
$actions = $test->getActiveActions('new');
print_r($actions);

