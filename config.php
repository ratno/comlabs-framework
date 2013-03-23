<?php
define('APPNAME',"My Apps");
define('APPFOOTER',"&copy;2013 Comlabs USDI ITB");
// settingan untuk koneksi ke database
define('SERVER',"localhost");
define('USERNAME',"root");
define('PASSWORD',"");
define('DB_NAME',""); // mohon edit nama databasenya sesuai dengan database anda
define('IDTIMEZONE',"WIB");

/* ubah jika perlu */
error_reporting("E_ERROR");
//settingan untuk aplikasi dan web server
define('DEFAULT_CONTROLLER',"index");
define('DEFAULT_ACTION',"index");
define("BASEURL", "http://".$_SERVER['SERVER_NAME']);
define("MOD_REWRITE",true);
define("BASEDIR",$_SERVER["DOCUMENT_ROOT"]);
define("APP","/application");
$path = str_replace("\\","/",substr(dirname(__FILE__),strlen(BASEDIR)));
define("SUBDIR",$path);
define("APPDIR",BASEDIR.SUBDIR.APP);
define("CONTROLLER",APPDIR."/controller/");
define("MODEL",APPDIR."/model/");
define("VIEW",APPDIR."/view/");
define("FILES",BASEDIR.SUBDIR."/files/");
define("CSSDIR",BASEDIR.SUBDIR."/css/");

define("WEB_URL",BASEURL.SUBDIR."/");
define('FILES_URL', WEB_URL."files/");
define('CSS_URL', WEB_URL."css/");
define('JS_URL', WEB_URL."js/");
define('IMAGES_URL', WEB_URL."images/");

define('JQUERY', "jquery.min.js");
define('JQUERY_UI', "jquery-ui.min.js");
define('JQUERY_UI_CSS', "ui-lightness/jquery-ui.css");

if(DB_NAME == "") {
  define('DATABASE',"cl_framework");
} else {
  define('DATABASE',DB_NAME);
}

if(MOD_REWRITE){
	define("INDEX","");
} else {
	define("INDEX","index.php");
}

switch (IDTIMEZONE) {
  case 'WIB': date_default_timezone_set('Asia/Jakarta'); break;
  case 'WITA': date_default_timezone_set('Asia/Pontianak'); break;
  case 'WIT': date_default_timezone_set('Asia/Jayapura'); break;
}