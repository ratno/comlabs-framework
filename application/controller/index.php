<?php

class index extends application {

  function __construct() {
    $this->loadModel("model_user");
  }

  function index() {
    $data['judul'] = "Selamat Datang";
    $this->loadView('index/index', $data);
  }

  function login() {
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
          $data['pesan'] = "Password Anda Salah";
        }
      } else {
        $data['pesan'] = "Username Anda Tidak Ditemukan";
      }
    }

    $this->loadView('index/login', $data);
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
    $this->loadView('index/index', $data);
  }

}