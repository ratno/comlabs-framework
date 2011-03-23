<?php
define('SERVER',"localhost");
define('USERNAME',"root");
define('PASSWORD',"");
define('DATABASE',"");
define('DEFAULT_CONTROLLER',"index");
define('DEFAULT_ACTION',"index");
define("BASEURL", "http://localhost");

/* ubah jika perlu */
define("MOD_REWRITE",true);
define("BASEDIR",$_SERVER["DOCUMENT_ROOT"]);
define("APP","/application");
$path = substr(dirname(__FILE__),strlen(BASEDIR));
define("SUBDIR",$path);
define("CONTROLLER",BASEDIR.SUBDIR.APP."/controller/");
define("MODEL",BASEDIR.SUBDIR.APP."/model/");
define("VIEW",BASEDIR.SUBDIR.APP."/view/");

if(MOD_REWRITE){
	define("INDEX","");
} else {
	define("INDEX","index.php");
}