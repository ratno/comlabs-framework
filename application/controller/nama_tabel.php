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
}