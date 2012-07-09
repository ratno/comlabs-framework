<?php

class model_nama_tabel extends application {
  
  public static $static_tabel = "nama_tabel";
  public static $static_pk = "id";

  function __construct() {
    $this->db(); // koneksi ke database
    $this->tabel = self::$static_tabel;
    $this->pk = self::$static_pk;
  }

  function ambil_data($klausa=null,$page=null,$jml_data_per_page=10) {
    $kolom[] = kolom(self::tabel(), "*");
    
    $sql[] = "SELECT";
    $sql[] = implode(",", $kolom); 
    $sql[] = "FROM " . self::tabel();
    
    if ($klausa) {
      if(is_array($klausa)){
        $sql[] = implode(" ", $klausa);
      } else {
        $sql[] = $klausa;
      }
    } else {
      $sql[] = "ORDER BY ". kolom(self::tabel(), self::pk());
    }
    
    if(!is_empty($page)) {
      $start = ($page-1)*$jml_data_per_page;
      $sql[] = "LIMIT $start,$jml_data_per_page";
    }
    
    return $this->query(implode(" ", $sql));
  }
  
  function hitung_data($klausa=null) {
    $kolom[] = kolom(self::tabel(), self::pk(),"total","count");
    
    $sql[] = "SELECT";
    $sql[] = implode(",", $kolom); 
    $sql[] = "FROM " . self::tabel();
    
    if ($klausa) {
      if(is_array($klausa)){
        $sql[] = implode(" ", $klausa);
      } else {
        $sql[] = $klausa;
      }
    }
    
    $data = $this->query(implode(" ", $sql));
    return $data['total'];
  }

  function ambil_berdasar_id($id) {
    $klausa = "WHERE ". kolom(self::tabel(), self::pk()) ." = '$id'";
    return $this->ambil_data($klausa);
  }
  
  static function pk(){
    return self::$static_pk;
  }
    
  static function tabel(){
    return self::$static_tabel;
  }
}