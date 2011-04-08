<?php

class model_user extends application {

  function __construct() {
    $this->db(); // koneksi ke database
    $this->tabel = "user";
    $this->pk = "id";
  }

  function ambil_data($klausa=null) {
    $sql = "SELECT * FROM " . $this->tabel;
    if ($klausa) {
      $sql .= " " . $klausa;
    }
    return $this->query($sql);
  }

  function ambil_berdasar_id($id) {
    $sql = "SELECT * FROM " . $this->tabel . " WHERE " . $this->pk . "='$id'";
    return $this->query($sql, true);
  }

  function ambil_berdasar_username($username) {
    $sql = "SELECT * FROM " . $this->tabel . " WHERE username='$username'";
    return $this->query($sql, true);
  }

}