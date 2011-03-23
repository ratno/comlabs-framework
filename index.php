<?php
error_reporting(~E_NOTICE);
session_start();
require_once("config.php");
require_once("framework/func.php");
require_once("framework/base.php");

$url = $_SERVER['REQUEST_URI'];
$url = 	str_replace(array(SUBDIR.INDEX,"/index.php"),"",$url);

$array_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);

// ambil controller
if(isset($array_uri[0])){
	$uri['controller'] 	= $array_uri[0]; //class
	unset($array_uri[0]);
} else {
	$uri['controller'] 	= DEFAULT_CONTROLLER;
}	

// ambil action/fungsi/method
if(isset($array_uri[1])){
	$uri['method']		= $array_uri[1];
	unset($array_uri[1]);
} else {
	$uri['method']		= DEFAULT_ACTION;
}

// ambil parameter 
foreach($array_uri as $item_uri){
	$arr_tmp = explode(":",str_replace("=",":",$item_uri));
	$uri['var'][$arr_tmp[0]] = $arr_tmp[1];
}

$app = new application($uri);
$app->loadController($uri['controller']);