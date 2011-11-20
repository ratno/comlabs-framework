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
    $hasil = mysql_query($sql);
    if ($hasil === true) {
      return mysql_affected_rows();
    } elseif ($hasil === false) {
      return false;
    } else {
      $out = array();
      $rows = mysql_num_rows($hasil);
      if ($rows > 1) {
        while ($data = mysql_fetch_assoc($hasil)) {
          $out[] = $data;
        }
      } else {
        $out = mysql_fetch_assoc($hasil);
      }

      if ($single) {
        return $out[0];
      } else {
        return $out;
      }
    }
  }

  function insert($data, $execute=true, $process_file=true, $tabel="") {

    $kolom = $isi = array();
    foreach ($data as $key => $value) {
      $arrkey = explode("___", $key);
      if ($arrkey[0] == 'ztglz') {
        $key = $arrkey[1];
        $value = tanggal($value);
      }
      $kolom[] = $key;
      $isi[] = $this->escape($value);
    }

    if ($process_file && isset($_FILES)) {
      foreach ($_FILES as $file_form => $file) {
        if (is_array($file) && $file['tmp_name'] != "" && !empty($file['tmp_name'])) {
          $nama_file = date('Y.m.d.h.i.s.') . $file['name'];
          move_uploaded_file($file['tmp_name'], FILES . $nama_file);
          $kolom[] = $file_form;
          $isi[] = $this->escape($nama_file);
        }
      }
    }

    if (!$tabel)
      $tabel = $this->tabel;

    $sql = "INSERT INTO " . $tabel;
    $sql .= " (" . implode(",", $kolom) . ")";
    $sql .= " VALUES (" . implode(",", $isi) . ")";
    if ($execute) {
      return mysql_query($sql);
    } else {
      return $sql;
    }
  }

  function update($data, $id) {
    $update = array();
    foreach ($data as $key => $value) {
      $arrkey = explode("___", $key);
      if ($arrkey[0] == 'ztglz') {
        $key = $arrkey[1];
        $value = tanggal($value);
      }
      $update[] = $key . "=" . $this->escape($value);
    }

    if (isset($_FILES)) {
      foreach ($_FILES as $file_form => $file) {
        if (is_array($file) && $file['tmp_name'] != "" && !empty($file['tmp_name'])) {
          $nama_file = date('Y.m.d.h.i.s.') . $file['name'];
          move_uploaded_file($file['tmp_name'], FILES . $nama_file);
          $update[] = $file_form . "=" . $this->escape($nama_file);
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
    if ($val == "" || empty($val) || is_null($val) || $val == 'null') {
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

  function loadView($view, $vars="", $echo=true, $return="all") {
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
    if ($echo) {
      echo $out;
    } else {
      if ($return == "main") {
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

  function impor($controller, $tabel) {
    $data['judul'] = "Impor Data XLS";
    $data['controller'] = $controller;
    $data['tabel'] = $tabel;
    $data['aksi'] = "impor_preview";
    $this->loadView("general/impor", $data);
  }

  function impor_preview() {
    if (!key_exists('controller', $_POST)) {
      header("location: " . url('index'));
      die();
    }
    $xls = new Spreadsheet_Excel_Reader($_FILES['file_csv']['tmp_name']);

    $sheet_num = $_POST['sheet_num'] - 1;
    $header_row = $_POST['header_row'];
    $first_row = $_POST['first_row'];
    $last_row = ($_POST['last_row']) ? ($_POST['last_row']) : count($xls->rowInfo[$sheet_num]);

    $controller = $_POST['controller'];
    $tabel = $_POST['tabel'];

    $cells = $xls->sheets[$sheet_num]['cells'];

    if (isset($_POST['columns']) && !empty($_POST['columns']) && $_POST['columns'] != '') {
      $columns = explode(",", $_POST['columns']);
    } else {
      $columns = $cells[$header_row];
    }

    $col_num = count($cells[$header_row]);

    $inserts = array();

    // judul
    $tbl_header = "";
    $tbl_header .= "<tr>";
    foreach ($cells[$header_row] as $col) {
      $tbl_header .= "<td>" . $col . "</td>";
    }
    $tbl_header .= "</tr>";

    // content
    $tbl_content = "";
    for ($i = $first_row; $i <= $last_row; $i++) {
      $tbl_content .= "<tr>";
      $row = array();
      for ($c = 1; $c <= $col_num; $c++) {
        $tbl_content .= "<td>" . $cells[$i][$c] . "</td>";

        $row[$columns[$c]] = $cells[$i][$c];
      }

      $inserts[] = $this->insert($row, false, false, $tabel);
      $tbl_content .= "</tr>";
    }

    $tbl = "<table border='1' cellpadding='5' cellspacing='0'>";
    $tbl .= $tbl_header;
    $tbl .= $tbl_content;
    $tbl .= "</table>";

    $data['judul'] = "Preview Data Hasil Impor";
    $data['controller'] = $controller;
    $data['aksi'] = "impor_status";
    $data['data'] = implode("||", $inserts);
    $data['tabel'] = $tbl;
    $this->loadView("general/impor_preview", $data);
  }

  function impor_status() {
    if (!key_exists('insert', $_POST)) {
      header("location: " . url('index'));
      die();
    }
    $insert = explode('||', $_POST['insert']);
    $data['judul'] = "Status Simpan Impor Data";
    $data['hasil'] = "";
    foreach ($insert as $sql) {
      $sql = str_replace("\\'", "'", $sql);
      $hasil = $this->query($sql);
      if ($hasil) {
        $status = "sukses";
      } else {
        $status = "gagal";
      }
      $data['hasil'] .= "[$status] $sql<br />\n";
    }
    $this->loadView('general/impor_status', $data);
  }

}