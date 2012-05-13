<?php

class page extends application {

  function __construct() {
    $this->loadModel("model_page");
  }

  function redirect() {
    header("location: " . url('page'));
  }

  function index() {
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Daftar Page";
    $data['data'] = $this->model_page->ambil_data();
    if(cek_role("admin")){
      $data['aksi'] = array("ubah"=>"Ubah","hapus"=>"Hapus");
      $data['link_tambah'] = link_tambah("page");
    } else {
      $data['aksi'] = array();
      $data['link_tambah'] = "";
    }
    $this->loadView("page/index", $data);
  }

  function tambah() {
    cek_keamanan(array("admin"));
    $data['judul'] = "Tambah Page";
    $data['aksi'] = "simpan_tambah";
//    $data['opsi_db'] = opsi($this->model_page->ambil_data(),"nama");
    $data['opsi_manual'] = array( "pilihan satu (ini yg disimpan di tabel)"=>"Pilihan Satu (ini yg muncul di halaman web)",
                                  "pilihan dua (ini yg disimpan di tabel)"=>"Pilihan Dua (ini yg muncul di halaman web)",
                                  "pilihan tiga (ini yg disimpan di tabel)"=>"Pilihan Tiga (ini yg muncul di halaman web)"
                                 );
    
    $this->loadView('page/form', $data);
  }

  function simpan_tambah() {
    cek_keamanan(array("admin"));
    $this->model_page->insert($_POST);
    $this->redirect();
  }

  function ubah($var) {
    cek_keamanan(array("admin"));
    $data['judul'] = "Ubah Page";
    $data['aksi'] = "simpan_ubah";
    $data['data'] = $this->model_page->ambil_berdasar_id($var['id']);
    $this->loadView('page/form', $data);
  }

  function simpan_ubah($var) {
    cek_keamanan(array("admin"));
    $this->model_page->update($_POST, $var['id']);
    $this->redirect();
  }

  function hapus($var) {
    cek_keamanan(array("admin"));
    $this->model_page->delete($var['id']);
    $this->redirect();
  }
  
  function cari(){
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Cari page";
    $data['aksi'] = "hasil_pencarian";
    $this->loadView('page/cari', $data);
  }
  
  function hasil_pencarian(){
    cek_keamanan(array("admin","user"));
    $kondisi_pencarian = array();
    foreach ($_POST as $field=>$isian){
      if($isian && $isian != "null"){
        $kondisi_pencarian[] = "$field like '%$isian%'";
      }
    }
    if(count($kondisi_pencarian) > 0){
      $kondisi = "WHERE ".implode(" and ", $kondisi_pencarian);
      $data['judul'] = "Hasil Pencarian page";
      $data['data'] = $this->model_page->ambil_data($kondisi);
      $data['link_tambah'] = "<a href='".url("page","cari")."'>Kembali</a>";
      $this->loadView("page/index",$data);
    } else {
      $this->cari();
    }
  }
  
  function laporan(){
    cek_keamanan(array("admin","user"));
    $this->layout = "laporan"; // untuk layout laporan biasanya tanpa menu
    $data['judul'] = "Laporan page";
    $data['data'] = $this->model_page->ambil_data();
    $this->loadView("page/index",$data);
  }
  
  function impor() {
    cek_keamanan(array("admin","user"));
    parent::impor(__CLASS__,$this->model_page->tabel);
  }
}