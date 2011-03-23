<?php
error_reporting(~E_NOTICE);
session_start();
require_once("config.php");
require_once("framework/func.php");
require_once("framework/base.php");

$url = $_SERVER['REQUEST_URI'];
$url = 	str_replace(array(SUBDIR.INDEX,"/index.php"),"",$url);


$array_tmp_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);

$array_uri['controller'] 	= ($array_tmp_uri[0])?$array_tmp_uri[0]:DEFAULT_CONTROLLER; //class
$array_uri['method']		= $array_tmp_uri[1]; //function
$array_uri['var']			= $array_tmp_uri[2]; //variable

$app = new application($array_uri);
$app->loadController($array_uri['controller']);