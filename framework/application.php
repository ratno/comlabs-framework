<?php
/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * Basis kelas untuk framework sederhana yang digunakan untuk pelatihan di Comlabs USDI ITB
 */ 
if (!defined('BASEURL')) die('Liat apa kamoe!!!');

class application {
    var $uri;
    var $model;
    var $db;
    var $tabel;
    var $pk;

    function __construct($uri) {
        $this->uri = $uri;
    }

    function db() {
        $this->db = mysql_connect(SERVER,USERNAME,PASSWORD);
        mysql_select_db(DATABASE);
    }

    function query($sql,$single=false) {
        $hasil = mysql_query($sql);

        $out = array();
        while($data = mysql_fetch_assoc($hasil)) {
            $out[] = $data;
        }
		
		if($single){
			return $out[0];
		} else {
			return $out;
		}
    }

    function insert($data) {
        $kolom = $isi = array();
        foreach ($data as $key=>$value) {
            $kolom[] = $key;
            $isi[] = $this->escape($value);
        }

        $sql = "INSERT INTO ".$this->tabel;
        $sql .= " (".implode(",",$kolom).")";
        $sql .= " VALUES (".  implode(",", $isi).")";

        return mysql_query($sql);
    }

    function update($data,$id) {
        $update = array();
        foreach ($data as $key=>$value) {
            $update[] = $key."=".$this->escape($value);
        }

        $sql = "UPDATE ".$this->tabel." SET ".implode(",", $update)." WHERE ".$this->pk."=".$this->escape($id);
        //die($sql);
        return mysql_query($sql);
    }

    function delete($id) {
        $sql = "DELETE FROM ".$this->tabel." WHERE ".$this->pk." = ".$this->escape($id);
        return mysql_query($sql);
    }

    function escape($val) {
        $val = trim($val);
        return is_numeric($val)?"'$val'":"'".mysql_escape_string($val)."'";
    }

    function loadController($class) {
        $file = CONTROLLER.$this->uri['controller'].".php";

        if(!file_exists($file)) header("location: ".BASEURL.SUBDIR);

        require_once($file);

        $controller = new $class();

        if(method_exists($controller, $this->uri['method'])) {
            $controller->{$this->uri['method']}($this->uri['var']);
        } else {
            $controller->index();
        }
    }

	var $layout = "layout";
    function loadView($view,$vars="") {
        if(is_array($vars) && count($vars) > 0)
            extract($vars, EXTR_PREFIX_SAME, "wddx");
		ob_start();
        require_once(VIEW.$view.'.php');
		$main_content = ob_get_clean();
		$file = VIEW.$this->layout.'.php';
		if(file_exists($file)) {
			require_once($file);
		} else {
			echo $main_content;
		}
    }

    function loadModel($model) {
        require_once(MODEL.$model.'.php');
        $this->$model = new $model;
    }
}