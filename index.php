<?php

/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * bootstrap untuk sistem framework sederhana untuk pelatihan di Comlabs USDI ITB
 */
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
session_start();
require_once("config.php");
require_once("framework/func.php");
require_once("framework/application.php");
require_once("framework/excel_reader2.php");
require_once("framework/spyc.php");

$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strlen(SUBDIR . INDEX));
$url = str_replace(array("/index.php"), "", $url);

$array_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);
// ambil controller
if (isset($array_uri[0])) {
  $uri['controller'] = str_replace(array("-"), "_", $array_uri[0]);
  unset($array_uri[0]);
} else {
  $uri['controller'] = DEFAULT_CONTROLLER;
}

// ambil action/fungsi/method
if (isset($array_uri[1])) {
  $uri['method'] = str_replace(array("-"), "_", $array_uri[1]);
  if (in_array($uri['method'], array("db", "query", "insert", "update", "delete", "escape", "loadController", "loadView", "loadModel"))) {
    die("mohon ubah nama aksi <b>{$uri['method']}</b> di <b>class {$uri['controller']}</b>, karena penamaan fungsi ini digunakan oleh sistem framework<br />--ratno");
  }
  unset($array_uri[1]);
} else {
  $uri['method'] = DEFAULT_ACTION;
}

// ambil parameter 
foreach ($array_uri as $item_uri) {
  $arr_tmp = explode(":", str_replace("=", ":", $item_uri));
  $uri['var'][$arr_tmp[0]] = $arr_tmp[1];
}

$app = new application($uri);
$app->loadController($uri['controller']);