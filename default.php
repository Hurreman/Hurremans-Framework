<?php
ob_start();
session_start();

date_default_timezone_set('Europe/Stockholm');

/**
 * Include necessary library files
 */
require_once('config.php');

require_once('lib/FrontController.php');

require_once('lib/MySQL.php');
require_once('lib/Controller.php');
require_once('lib/Model.php');
require_once('lib/Template.php');
require_once('lib/helpers.php');

/**
 * Setup front controller instance
 */
$fc = new FrontController('controls/', 'home', 'error');

/**
 * Setup global database connection
 */
//$db = new cMySQL(array(DB_HOST, DB_DATABASE, DB_USER, DB_PWD));

$fc->execute();

echo '<hr />';
echo 'Memory Usage: ' . convert(memory_get_usage()) . '<br/>';
echo 'Memory Peak: ' . convert(memory_get_peak_usage()) . '<br/>';

function convert($size)
 {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 }

ob_flush();
?>