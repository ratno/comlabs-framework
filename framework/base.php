<?php 
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

    function query($sql) {
        $hasil = mysql_query($sql);

        $out = array();
        while($data = mysql_fetch_array($hasil)) {
            $out[] = $data;
        }

        return $out;
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
        return is_numeric($val)?$val:"'".mysql_escape_string($val)."'";
    }

    function loadController($class) {
        $file = CONTROLLER.$this->uri['controller'].".php";

        if(!file_exists($file)) die();

        require_once($file);

        $controller = new $class();

        if(method_exists($controller, $this->uri['method'])) {

            $controller->{$this->uri['method']}($this->uri['var']);
        } else {
            $controller->index();
        }
    }

    function loadView($view,$vars="") {
        if(is_array($vars) && count($vars) > 0)
            extract($vars, EXTR_PREFIX_SAME, "wddx");
        require_once(VIEW.$view.'.php');
    }

    function loadModel($model) {
        require_once(MODEL.$model.'.php');
        $this->$model = new $model;
    }
}