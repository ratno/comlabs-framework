<?php
include_once("config.php");

$cssfile = CSSDIR . $_GET['f'];
if (file_exists($cssfile)) {
  $css = file_get_contents($cssfile);
  $css = str_replace(array("IMAGES_URL"), array(IMAGES_URL), $css);
} else {
  $css = "";
}

header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 * 24 * 3;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);
echo $css;