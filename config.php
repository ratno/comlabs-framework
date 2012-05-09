<?php
// settingan untuk koneksi ke database
define('SERVER',"localhost");
define('USERNAME',"root");
define('PASSWORD',"");
define('DATABASE',""); // mohon edit nama databasenya sesuai dengan database anda

/* ubah jika perlu */
//settingan untuk aplikasi dan web server
define('DEFAULT_CONTROLLER',"index");
define('DEFAULT_ACTION',"index");
define("BASEURL", "http://".$_SERVER['SERVER_NAME']);
define("MOD_REWRITE",true);
define("BASEDIR",$_SERVER["DOCUMENT_ROOT"]);
define("APP","/application");
$path = str_replace("\\","/",substr(dirname(__FILE__),strlen(BASEDIR)));
define("SUBDIR",$path);
define("CONTROLLER",BASEDIR.SUBDIR.APP."/controller/");
define("MODEL",BASEDIR.SUBDIR.APP."/model/");
define("VIEW",BASEDIR.SUBDIR.APP."/view/");
define("FILES",BASEDIR.SUBDIR."/files/");

define("WEB_URL",BASEURL.SUBDIR."/");
define('FILES_URL', WEB_URL."files/");
define('CSS_URL', WEB_URL."css/");
define('JS_URL', WEB_URL."js/");
define('IMAGES_URL', WEB_URL."images/");

if(!defined(DATABASE)) {
  define('DATABASE',"cl_framework");
}

if(MOD_REWRITE){
	define("INDEX","");
} else {
	define("INDEX","index.php");
}

date_default_timezone_set('Asia/Krasnoyarsk');