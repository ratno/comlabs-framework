<?php

class user extends application {

  function __construct($uri) {
    parent::__construct($uri);
  }

  function redirect() {
    header("location: " . url('user'));
  }

  function index() {
    $this->daftar();
  }
  
  function daftar($var) {
    cek_keamanan(array("admin","user"));
    $this->model("model_user");
    $data['judul'] = "Halaman User";
//    pakai paging manual
//    $data['no_page'] = ($var['page'])?$var['page']:1;
//    $data['jml_data_per_page'] = 10;
//    $data['total_data'] = $this->model_user->hitung_data();
//    $data['data'] = $this->model_user->ambil_data(null,$data['no_page'],$data['jml_data_per_page']);
//    $data['method'] = __FUNCTION__;
    $data['data'] = $this->model_user->ambil_data();
    if(cek_role("admin")){
      $data['aksi'] = array("viewdetail"=>"View","ubah"=>"Ubah","hapus"=>"Hapus");
      $data['link_tambah'] = link_tambah("user");
    }
    $this->view("user/daftar", $data);
  }
  
  function viewdetail($var){
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $data['judul'] = "Detail User";
    $data['data'] = $this->model_user->ambil_berdasar_id($var[model_user::pk()]);
    $this->view('user/view', $data);
  }

  function tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $data['judul'] = "Tambah User";
    $data['aksi'] = "simpan_tambah";
    $data['form_method'] = "POST";
    $this->view('user/form', $data);
  }

  function simpan_tambah() {
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $this->model_user->insert($_POST);
    $this->redirect();
  }

  function ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $data['judul'] = "Ubah User";
    $data['aksi'] = "simpan_ubah";
    $data['form_method'] = "POST";
    $data['data'] = $this->model_user->ambil_berdasar_id($var[model_user::pk()]);
    $this->view('user/form', $data);
  }

  function simpan_ubah($var) {
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $this->model_user->update($_POST, $var[model_user::pk()]);
    $this->redirect();
  }

  function hapus($var) {
    cek_keamanan(array("admin"));
    $this->model("model_user");
    $this->model_user->delete($var[model_user::pk()]);
    $this->redirect();
  }
    
  function cari(){
    cek_keamanan(array("admin","user"));
    $data['judul'] = "Cari User";
    $data['form_method'] = "GET";
    $data['aksi'] = "hasil_pencarian";
    $this->view('user/cari', $data);
  }
  
  function hasil_pencarian(){
    cek_keamanan(array("admin","user"));
    $this->model("model_user");
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
      $data['judul'] = "Hasil Pencarian User";
      $data['data'] = $this->model_user->ambil_data($kondisi);
      $data['link_tambah'] = "<a href='".url("user","cari")."'>Kembali</a>";
      if(cek_role("admin")){
        $data['aksi'] = array("view"=>"View","ubah"=>"Ubah","hapus"=>"Hapus");
      }
      $this->view("user/daftar",$data);
    } else {
      $this->cari();
    }
  }
  
  function laporan(){
    cek_keamanan(array("admin","user"));
    $this->model("model_user");
    $this->layout = "laporan"; // untuk layout laporan biasanya tanpa menu
    $data['judul'] = "Laporan User";
    $data['data'] = $this->model_user->ambil_data();
    $this->view("user/daftar",$data);
  }
  
  function impor() {
    cek_keamanan(array("admin","user"));
    $this->model("model_user");
    parent::impor(__CLASS__,$this->model_user->tabel);
  }
}