<?php

class index extends application {

  function __construct($uri) {
    parent::__construct($uri);
  }

  function index() {
    $data['judul'] = "Selamat Datang";
    $this->view('index/index', $data);
  }

  function login() {
    $this->model("model_user");
    $data['judul'] = "Halaman Login";
    if ($_POST) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      // cek ke database
      $user = $this->model_user->ambil_berdasar_username($username);
      if ($user) { // jika user ada, cek passwordnya
        if ($password == $user['password']) { // password cucok
          set_keamanan($user);
          header("Location: " . url("index", "control_panel"));
        } else {
          msg("Password Anda Salah");
        }
      } else {
        msg("Username Anda Tidak Ditemukan");
      }
    }
    $this->view('index/login', $data);
  }

  function logout() {
    cek_keamanan(array("admin", "user"));
    unset($_SESSION);
    session_destroy();
    header("Location: " . url("index"));
  }

  function control_panel() {
    $user = cek_keamanan(array("admin", "user"));
    $data['judul'] = "Selamat Datang " . $user['nama'];
    $this->view('index/cpanel', $data);
  }
}