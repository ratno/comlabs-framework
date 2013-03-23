<?php

class nama_tabel extends application {

  function __construct() {
    parent::__construct($uri);
  }

  function redirect() {
    header("location: " . url('nama_tabel'));
  }

  function index() {
    $this->daftar();
  }
  
  function daftar($var) {
    cek_keamanan(array("admin","user"));
    $this->model("model_nama_tabel");
    $data['judul'] = "Halaman nama_tabel";
    $data['data'] = $this->model_nama_tabel->ambil_data();
    if(cek_role("admin")){
      $data['aksi'] = array("viewdetail"=>"View","ubah"=>"Ubah","hapus"=>"Hapus");
      $data['link_tambah'] = link_tambah("nama_tabel");
    } else {
      $data['aksi'] = array();
      $data['link_tambah'] = "";
    }
    $this->view("nama_tabel/daftar", $data);
  }
  
  
  function viewdetail($var){
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $data['judul'] = "Detail nama_tabel";
    $data['data'] = $this->model_nama_tabel->ambil_berdasar_id($var[model_nama_tabel::pk()]);
    $this->view('nama_tabel/view', $data);
  }

  function tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $data['judul'] = "Tambah nama_tabel";
    $data['aksi'] = "simpan_tambah";
//    $data['opsi_db'] = opsi($this->model_nama_tabel->ambil_data(),"nama");
    $data['opsi_manual'] = array( "pilihan satu (ini yg disimpan di tabel)"=>"Pilihan Satu (ini yg muncul di halaman web)",
                                  "pilihan dua (ini yg disimpan di tabel)"=>"Pilihan Dua (ini yg muncul di halaman web)",
                                  "pilihan tiga (ini yg disimpan di tabel)"=>"Pilihan Tiga (ini yg muncul di halaman web)"
                                 );
    $data['form_method'] = "POST";
    $this->view('nama_tabel/form', $data);
  }

  function simpan_tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $this->model_nama_tabel->insert($_POST);
    $this->redirect();
  }

  function ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $data['judul'] = "Ubah nama_tabel";
    $data['aksi'] = "simpan_ubah";
    $data['data'] = $this->model_nama_tabel->ambil_berdasar_id($var[model_nama_tabel::pk()]);
    $data['form_method'] = "POST";
    $this->view('nama_tabel/form', $data);
  }

  function simpan_ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $this->model_nama_tabel->update($_POST, $var[model_nama_tabel::pk()]);
    $this->redirect();
  }

  function hapus($var) {
    cek_keamanan(array("admin"));
    $this->model("model_nama_tabel");
    $this->model_nama_tabel->delete($var[model_nama_tabel::pk()]);
    $this->redirect();
  }
  
  function cari(){
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Cari nama_tabel";
    $data['aksi'] = "hasil_pencarian";
    $data['form_method'] = "GET";
    $this->view('nama_tabel/cari', $data);
  }
  
  function hasil_pencarian(){
    cek_keamanan(array("admin","user"));
    $this->model("model_nama_tabel");
    $kondisi_pencarian = array();
    foreach ($_GET as $field=>$isian){
      if(preg_match("/^(inputtgl_)/", $field)){
        $field = substr($field, 9);
        $isian = tanggal($isian);
      }
      if($isian && $isian != "null"){
        $kondisi_pencarian[] = "$field like '%$isian%'";
      }
    }
    if(count($kondisi_pencarian) > 0){
      $kondisi = "WHERE ".implode(" and ", $kondisi_pencarian);
      $data['judul'] = "Hasil Pencarian nama_tabel";
      $data['data'] = $this->model_nama_tabel->ambil_data($kondisi);
      $data['link_tambah'] = "<a href='".url("nama_tabel","cari")."'>Kembali</a>";
      if(cek_role("admin")){
        $data['aksi'] = array("view"=>"View","ubah"=>"Ubah","hapus"=>"Hapus");
      }
      $this->view("nama_tabel/daftar",$data);
    } else {
      $this->cari();
    }
  }
  
  function laporan(){
    cek_keamanan(array("admin","user"));
    $this->model("model_nama_tabel");
    $this->layout = "laporan"; // untuk layout laporan biasanya tanpa menu
    $data['judul'] = "Laporan nama_tabel";
    $data['data'] = $this->model_nama_tabel->ambil_data();
    $this->view("nama_tabel/daftar",$data);
  }
  
  function impor() {
    cek_keamanan(array("admin","user"));
    $this->model("model_nama_tabel");
    parent::impor(__CLASS__,$this->model_nama_tabel->tabel);
  }
}