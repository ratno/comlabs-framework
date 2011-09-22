<?php

class nama_tabel extends application {

  function __construct() {
    $this->loadModel("model_nama_tabel");
  }

  function redirect() {
    header("location: " . url('nama_tabel'));
  }

  function index() {
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Halaman nama_tabel";
    $data['data'] = $this->model_nama_tabel->ambil_data();
    if(cek_role("admin")){
      $data['aksi'] = array("ubah"=>"Ubah","hapus"=>"Hapus");
      $data['link_tambah'] = link_tambah("nama_tabel");
    } else {
      $data['aksi'] = array();
      $data['link_tambah'] = "";
    }
    $this->loadView("nama_tabel/index", $data);
  }

  function tambah() {
    cek_keamanan(array("admin"));
    $data['judul'] = "Tambah nama_tabel";
    $data['aksi'] = "simpan_tambah";
    $data['atasan'] = $this->model_nama_tabel->ambil_data();
    $this->loadView('nama_tabel/form', $data);
  }

  function simpan_tambah() {
    cek_keamanan(array("admin"));
    $this->model_nama_tabel->insert($_POST);
    $this->redirect();
  }

  function ubah($var) {
    cek_keamanan(array("admin"));
    $data['judul'] = "Ubah nama_tabel";
    $data['aksi'] = "simpan_ubah";
    $data['data'] = $this->model_nama_tabel->ambil_berdasar_id($var['id']);
    $this->loadView('nama_tabel/form', $data);
  }

  function simpan_ubah($var) {
    cek_keamanan(array("admin"));
    $this->model_nama_tabel->update($_POST, $var['id']);
    $this->redirect();
  }

  function hapus($var) {
    cek_keamanan(array("admin"));
    $this->model_nama_tabel->delete($var['id']);
    $this->redirect();
  }
  
  function cari(){
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Cari nama_tabel";
    $data['aksi'] = "hasil_pencarian";
    $this->loadView('nama_tabel/cari', $data);
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
      $data['judul'] = "Hasil Pencarian nama_tabel";
      $data['data'] = $this->model_nama_tabel->ambil_data($kondisi);
      $data['link_tambah'] = "<a href='".url("nama_tabel","cari")."'>Kembali</a>";
      $this->loadView("nama_tabel/index",$data);
    } else {
      $this->cari();
    }
  }
  
  function laporan(){
    cek_keamanan(array("admin","user"));
    $this->layout = "laporan"; // untuk layout laporan biasanya tanpa menu
    $data['judul'] = "Laporan nama_tabel";
    $data['data'] = $this->model_nama_tabel->ambil_data();
    $this->loadView("nama_tabel/index",$data);
  }
}