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

$fc->execute();

ob_flush();
?>