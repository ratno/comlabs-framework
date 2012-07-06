<?php

class model_user extends application {

  public static $static_tabel = "user";
  public static $static_pk = "id";

  function __construct() {
    $this->db(); // koneksi ke database
    $this->tabel = self::$static_tabel;
    $this->pk = self::$static_pk;
  }

  function ambil_data($klausa=null) {
    $sql = "SELECT * FROM " . $this->tabel;
    if ($klausa) {
      $sql .= " " . $klausa;
    } else {
      $sql .= " ORDER BY ". $this->pk;
    }
    return $this->query($sql);
  }

  function ambil_berdasar_id($id) {
    $sql = "SELECT * FROM " . $this->tabel . " WHERE " . $this->pk . "='$id'";
    return $this->query($sql);
  }

  function ambil_berdasar_username($username) {
    $sql = "SELECT * FROM " . $this->tabel . " WHERE username='$username'";
    return $this->query($sql);
  }
  
  static function pk(){
    return self::$static_pk;
  }
}