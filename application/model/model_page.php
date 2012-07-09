<?php

class model_page extends application {

  public static $static_tabel = "page";
  public static $static_pk = "id";

  function __construct() {
    $this->db(); // koneksi ke database
    $this->tabel = self::$static_tabel;
    $this->pk = self::$static_pk;
  }

  function ambil_data($klausa=null) {
    $kolom[] = kolom(self::tabel(), "*");
    
    $sql[] = "SELECT";
    $sql[] = implode(",", $kolom); 
    $sql[] = "FROM " . self::tabel();
    
    if ($klausa) {
      $sql[] = $klausa;
    } else {
      $sql[] = "ORDER BY ". kolom(self::tabel(), self::pk());
    }
    return $this->query(implode(" ", $sql));
  }

  function ambil_berdasar_id($id) {
    $klausa = "WHERE ". kolom(self::tabel(), self::pk()) ." = '$id'";
    return $this->ambil_data($klausa);
  }
  
  function ambil_berdasar_kode($kode) {
    $klausa = "WHERE ". kolom(self::tabel(), "kode") ." = '$kode'";
    return $this->ambil_data($klausa);
  }
  
  static function pk(){
    return self::$static_pk;
  }
    
  static function tabel(){
    return self::$static_tabel;
  }
}