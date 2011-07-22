<?php

/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * Basis kelas untuk framework sederhana yang digunakan untuk pelatihan di Comlabs USDI ITB
 */
if (!defined('BASEURL'))
  die('Liat apa kamoe!!!');

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
    $this->db = mysql_connect(SERVER, USERNAME, PASSWORD);
    mysql_select_db(DATABASE);
  }

  function query($sql, $single=false) {
//    die($sql);
    $hasil = mysql_query($sql);

    $out = array();
    while ($data = mysql_fetch_assoc($hasil)) {
      $out[] = $data;
    }

    if ($single) {
      return $out[0];
    } else {
      return $out;
    }
  }

  function insert($data) {
    
    $kolom = $isi = array();
    foreach ($data as $key => $value) {
      $kolom[] = $key;
      $isi[] = $this->escape($value);
    }
    
    if(isset ($_FILES)){
      foreach ($_FILES as $file_form=>$file){
        if(is_array($file) && $file['tmp_name'] != "" && !empty ($file['tmp_name'])){
          $nama_file = date('Y.m.d.h.i.s.').$file['name'];
          move_uploaded_file($file['tmp_name'], FILES.$nama_file);
          $kolom[] = $file_form;
          $isi[] = $this->escape($nama_file);
        }
      }
    }

    $sql = "INSERT INTO " . $this->tabel;
    $sql .= " (" . implode(",", $kolom) . ")";
    $sql .= " VALUES (" . implode(",", $isi) . ")";
//    die($sql);
    return mysql_query($sql);
  }

  function update($data, $id) {
    $update = array();
    foreach ($data as $key => $value) {
      $update[] = $key . "=" . $this->escape($value);
    }

   if(isset ($_FILES)){
      foreach ($_FILES as $file_form=>$file){
        if(is_array($file) && $file['tmp_name'] != "" && !empty ($file['tmp_name'])){
          $nama_file = date('Y.m.d.h.i.s.').$file['name'];
          move_uploaded_file($file['tmp_name'], FILES.$nama_file);
          $update[] = $file_form ."=". $this->escape($nama_file);
        }
      }
    }

    $sql = "UPDATE " . $this->tabel . " SET " . implode(",", $update) . " WHERE " . $this->pk . "=" . $this->escape($id);
    //die($sql);
    return mysql_query($sql);
  }

  function delete($id) {
    $sql = "DELETE FROM " . $this->tabel . " WHERE " . $this->pk . " = " . $this->escape($id);
    return mysql_query($sql);
  }

  function escape($val) {
    $val = trim($val);
    if($val=="" || empty ($val) ||  is_null($val) || $val == 'null'){
      return 'null';
    }
    return is_numeric($val) ? "'$val'" : "'" . mysql_escape_string($val) . "'";
  }

  function loadController($class) {
    $file = CONTROLLER . $this->uri['controller'] . ".php";

    if (!file_exists($file))
      header("location: " . BASEURL . SUBDIR);

    require_once($file);

    $controller = new $class();

    if (method_exists($controller, $this->uri['method'])) {
      $controller->{$this->uri['method']}($this->uri['var']);
    } else {
      $controller->index();
    }
  }

  var $layout = "layout";

  function loadView($view, $vars="",$echo=true,$return="all") {
    if (is_array($vars) && count($vars) > 0)
      extract($vars, EXTR_PREFIX_SAME, "wddx");
    ob_start();
    require_once(VIEW . $view . '.php');
    $main_content = ob_get_clean();
    
    ob_start();
    $file = VIEW . $this->layout . '.php';
    if (file_exists($file)) {
      require_once($file);
    } else {
      echo $main_content;
    }
    $out = ob_get_clean();
    if($echo){
      echo $out;
    } else {
      if($return == "main"){
        return $main_content;
      } else {
        return $out;
      }
    }
  }

  function loadModel($model) {
    require_once(MODEL . $model . '.php');
    $this->$model = new $model;
  }

}