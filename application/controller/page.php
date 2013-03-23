<?php

class page extends application {

  function __construct($uri) {
    parent::__construct($uri);
  }

  function redirect() {
    header("location: " . url('page'));
  }

  function index($param=null) {
    $this->model("model_page");
    // kita cek, jika ada halamannya berdasar kode, maka kita tampilkan
    $data = $this->model_page->ambil_berdasar_kode($this->uri['request']);
    if($data){
      $this->display($param,$data);
    } else {
      $this->daftar();
    }
  }
  
  function display($param,$data=null){
    $this->model("model_page");
    if(!$data){
      $kode = key($param);
      if($param[$kode]) {
        $data = $this->model_page->ambil_berdasar_id($param[$kode]);
      } else {
        $data = $this->model_page->ambil_berdasar_kode($kode);
      }
    }
    if(is_empty($data['akses'])){
      $akses = array("public");
    } else {
      $akses = explode(",", $data['akses']);
    }
    cek_keamanan($akses);
    $this->view("page/display",$data);
  }
  
  function daftar($var) {
    cek_keamanan(array("admin","user"));
    $this->model("model_page");
    $data['judul'] = "Daftar Page";
    $data['data'] = $this->model_page->ambil_data();
    if(cek_role("admin")){
      $data['aksi'] = array("display"=>"Display","ubah"=>"Ubah","hapus"=>"Hapus");
      $data['link_tambah'] = link_tambah("page");
    } else {
      $data['aksi'] = array();
      $data['link_tambah'] = "";
    }
    $this->view("page/daftar", $data);
  }

  function tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_page");
    $data['judul'] = "Tambah Page";
    $data['aksi'] = "simpan_tambah";
//    $data['opsi_db'] = opsi($this->model_page->ambil_data(),"nama");
    $data['opsi_manual'] = array( "pilihan satu (ini yg disimpan di tabel)"=>"Pilihan Satu (ini yg muncul di halaman web)",
                                  "pilihan dua (ini yg disimpan di tabel)"=>"Pilihan Dua (ini yg muncul di halaman web)",
                                  "pilihan tiga (ini yg disimpan di tabel)"=>"Pilihan Tiga (ini yg muncul di halaman web)"
                                 );
    
    $this->view('page/form', $data);
  }

  function simpan_tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_page");
    $this->model_page->insert($_POST);
    $this->redirect();
  }

  function ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_page");
    $data['judul'] = "Ubah Page";
    $data['aksi'] = "simpan_ubah";
    $data['data'] = $this->model_page->ambil_berdasar_id($var[model_page::pk()]);
    $this->view('page/form', $data);
  }

  function simpan_ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_page");
    $this->model_page->update($_POST, $var[model_page::pk()]);
    $this->redirect();
  }

  function hapus($var) {
    cek_keamanan(array("admin"));
    $this->model("model_page");
    $this->model_page->delete($var[model_page::pk()]);
    $this->redirect();
  }
  
  function impor() {
    cek_keamanan(array("admin","user"));
    $this->model("model_page");
    parent::impor(__CLASS__,$this->model_page->tabel);
  }
}